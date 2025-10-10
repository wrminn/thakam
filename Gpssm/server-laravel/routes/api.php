<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BinRequestController;
use App\Http\Controllers\EmergencyController;
use Illuminate\Http\Request;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneralRequestController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::middleware('auth.jwt')->get('me', [AuthController::class, 'me']);
});

Route::get('ping', fn () => response()->json(['ok' => true, 't' => now()->getTimestampMs()]));
Route::post('general-requests', [GeneralRequestController::class, 'store']);
Route::post('bin-requests', [BinRequestController::class, 'store']);
Route::get('bin-requests', [BinRequestController::class, 'index']);

Route::middleware('auth.jwt')->group(function () {
    Route::get('payments/me', [PaymentController::class, 'me']);
    Route::get('payments/admin/summary', [PaymentController::class, 'adminSummary']);
});
Route::get('emergencies',  [EmergencyController::class, 'index']);
Route::post('emergencies', [EmergencyController::class, 'store']);

Route::get('general-requests',  [GeneralRequestController::class, 'index']);
Route::post('general-requests', [GeneralRequestController::class, 'store']);
Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
$u = $request->user();


// รวมชื่อจากคอลัมน์ที่เป็นไปได้ (เผื่อ schema เก่า)
$name = $u->name ?? $u->fullname ?? $u->username ?? '';


return response()->json([
'name' => $name,
'email' => $u->email,
'status' => $u->status ?? 'ปกติ',
'type' => $u->type ?? 'ปกติ',
// คอลัมน์ที่เราเพิ่มไว้ตอนสมัคร
'house_no' => $u->house_no,
'village' => $u->village,
'subdistrict' => $u->subdistrict,
'district' => $u->district,
'province' => $u->province,
'profile' => [
'prefix' => $u->prefix,
'phone' => $u->phone,
'age' => $u->age,
'address'=> [
'houseNo' => $u->house_no,
'village' => $u->village,
'subdistrict'=> $u->subdistrict,
'district' => $u->district,
'province' => $u->province,
],
],
]);
});
