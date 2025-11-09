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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('feed_id')->nullable()->index();
            $table->string('type');
            $table->string('slug')->nullable()->index();
            $table->string('name')->nullable();
            $table->longText('body')->nullable();
            $table->string('status')->index();

            $table->json('added')->nullable();
            $table->json('locales')->nullable();
            $table->json('fields')->nullable();
            $table->decimal('rating')->default(0);
            $table->integer('comments_count')->default(0);
            $table->unsignedBigInteger('purchases_count')->default(0);
            $table->timestamp('income_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->uuid('category_id')->nullable()->index();
            $table->uuid('productparity_id')->nullable()->index();
            $table->uuid('productmodel_id')->nullable()->index();
            $table->uuid('brand_id')->nullable()->index();
            $table->uuid('domain_id')->nullable()->index();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
