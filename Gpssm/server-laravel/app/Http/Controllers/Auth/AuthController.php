<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    public function register(Request $request)
{
    // รวมชื่อจากหลายคีย์ให้เป็น name
    $name = $request->input('name')
        ?? $request->input('fullName')
        ?? $request->input('full_name');

    if ($name) {
        $request->merge(['name' => $name]);
    }

    // ถ้าไม่มี password_confirmation จะไม่บังคับ confirmed (ยังคง min:9)
    $passwordRules = ['required', 'string', 'min:9'];
    if ($request->has('password_confirmation')) {
        $passwordRules[] = 'confirmed';
    }

    $validated = $request->validate([
        'name' => ['required','string','max:255'],
        'email' => ['required','email','max:255','unique:users,email'],
        'password' => $passwordRules,

        'profile.prefix' => ['nullable','string','max:50'],
        'profile.phone' => ['nullable','string','max:30'],
        'profile.age' => ['nullable','integer','min:0','max:120'],

        'profile.address.houseNo' => ['nullable','string','max:100'],
        'profile.address.village' => ['nullable','string','max:100'],
        'profile.address.subdistrict' => ['nullable','string','max:100'],
        'profile.address.district' => ['nullable','string','max:100'],
        'profile.address.province' => ['nullable','string','max:100'],
    ]);

    $hashed = Hash::make($validated['password']);

    // เตรียม payload ครั้งเดียว
    $data = [
        'name'       => $validated['name'],
        'email'      => $validated['email'],
        'password'   => $hashed,
        'prefix'     => data_get($validated, 'profile.prefix'),
        'phone'      => data_get($validated, 'profile.phone'),
        'age'        => data_get($validated, 'profile.age'),
        'house_no'   => data_get($validated, 'profile.address.houseNo'),
        'village'    => data_get($validated, 'profile.address.village'),
        'subdistrict'=> data_get($validated, 'profile.address.subdistrict'),
        'district'   => data_get($validated, 'profile.address.district'),
        'province'   => data_get($validated, 'profile.address.province'),
    ];

    // ถ้าตาราง users มีคอลัมน์ password_hash (ระบบเก่า)
    if (Schema::hasColumn('users', 'password_hash')) {
        $data['password_hash'] = $hashed;
    }

    $user = User::create($data);

    return response()->json([
        'ok' => true,
        'user' => $user,
    ], 201);
}

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $data = $validator->validated();
        $user = User::query()->where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password_hash)) {
            return response()->json(['error' => 'Invalid email or password'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($user, Response::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        return response()->json(['ok' => true])->withCookie(
            cookie(
                name: 'token',
                value: null,
                minutes: -1,
                path: '/',
                secure: app()->environment('production'),
                httpOnly: true,
                sameSite: 'lax'
            )
        );
    }

    public function me(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->attributes->get('auth_user');

        return response()->json([
            'user' => $user ? [
                'id' => $user->id,
                'email' => $user->email,
                'fullName' => $user->full_name,
                'createdAt' => optional($user->created_at)->toIso8601String(),
            ] : null,
        ]);
    }

    protected function respondWithToken(User $user, int $status): JsonResponse
    {
        $token = $this->createJwt($user->id);
        $cookie = cookie(
            name: 'token',
            value: $token,
            minutes: $this->cookieMinutes(),
            path: '/',
            secure: app()->environment('production'),
            httpOnly: true,
            sameSite: 'lax'
        );

        return response()->json([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'fullName' => $user->full_name,
            ],
        ], $status)->withCookie($cookie);
    }

    protected function createJwt(int $userId): string
    {
        $expiresAt = Carbon::now()->addMinutes($this->cookieMinutes());

        $secret = env('JWT_SECRET');

        if (! $secret) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'JWT secret is not configured');
        }

        $payload = [
            'sub' => 'user',
            'uid' => $userId,
            'iat' => Carbon::now()->getTimestamp(),
            'exp' => $expiresAt->getTimestamp(),
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    protected function cookieMinutes(): int
    {
        return (int) (60 * 24 * 7);
    }
}
