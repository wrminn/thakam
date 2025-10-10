<?php

namespace App\Http\Controllers;

use App\Models\Emergency;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EmergencyController extends Controller
{
    // GET /api/emergencies
    public function index(Request $req): JsonResponse
    {
        $perPage = (int) ($req->integer('per_page') ?: 50);

        $page = Emergency::query()
            ->orderByDesc('id')
            ->simplePaginate($perPage);

        $rows = collect($page->items())->map(function (\App\Models\Emergency $e) {
                $photos = [];
                $raw = is_array($e->photo) ? $e->photo : json_decode($e->photo ?? '[]', true);

                foreach (($raw ?: []) as $f) {
                    $p = $f['path'] ?? ($f['original'] ?? null);

                    if ($p) {
                        // ✅ ถ้าเป็น URL เต็มอยู่แล้ว
                        if (preg_match('~^https?://~i', $p)) {
                            // กรณีเก่าที่ผิดเป็น /storage/public/... → แก้เป็น /storage/...
                            $p = preg_replace('~/storage/public/~i', '/storage/', $p);
                        } else {
                            // กรณีเป็นพาธสัมพัทธ์ → บังคับให้เป็น absolute และแก้ /storage/public → /storage
                            $p = preg_replace('~^/storage/public/~i', '/storage/', $p);
                            $p = url($p);
                        }
                    }

                    $photos[] = [
                        'original' => $f['original'] ?? null,
                        'path'     => $p,
                        'mime'     => $f['mime'] ?? null,
                        'size'     => $f['size'] ?? null,
                    ];
                }

                return [
                    'id'          => $e->id,
                    'category'    => $e->category,
                    'title'       => $e->title,
                    'reporterName'=> $e->reporter_name,
                    'phone'       => $e->phone,
                    'description' => $e->description,
                    'lat'         => $e->lat !== null ? (float)$e->lat : null,
                    'lng'         => $e->lng !== null ? (float)$e->lng : null,
                    'photo'       => $photos,
                    'createdAt'   => optional($e->created_at)->toIso8601String(),
                ];
            })->all();


        return response()->json([
            'ok'   => true,
            'rows' => $rows,
            'meta' => ['next' => $page->nextPageUrl()],
        ]);
    }

    // POST /api/emergencies
    public function store(Request $req): JsonResponse
    {
        // เผื่อ client ส่ง type มา แปลงเป็น category
        if (!$req->has('category') && $req->has('type')) {
            $map = [
                'เหตุต้นไม้ล้ม' => 'tree',
                'เหตุไฟไหม้'     => 'fire',
                'อุบัติเหตุ'     => 'accident',
                'เหตุฉุกเฉิน'    => 'general',
                'เหตุทั่วไป'      => 'general',
            ];
            $req->merge(['category' => $map[(string) $req->input('type')] ?? 'general']);
        }

        $v = Validator::make($req->all(), [
            'category'     => ['required','in:accident,fire,tree,general'],
            'title'        => ['required','string','max:255'], // บังคับมีค่า (ตารางเดิมของคุณ NOT NULL)
            'reporterName' => ['nullable','string','max:255'],
            'phone'        => ['nullable','string','max:64'],
            'description'  => ['nullable','string'],
            'lat'          => ['nullable','numeric','between:-90,90'],
            'lng'          => ['nullable','numeric','between:-180,180'],
            'photo'        => ['nullable','file','mimes:png,jpg,jpeg,webp','max:2048'], // 2MB
            'photoUrl'     => ['nullable','string'],
        ]);

        if ($v->fails()) {
            return response()->json([
                'ok' => false,
                'error' => $v->errors()->first(),
                'errors' => $v->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $v->validated();

            // title กันค่าว่าง
            $safeTitle = trim((string)($data['title'] ?? ''));
            if ($safeTitle === '') {
                $safeTitle = 'การแจ้งเหตุ - ' . strtoupper($data['category']) . ' - ' . now()->format('Y-m-d H:i:s');
            }

            // เก็บรูป → ทำ URL ให้เป็น absolute ตั้งแต่ตอนนี้
           $photo = null;
                if ($req->hasFile('photo')) {
                    $f = $req->file('photo');
                    if (!$f->isValid()) {
                        $errCode = $f->getError();
                        return response()->json([
                            'ok' => false,
                            'error' => "อัปโหลดรูปไม่สำเร็จ (code $errCode)"
                        ], 422);
                    }

                    $filename = \Illuminate\Support\Str::uuid().'.'.strtolower($f->getClientOriginalExtension());

                    // ✅ ใช้ disk('public') โดยตรง (root = storage/app/public)
                    //    จะได้ไฟล์อยู่ที่ storage/app/public/emergencies/<filename>
                    \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('emergencies', $f, $filename);

                    // ✅ สร้าง URL ที่ถูกต้อง เช่น /storage/emergencies/<filename>
                    $relative = 'emergencies/'.$filename;
                    $publicUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($relative); // -> /storage/emergencies/...

                    $photo = [[
                        'original' => $f->getClientOriginalName(),
                        'path'     => url($publicUrl), // ทำเป็น absolute เช่น http://192.168.1.7:8000/storage/emergencies/...
                        'mime'     => $f->getClientMimeType(),
                        'size'     => $f->getSize(),
                    ]];
                } elseif (!empty($data['photoUrl'])) {
                    $p = (string) $data['photoUrl'];
                    $low = strtolower($p);
                    if (!str_starts_with($low, 'http://') && !str_starts_with($low, 'https://') && !str_starts_with($low, 'data:')) {
                        $p = url($p);
                    }
                    $photo = [[ 'original' => basename($p), 'path' => $p ]];
                }
            // เตรียม attributes เฉพาะคอลัมน์ที่มีจริง
            $maybe = [
                'user_id'       => optional($req->user())->id,
                'category'      => $data['category'],
                'title'         => $safeTitle,
                'reporter_name' => $data['reporterName'] ?? null,
                'phone'         => $data['phone'] ?? null,
                'description'   => $data['description'] ?? null,
                'lat'           => $data['lat'] ?? null,
                'lng'           => $data['lng'] ?? null,
                'photo'         => $photo,
            ];
            $cols = array_flip(Schema::getColumnListing('emergencies'));
            $attrs = array_intersect_key($maybe, $cols);

            $row = Emergency::create($attrs);

            return response()->json(['ok' => true, 'id' => $row->id], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
