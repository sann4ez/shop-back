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
        Schema::create('pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->nullable()->index();
            $table->string('name')->nullable();;
            $table->longText('body')->nullable();
            $table->json('added')->nullable();
            $table->json('locales')->nullable();
            $table->string('status')->index();
            $table->string('template')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
