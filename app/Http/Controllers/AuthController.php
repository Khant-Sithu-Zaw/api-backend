<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    // User Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // $user = User::where('email', $request->email)->first();
        $user = User::with('userDetail')->where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email does not exist'], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password is incorrect'], 401);
        }
        $newToken = $user->createToken('API Token', ['*']); // returns a NewAccessToken instance
        $plainTextToken = $newToken->plainTextToken;
        $accessToken = $newToken->accessToken; // this is the PersonalAccessToken model instance

        // Update expiration and last used
        $now = Carbon::now('Asia/Yangon');
        $expiresAt = $now->copy()->addMinutes(60);
        $accessToken->last_used_at = $now;
        $accessToken->expires_at = $expiresAt;
        $accessToken->save();

        return response()->json([
            'token_type' => 'Bearer',
            'token'      => $plainTextToken,
            'expiresAt'  => $expiresAt->toIso8601String(),
            'lastUsed'   => $now->toIso8601String(),
            'user'       => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'userDetail' => $user->userDetail,
            ],
        ]);
    }

    public function logout(Request $request)
    { //delete all tokens of user from all devices
      //  $request->user()->tokens()->delete();
      //delete only the current token
   
        if ($request->user()) {
            // Delete only the current token
            $request->user()->currentAccessToken()->delete();

            // Optionally, you can log the user out from all devices:
            // $request->user()->tokens()->delete(); // Uncomment this if you want to delete all tokens for the user

            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'Not authenticated'], 401);
    }
}
