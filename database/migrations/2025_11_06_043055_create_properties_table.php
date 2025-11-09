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
        Schema::create('properties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->nullable()->index();
            $table->string('value')->nullable();
            $table->string('suffix')->nullable();
            $table->string('prefix')->nullable();
            $table->string('color')->nullable();
            $table->integer('weight')->default(0);
            $table->string('help')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->string('attribute_slug')->index();
            $table->foreignUuid('attribute_id')
                ->constrained()->onDelete('CASCADE');
        });

        Schema::create('propertyables', function (Blueprint $table) {
            $table->foreignUuid('property_id')
                ->constrained()
                ->onDelete('cascade');
            $table->uuidMorphs('model');
            $table->string('comment')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('weight')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propertyables');

        Schema::dropIfExists('properties');
    }
};
