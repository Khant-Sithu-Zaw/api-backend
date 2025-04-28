<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('foods', [FoodController::class, 'index']);
Route::get('tags', [FoodController::class, 'getTags']);       // Get all foods

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::post('/register', [UserController::class, 'register']);
Route::post('/ordersave', [OrderController::class, 'saveOrder']);
Route::post('/orderhistory', [OrderController::class, 'getOrderHistory']);
Route::middleware('auth:sanctum')->post('/updatetoken', [UserController::class, 'updateTokenActivity']);
// Route::middleware('auth:sanctum')->post('/session/ping', function (Request $request) {
//     $token = $request->bearerToken();

//     Log::info("🔐 Received Token: " . $token);

//     if (!$token) {
//         return response()->json(['message' => 'Token missing'], 401);
//     }

//     $personalAccessToken = PersonalAccessToken::findToken($token);

//     if (!$personalAccessToken) {
//         return response()->json(['message' => 'Token not found'], 401);
//     }

//     $now = Carbon::now('Asia/Yangon');

//     // If expired, logout and delete the token
//     if ($personalAccessToken->expires_at && $now->greaterThan($personalAccessToken->expires_at)) {
//         $personalAccessToken->delete();
//         return response()->json(['message' => 'Session expired'], 401);
//     }

//     // If not expired, update session
//     $personalAccessToken->last_used_at = $now;
//     $personalAccessToken->expires_at = $now->copy()->addMinutes(10); // extend 30 mins
//     $personalAccessToken->save();

//     return response()->json(['message' => 'Session refreshed']);
// });
// 