<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('change_logs', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type');          // Тип сущности
            $table->unsignedBigInteger('entity_id'); // ID измененной записи
            $table->json('before_change')->nullable();          // Старые значения
            $table->json('after_change')->nullable();           // Новые значения
            $table->unsignedBigInteger('created_by')->nullable(); // ID пользователя, который сделал изменение
            $table->string('operation_type'); // Тип операции
            $table->timestamps();                   // created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_logs');
    }
};
