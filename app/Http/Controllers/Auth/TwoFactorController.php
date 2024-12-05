<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\AuthService;

class TwoFactorController extends Controller
{
    protected $twoFactorService;
    protected $authService;

    public function __construct(AuthService $authService, TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
        $this->authService = $authService;
    }

    public function requestCode(Request $request)
    {
        $user = $user = User::where('email', $request->email)->first();
        $clientIp = $request->ip();

        if (cache()->has("user:{$user->id}:2fa_request_count")) {
            $requestCount = cache()->increment("user:{$user->id}:2fa_request_count");
    
            if ($requestCount > 3) {
                return response()->json(['message' => 'Too many requests. Please wait 30 seconds.'], 429);
            }
        } else {
            cache()->put("user:{$user->id}:2fa_request_count", 1, now()->addSeconds(30));
        }

        // Генерация и отправка кода
        $code = $this->twoFactorService->generateCode($user, $clientIp);

        return response()->json([
            'message' => '2FA code sent.',
            'code' => $code
        ]);
    }

    public function verifyCode(Request $request)
    {
        $user = $user = User::where('email', $request->email)->first();

        $code = $request->input('code');
        $clientIp = $request->ip();

        if ($this->twoFactorService->verifyCode($user, $code, $clientIp)) {
            return $this->authService->createTokenResponse($user);
        }

        return response()->json(['message' => 'Invalid or expired 2FA code.'], 401);
    }

    public function toggleTwoFactor(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        // Проверяем, соответствует ли пароль и существует ли пользователь
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user->two_factor_enabled = !$user->two_factor_enabled;
        $user->save();

        return response()->json([
            'message' => $user->two_factor_enabled ? '2FA enabled.' : '2FA disabled.'
        ]);
    }
}
