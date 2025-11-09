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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->nullable()->index();
            $table->string('sku')->nullable()->index();
            $table->string('sku_extern')->nullable()->index();
            $table->string('barcode')->nullable()->index();
            $table->string('barcodes')->nullable();
            $table->string('status')->index();
            $table->string('name')->nullable();
            $table->longText('body')->nullable();

            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('price_old', 10, 2)->default(0);
            $table->decimal('price_cost', 10)->default(0);
            $table->string('currency_code')->nullable();
            $table->integer('multiplicity')->default(1);
            $table->integer('stock_qty')->default(0);
            $table->integer('limit_qty')->default(0);
            $table->integer('min_qty')->default(1);
            $table->string('unit')->nullable();
            $table->json('added')->nullable();
            $table->json('fields')->nullable();
            $table->json('switching')->nullable();

            $table->boolean('is_default')->default(false);
            $table->boolean('is_attribute_groped')->default(true);
            $table->string('grouped_id')->nullable()->index();
            $table->timestamp('income_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->json('index')->nullable();

            $table->foreignUuid('product_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
