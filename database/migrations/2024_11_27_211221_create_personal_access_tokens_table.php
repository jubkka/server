<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tokenable_id'); // id пользователя
            $table->string('tokenable_type'); // тип модели, в данном случае User
            $table->string('name'); // имя токена (например, 'auth_token')
            $table->text('token'); // сам токен
            $table->json('abilities')->nullable(); // права доступа для токена
            $table->timestamps(); // created_at, updated_at
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
