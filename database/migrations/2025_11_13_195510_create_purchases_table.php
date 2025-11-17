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
        Schema::create('purchases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('price_cost', 10, 2)->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('quantity_prev')->default(0);
            $table->string('currency_code')->nullable();
            $table->json('added')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignUuid('order_id')
                ->constrained()
                ->onDelete('cascade');
            $table->uuid('product_id')->nullable()->index();
            $table->nullableUuidMorphs('model');
            $table->string('extern_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
