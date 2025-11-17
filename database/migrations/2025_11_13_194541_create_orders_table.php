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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('source')->nullable();
            $table->string('type');
            $table->string('number')->nullable()->index();
            $table->string('ttn')->nullable()->index();
            $table->string('status')->nullable()->index();
            $table->string('perform')->nullable()->index();
            $table->decimal('sum', 10)->default(0);
            $table->decimal('sum_cost', 10)->default(0);
            $table->decimal('profit', 10)->default(0);
            $table->string('payment_status')->nullable()->index();
            $table->decimal('discount_sum')->default(0);
            $table->decimal('delivery_sum')->default(0);
            $table->decimal('delivery_discount_sum')->default(0);
            $table->text('client_comment')->nullable();
            $table->text('manager_comment')->nullable();
            $table->timestamp('ordered_at')->nullable()->index();
            $table->timestamp('performed_at')->nullable()->index();
            $table->json('added')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->string('currency_code')->nullable();
            $table->string('country_code')->nullable();
            $table->string('locale_code')->index()->nullable();

            $table->uuid('manager_id')->nullable()->index();
            $table->uuid('user_id')->nullable()->index();
            $table->string('extern_id')->nullable()->index();
            $table->json('extern_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
