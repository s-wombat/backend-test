<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Логин и получение токена.
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Создать токен доступа
        $accessToken = $user->createToken('access_token', ['*'])->plainTextToken;

        // Создать refresh-токен с длительным сроком действия
        $refreshToken = $user->createToken('refresh_token', ['refresh'])->plainTextToken;

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            // 'expires_in' => Carbon::now()->addMinutes(config('sanctum.expiration', 60)),
        ]);
    }

    /**
     * Обновление access-токена.
     */
    public function refreshToken(Request $request)
    {
        $validated = $request->validate([
            'refresh_token' => 'required|string',
        ]);

        // Поиск пользователя по токену
        $user = User::whereHas('tokens', function ($query) use ($validated) {
            $query->where('token', hash('sha256', $validated['refresh_token']))
                ->where('name', 'refresh_token');
        })->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        }

        // Удалить старый refresh-токен
        $user->tokens()->where('name', 'refresh_token')->delete();

        // Создать новый токен
        $newAccessToken = $user->createToken('access_token', ['*'])->plainTextToken;
        $newRefreshToken = $user->createToken('refresh_token', ['refresh'])->plainTextToken;

        return response()->json([
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefreshToken,
            'token_type' => 'Bearer',
            // 'expires_in' => Carbon::now()->addMinutes(config('sanctum.expiration', 60)),
        ]);
    }

    /**
     * Логаут и удаление токенов.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}


