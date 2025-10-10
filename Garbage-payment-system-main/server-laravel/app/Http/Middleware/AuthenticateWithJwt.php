<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWithJwt
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('token') ?? $request->bearerToken();

        if (! $token) {
            return response()->json(['error' => 'UNAUTHENTICATED'], Response::HTTP_UNAUTHORIZED);
        }

        $secret = env('JWT_SECRET');

        if (! $secret) {
            return response()->json(['error' => 'SERVER_MISCONFIGURED'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $payload = JWT::decode($token, new Key($secret, 'HS256'));
        } catch (\Throwable $e) {
            return response()->json(['error' => 'TOKEN_INVALID'], Response::HTTP_UNAUTHORIZED);
        }

        if (! isset($payload->uid)) {
            return response()->json(['error' => 'TOKEN_INVALID'], Response::HTTP_UNAUTHORIZED);
        }

        if (isset($payload->exp) && Carbon::createFromTimestamp($payload->exp)->isPast()) {
            return response()->json(['error' => 'TOKEN_EXPIRED'], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::query()->find($payload->uid);

        if (! $user) {
            return response()->json(['error' => 'USER_NOT_FOUND'], Response::HTTP_UNAUTHORIZED);
        }

        Auth::setUser($user);
        $request->attributes->set('auth_user', $user);
        $request->attributes->set('auth_user_id', $user->id);

        return $next($request);
    }
}
