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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Основні дані
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('login')->nullable()->index();

            $table->string('email')->nullable()->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable()->index();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('telegram_id')->nullable();

            // Пароль та авторизація
            $table->string('password')->nullable();
            $table->rememberToken();

            // Статуси та роль
            $table->string('status')->nullable();
            $table->decimal('discount')->default(0);
            $table->string('created_type')->nullable()->index();
            $table->string('created_step')->nullable()->index();
            $table->string('role')->nullable();

            // Додаткові дані
            $table->date('birthday')->nullable();
            $table->json('added')->nullable();
            $table->json('contacts')->nullable();
            $table->json('fields')->nullable();
            $table->json('notifies')->nullable();
            $table->text('comment')->nullable();

            // Локалізація
            $table->string('locale_code')->nullable()->index();

            // Активність та реєстрація
            $table->timestamp('activity_at')->nullable();
            $table->timestamp('registered_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
