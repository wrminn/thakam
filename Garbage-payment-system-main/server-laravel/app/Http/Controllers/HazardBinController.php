<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HazardBinController extends Controller
{
    /** เลือกชื่อตารางจาก .env หรือ candidate ที่พบได้บ่อย */
    protected function resolveTable(): string
    {
        $envTable = env('HAZARD_BINS_TABLE');
        if ($envTable && Schema::hasTable($envTable)) return $envTable;

        foreach (['hazard_bins', 'hazard_dumps', 'bins'] as $t) {
            if (Schema::hasTable($t)) return $t;
        }
        return 'hazard_bins';
    }

    /** ดึง metadata คอลัมน์แบบละเอียด (SHOW COLUMNS) */
    protected function getColumnsMeta(string $table): array
    {
        // Field, Type, Null, Key, Default, Extra
        $rows = DB::select("SHOW COLUMNS FROM `$table`");
        $meta = [];
        foreach ($rows as $r) {
            $name = strtolower($r->Field);
            $meta[$name] = [
                'name' => $name,
                'type' => strtolower($r->Type),
            ];
        }
        return $meta;
    }

    /** ตรวจว่า type เป็นตัวเลขทศนิยม */
    protected function isDecimalType(string $type): bool
    {
        return str_contains($type, 'decimal')
            || str_contains($type, 'double')
            || str_contains($type, 'float')
            || str_contains($type, 'real');
    }

    /** เดาคอลัมน์ lat/lng/note อย่างยืดหยุ่นมากขึ้น + ใช้ sample values ช่วย */
    protected function resolveColumnsSmart(string $table): array
    {
        // .env override (ใช้ได้เฉพาะเมื่อคอลัมน์มีจริง)
        $override = [
            'lat'  => env('HAZARD_LAT_COLUMN'),
            'lng'  => env('HAZARD_LNG_COLUMN'),
            'note' => env('HAZARD_NOTE_COLUMN'),
            'geom' => env('HAZARD_GEOM_COLUMN'), // POINT
        ];

        $meta = $this->getColumnsMeta($table);
        $cols = array_keys($meta);

        $has = fn(string $c) => in_array(strtolower($c), $cols, true);

        if ($override['geom'] && $has($override['geom'])) {
            return ['geom' => $override['geom'], 'lat' => null, 'lng' => null, 'note' => $has($override['note']) ? $override['note'] : null];
        }

        $lat = ($override['lat'] && $has($override['lat'])) ? strtolower($override['lat']) : null;
        $lng = ($override['lng'] && $has($override['lng'])) ? strtolower($override['lng']) : null;
        $note = ($override['note'] && $has($override['note'])) ? strtolower($override['note']) : null;

        // 1) เดาจากชื่อคอลัมน์ก่อน
        $findByName = function(array $patterns, array $except = []) use ($cols) {
            foreach ($patterns as $p) {
                foreach ($cols as $c) {
                    if (in_array($c, $except, true)) continue;
                    if (preg_match($p, $c)) return $c;
                }
            }
            return null;
        };

        $geom = $findByName([
            '/^(location|geom|point|coordinates|latlng)$/i',
        ]);

        // ถ้า geometry POINT จริง ให้ใช้ geom
        if ($geom) {
            $t = $meta[$geom]['type'] ?? '';
            if (str_contains($t, 'point')) {
                if (!$note) $note = $findByName(['/^(note|description|desc)$/i']);
                return ['geom' => $geom, 'lat' => null, 'lng' => null, 'note' => $note];
            }
        }

        if (!$lng) {
            // จงใจรองรับ 'long' (ไม่ใช่ 'longitude') และกันชนกับ 'length'
            $lng = $findByName([
                '/(^|_)(longitude|lng|lon|long)(_|$)/i',
                '/(^|_)x(_|$)/i',
            ]);
            if ($lng === 'length') $lng = null; // กันผิดพลาด
        }

        if (!$lat) {
            $lat = $findByName([
                '/(^|_)(latitude|lat)(_|$)/i',
                '/(^|_)y(_|$)/i',
            ], $lng ? [$lng] : []);
        }

        if (!$note) {
            $note = $findByName(['/^(note|description|desc|remark|details?)$/i']);
        }

        // 2) ถ้ายังหาไม่ครบ ใช้ชนิดข้อมูลช่วย (เอาเฉพาะ numeric ทศนิยม)
        $decimalCols = [];
        foreach ($meta as $name => $m) {
            if ($this->isDecimalType($m['type'])) $decimalCols[] = $name;
        }

        // 3) ใช้ sample values เดาช่วย
        $sampleCols = array_unique(array_filter([$lat, $lng, ...$decimalCols]));
        $sampleCols = array_values($sampleCols);

        if (!empty($sampleCols)) {
            $rows = DB::table($table)->select($sampleCols)->orderByDesc('id')->limit(50)->get();

            $score = fn(string $col, callable $fn) => collect($rows)->filter(fn($r) => isset($r->$col) && is_numeric($r->$col) && $fn((float)$r->$col))->count();

            // ถ้า lng ยังไม่เจอ ลองหาคอลัมน์ที่ค่ามักจะอยู่ในช่วง [-180, 180] และ "มักจะ > 90" มากกว่า lat
            if (!$lng) {
                $bestLng = null; $bestScore = -1;
                foreach ($decimalCols as $c) {
                    if ($c === $lat) continue;
                    $s = $score($c, fn($v) => $v >= -180 && $v <= 180 && abs($v) >= 90);
                    if ($s > $bestScore) { $bestScore = $s; $bestLng = $c; }
                }
                if ($bestLng) $lng = $bestLng;
            }

            // ถ้า lat ยังไม่เจอ ลองหาคอลัมน์ที่ค่ามักจะอยู่ในช่วง [-90, 90]
            if (!$lat) {
                $bestLat = null; $bestScore = -1;
                foreach ($decimalCols as $c) {
                    if ($c === $lng) continue;
                    $s = $score($c, fn($v) => $v >= -90 && $v <= 90);
                    if ($s > $bestScore) { $bestScore = $s; $bestLat = $c; }
                }
                if ($bestLat) $lat = $bestLat;
            }
        }

        return ['geom' => null, 'lat' => $lat, 'lng' => $lng, 'note' => $note];
    }

    /** SELECT builder สำหรับ index() */
    protected function buildSelect(string $table, array $map)
    {
        if ($map['geom']) {
            // POINT(lng lat) → ST_X = lng, ST_Y = lat
            $q = DB::table($table)->select([
                DB::raw('id'),
                DB::raw("ST_Y(`{$map['geom']}`) as lat"),
                DB::raw("ST_X(`{$map['geom']}`) as lng"),
            ]);
        } else {
            $q = DB::table($table)->select([
                DB::raw('id'),
                DB::raw("`{$map['lat']}` as lat"),
                DB::raw("`{$map['lng']}` as lng"),
            ]);
        }

        if (!empty($map['note'])) {
            $q->addSelect(DB::raw("`{$map['note']}` as note"));
        } else {
            $q->addSelect(DB::raw("NULL as note"));
        }

        return $q;
    }

    /** GET /api/hazard-bins */
    public function index()
    {
        $table = $this->resolveTable();
        if (!Schema::hasTable($table)) {
            return response()->json(['message' => "Table '$table' not found."], 500);
        }

        $map = $this->resolveColumnsSmart($table);

        if (!$map['geom'] && (empty($map['lat']) || empty($map['lng']))) {
            return response()->json([
                'message' => "hazard_bins table missing latitude/longitude columns.",
                'table'   => $table,
                'columns' => Schema::getColumnListing($table),
                'hint'    => "กำหนดใน .env: HAZARD_LAT_COLUMN=..., HAZARD_LNG_COLUMN=... (หรือใช้ HAZARD_GEOM_COLUMN=location ถ้าเป็น POINT)",
            ], 500);
        }

        return $this->buildSelect($table, $map)->orderBy('id', 'desc')->get();
    }

    /** POST /api/hazard-bins */
    public function store(Request $req)
    {
        $table = $this->resolveTable();
        $map   = $this->resolveColumnsSmart($table);

        $lat  = $req->input('lat',  $req->input('latitude'));
        $lng  = $req->input('lng',  $req->input('longitude'));
        $note = $req->input('note');

        if ($lat === null || $lng === null) {
            return response()->json(['message' => 'lat/lng (or latitude/longitude) are required'], 422);
        }

        if ($map['geom']) {
            $row = [];
            if (!empty($map['note'])) $row[$map['note']] = $note;
            $row[$map['geom']] = DB::raw("ST_GeomFromText('POINT(".((float)$lng)." ".((float)$lat).")')");
            $id = DB::table($table)->insertGetId($row);
        } else {
            if (empty($map['lat']) || empty($map['lng'])) {
                return response()->json(['message' => 'Server not configured for lat/lng columns'], 500);
            }
            $row = [
                $map['lat'] => (float)$lat,
                $map['lng'] => (float)$lng,
            ];
            if (!empty($map['note'])) $row[$map['note']] = $note;
            $id = DB::table($table)->insertGetId($row);
        }

        return response()->json([
            'id'   => $id,
            'lat'  => (float)$lat,
            'lng'  => (float)$lng,
            'note' => $note,
        ], 201);
    }

    /** PUT /api/hazard-bins/{id} */
    public function update(Request $req, $id)
    {
        $table = $this->resolveTable();
        $map   = $this->resolveColumnsSmart($table);

        $lat = $req->input('lat', $req->input('latitude'));
        $lng = $req->input('lng', $req->input('longitude'));

        if ($lat === null || $lng === null) {
            return response()->json(['message' => 'lat/lng (or latitude/longitude) are required'], 422);
        }

        if ($map['geom']) {
            $geom = DB::raw("ST_GeomFromText('POINT(".((float)$lng)." ".((float)$lat).")')");
            $affected = DB::table($table)->where('id', $id)->update([$map['geom'] => $geom]);
        } else {
            if (empty($map['lat']) || empty($map['lng'])) {
                return response()->json(['message' => 'Server not configured for lat/lng columns'], 500);
            }
            $affected = DB::table($table)->where('id', $id)->update([
                $map['lat'] => (float)$lat,
                $map['lng'] => (float)$lng,
            ]);
        }

        if (!$affected) return response()->json(['message' => 'Not found'], 404);
        return response()->json(['id' => (int)$id, 'lat' => (float)$lat, 'lng' => (float)$lng]);
    }

    /** DELETE /api/hazard-bins/{id} */
    public function destroy(Request $req, $id)
    {
        $table = $this->resolveTable();
        $deleted = DB::table($table)->where('id', $id)->delete();
        if (!$deleted) return response()->json(['message' => 'Not found'], 404);
        return response()->noContent();
    }
}
