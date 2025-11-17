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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number')->nullable()->index();
            $table->string('gateway');
            $table->string('method');
            $table->string('purpose')->nullable();
            $table->string('operation')->nullable();
            $table->string('status')->index();
            $table->string('source')->nullable();
            $table->string('category')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->boolean('is_guarantee')->default(false);
            $table->text('payment_url')->nullable();
            $table->text('tax_url')->nullable();
            $table->string('fiscal_code')->nullable()->index();
            $table->timestamp('fiscal_at')->nullable();
            $table->text('comment')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('recurring_data')->nullable();
            $table->json('added')->nullable();
            $table->timestamp('payment_url_expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->string('extern_id')->index()->nullable();
            $table->uuid('parent_id')->index()->nullable();
            $table->nullableUuidMorphs('model');
            $table->uuid('user_id')->nullable()->index();
            $table->string('locale_code')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
