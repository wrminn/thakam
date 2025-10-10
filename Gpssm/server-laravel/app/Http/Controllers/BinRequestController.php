<?php

namespace App\Http\Controllers;

use App\Models\BinRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class BinRequestController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'date' => ['required', 'string'],
            'subject' => ['required', 'string'],
            'prefix' => ['nullable', 'string'],
            'fullName' => ['required', 'string'],
            'age' => ['nullable', 'integer'],
            'phone' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'houseNo' => ['required', 'string'],
            'moo' => ['nullable', 'string'],
            'road' => ['nullable', 'string'],
            'subdistrict' => ['required', 'string'],
            'district' => ['required', 'string'],
            'province' => ['required', 'string'],
            'postcode' => ['required', 'string'],
            'placeType' => ['required', 'string'],
            'placeTypeOther' => ['nullable', 'string'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
            'detail' => ['nullable', 'string'],
            'consent' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'error' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = $validator->validated();
        $files = $request->file('attachments', []);
        $attachments = [];

        foreach ($files as $file) {
            if (! $file->isValid()) {
                continue;
            }

            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid()->toString().($extension ? ".{$extension}" : '');

            $stored = $file->storeAs(
                'uploads',
                $filename,
                'public'
            );

            $attachments[] = [
                'filename' => $file->getClientOriginalName(),
                'url' => Storage::disk('public')->url($stored),
                'mimetype' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ];
        }

        $binRequest = BinRequest::query()->create([
            'user_id' => auth()->id(),
            'date' => $data['date'],
            'subject' => $data['subject'],
            'prefix' => $data['prefix'] ?? null,
            'full_name' => $data['fullName'],
            'age' => $data['age'] ?? null,
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'house_no' => $data['houseNo'],
            'moo' => $data['moo'] ?? null,
            'road' => $data['road'] ?? null,
            'subdistrict' => $data['subdistrict'],
            'district' => $data['district'],
            'province' => $data['province'],
            'postcode' => $data['postcode'],
            'place_type' => $data['placeType'],
            'place_type_other' => $data['placeTypeOther'] ?? null,
            'lat' => $data['lat'] ?? null,
            'lng' => $data['lng'] ?? null,
            'detail' => $data['detail'] ?? null,
            'attachments' => $attachments,
            'consent' => filter_var($data['consent'], FILTER_VALIDATE_BOOL),
        ]);

        return response()->json(['ok' => true, 'id' => $binRequest->id], Response::HTTP_CREATED);
    }

    public function index(): JsonResponse
    {
        $rows = BinRequest::query()->orderByDesc('created_at')->limit(100)->get();

        return response()->json([
            'ok' => true,
            'rows' => $rows,
        ]);
    }
}
