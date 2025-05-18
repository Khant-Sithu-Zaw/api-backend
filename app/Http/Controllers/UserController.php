<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function register(Request $request){
        $existingUser = User::where('email', $request->input('email'))->first();

        if ($existingUser) {
            return response()->json([
                'error' => 'Email already exists'
            ], 409); // 409 Conflict
        }
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            $profilePicPath = null;

            if ($request->hasFile('profile_pic')) {
                $profilePicPath = $request->file('profile_pic')->store('profile_images', 'public');
            }

            UserDetail::create([
                'user_id' => $user->id,
                'phone' => $request->input('phone'),
                'profile_pic' => $profilePicPath,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'User registered successfully',
                'user_id' => $user->id
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Registration failed',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    public function updateTokenActivity(Request $request)
    {
        $token = $request->bearerToken();

        Log::info("🔐 Received Token: " . $token);

        if (!$token) {
            return response()->json(['message' => 'Token missing'], 401);
        }
        //We don't need to hash the token again here
        //     $hashedToken = hash('sha256', $token);
        //     $personalToken = PersonalAccessToken::where('token', $hashedToken)
        $personalAccessToken = PersonalAccessToken::findToken($token);

        if (!$personalAccessToken) {
            return response()->json(['message' => 'Token not found'], 401);
        }

        $now = Carbon::now('Asia/Yangon');

        // If not expired, update session
        $personalAccessToken->last_used_at = $now;
        $personalAccessToken->expires_at = $now->copy()->addMinutes(60); // extend 30 mins
        $personalAccessToken->save();

                return response()->json([
                    'message' => 'Token activity updated',
                    'lastUsed' => $now->toIso8601String(),
                    'expiresAt' => $now->copy()->addMinutes(60)->toIso8601String()
                ]);
    }

}