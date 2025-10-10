<?php

namespace App\Http\Controllers;

use App\Models\GeneralRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class GeneralRequestController extends Controller
{
    /**
     * GET /api/general-requests
     * ดึงรายการคำร้องทั่วไปสำหรับหน้า Dashboard
     * - ถ้าเป็นแอดมิน เห็นทั้งหมด
     * - ถ้าเป็นผู้ใช้ทั่วไป เห็นเฉพาะของตัวเอง
     */
    public function index(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
{
    $perPage = (int) ($request->integer('per_page') ?: 50);

    $q = \App\Models\GeneralRequest::query()
        ->orderByDesc('id')
        ->select([
            'id','date','subject','prefix','first_name','last_name','age','phone',
            'addr_no','addr_moo','addr_subdistrict','addr_district','addr_province',
            'detail','attachments','consent','created_at',
        ]);

    $page = $q->simplePaginate($perPage);

    $rows = collect($page->items())->map(function ($r) {
        // $r เป็น Model หรือ stdClass ก็ได้ — แปลงให้ตารางใช้ได้ทันที
        $attachments = [];
        $raw = is_array($r->attachments) ? $r->attachments : json_decode($r->attachments ?? '[]', true);
        foreach ($raw ?: [] as $f) {
            $attachments[] = [
                'filename' => $f['original'] ?? (isset($f['path']) ? basename($f['path']) : null),
                'url'      => $f['path'] ?? null,
                'mimetype' => $f['mime'] ?? null,
                'size'     => $f['size'] ?? null,
            ];
        }

        return [
            'id'       => (int)$r->id,
            'date'     => $r->date,
            'subject'  => $r->subject,
            'prefix'   => $r->prefix,
            'fullName' => trim(($r->first_name ?? '').' '.($r->last_name ?? '')) ?: null,
            'age'      => is_numeric($r->age) ? (int)$r->age : (is_string($r->age) ? $r->age : null),
            'phone'    => $r->phone,

            'houseNo'     => $r->addr_no,
            'moo'         => $r->addr_moo,
            'road'        => null,
            'subdistrict' => $r->addr_subdistrict,
            'district'    => $r->addr_district,
            'province'    => $r->addr_province,
            'postcode'    => null,

            'placeType'      => null,
            'placeTypeOther' => null,
            'lat'            => null,
            'lng'            => null,

            'detail'     => $r->detail,
            'attachments'=> $attachments,
            'consent'    => (bool)$r->consent,
            'createdAt'  => optional($r->created_at)->toIso8601String(),
        ];
    })->all();

    return response()->json([
        'ok'   => true,
        'rows' => $rows,
        'meta' => [
            'per_page' => $perPage,
            'next'     => $page->nextPageUrl(),
        ],
    ]);
}

    /**
     * POST /api/general-requests
     * รับบันทึกคำร้องทั่วไป + อัปโหลดไฟล์แนบ
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'date'            => ['required', 'string', 'min:1'],
            'subject'         => ['required', 'string', 'min:1'],
            'firstName'       => ['required', 'string', 'min:1'],
            'lastName'        => ['required', 'string', 'min:1'],
            'age'             => ['required', 'string', 'min:1'],
            'addr_province'   => ['required', 'string', 'min:1'],
            'phone'           => ['required', 'string', 'min:1'],
            'detail'          => ['required', 'string', 'min:1'],
            'attachments.*'   => ['file', 'mimes:png,jpg,jpeg,pdf', 'max:8192'], // 8MB/ไฟล์
        ], [
            'required' => 'กรุณากรอก :attribute',
            'mimes'    => 'ชนิดไฟล์ต้องเป็น PNG, JPG, JPEG หรือ PDF',
            'max'      => 'ไฟล์แนบต้องไม่เกิน 8MB ต่อไฟล์',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok'     => false,
                'error'  => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $validator->validated();

        // อัปโหลดไฟล์แนบ
        $files = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (!$file->isValid()) continue;

                $ext = $file->getClientOriginalExtension();
                $filename = Str::uuid()->toString() . ($ext ? ('.' . strtolower($ext)) : '');
                $path = $file->storeAs('public/general-requests', $filename);

                $files[] = [
                    'original' => $file->getClientOriginalName(),
                    'path'     => Storage::url($path), // /storage/general-requests/xxx
                    'mime'     => $file->getClientMimeType(),
                    'size'     => $file->getSize(),
                ];
            }
        }

        // บันทึกลง DB
        $row = GeneralRequest::query()->create([
            'user_id'          => optional($request->user())->id,
            'date'             => $data['date'],
            'subject'          => $data['subject'],
            'request'          => $request->input('request'),
            'prefix'           => $request->input('prefix'),

            'first_name'       => $data['firstName'],
            'last_name'        => $data['lastName'],
            'age'              => $data['age'],

            'addr_no'          => $request->input('addr_no'),
            'addr_moo'         => $request->input('addr_moo'),
            'addr_subdistrict' => $request->input('addr_subdistrict'),
            'addr_district'    => $request->input('addr_district'),
            'addr_province'    => $data['addr_province'],

            'phone'            => $data['phone'],
            'detail'           => $data['detail'],
            'map_note'         => $request->input('map_note'),

            'attachments'      => $files,
            'consent'          => (bool) $request->boolean('consent', false),
        ]);

        return response()->json([
            'ok' => true,
            'id' => $row->id,
        ], Response::HTTP_CREATED);
    }
}
