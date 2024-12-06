<?php

namespace App\Services;

use App\Models\TwoFactorCode;
use Carbon\Carbon;

class TwoFactorService
{
    protected $codeExpiryMinutes;

    public function __construct()
    {
        $this->codeExpiryMinutes = env('TWO_FACTOR_CODE_EXPIRY', 5);
    }

    public function generateCode($user, $clientIp)
    {
        // Удаляем предыдущий код, если он есть
        TwoFactorCode::where('user_id', $user->id)->delete();

        // Генерируем новый код
        $code = TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => random_int(100000, 999999),
            'client_ip' => $clientIp,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        return $code;
    }

    public function verifyCode($user, $code, $clientIp)
    {
        $twoFactorCode = TwoFactorCode::where('user_id', $user->id)
            ->where('code', $code)
            ->where('client_ip', $clientIp)
            ->first();

        if (!$twoFactorCode || $twoFactorCode->isExpired()) {
            return false;
        }

        // Код прошел проверку
        $twoFactorCode->delete();
        return true;
    }
}