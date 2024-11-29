<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        // Регистрация Gate для проверки конкретных разрешений
        Gate::define('permission-check', function (User $user, $permissionCipher) {
            // Проверяем, есть ли у пользователя необходимое разрешение
            return $user->roles()
                        ->whereHas('permissions', function ($query) use ($permissionCipher) {
                            $query->where('cipher', $permissionCipher);
                        })->exists();
        });
    }
}
