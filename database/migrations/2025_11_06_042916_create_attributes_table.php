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
        Schema::create('attributes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('prefix')->nullable();
            $table->integer('weight')->default(0);
            $table->string('help')->nullable();
            $table->boolean('in_filter')->default(false)->comment('Використовувати в фасетных фільтах');
            $table->boolean('in_variant')->default(false)->comment('Використовувати для варіантів товарів');
            $table->boolean('in_specification')->default(false)->comment('Використовувати для виводу інфор. на стор. товара - характеристики');
            $table->boolean('has_image')->default(false)->comment('Можливість використ. зображення');
            $table->string('format')->nullable(); //select, radio, text, textarea
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
