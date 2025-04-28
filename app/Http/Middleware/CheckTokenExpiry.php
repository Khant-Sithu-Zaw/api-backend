<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json(['message' => 'Unauthorized. No token provided.'], 401);
        }

        $token = PersonalAccessToken::where('token', hash('sha256', $bearerToken))->first();

        if (!$token) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        // Parse the timestamps from DB
        $lastUsedAt = $token->last_used_at ?? $token->created_at;
        $expiresAt = $token->expires_at;

        // If both values exist, compare them
        if ($lastUsedAt && $expiresAt) {
            $lastUsed = Carbon::parse($lastUsedAt);
            $expires = Carbon::parse($expiresAt);

            $diff = $expires->diffInMinutes($lastUsed);

            if ($diff >= 10) {
                $token->delete(); // Session considered expired
                return response()->json(['message' => 'Session expired due to 10-minute difference.'], 401);
            }
        }

        // Extend token if still valid
        $now = Carbon::now('Asia/Yangon');
        $token->last_used_at = $now;
        $token->expires_at = $now->copy()->addMinutes(10);
        $token->save();

        return $next($request);
    }
}
