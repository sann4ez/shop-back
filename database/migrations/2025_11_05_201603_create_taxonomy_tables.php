<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createTermsTable();

        $this->createTermablesTable();
    }

    /**
     * Taxonomy terms table
     */
    public function createTermsTable()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('slug')->nullable()->index();
            $table->string('name')->nullable();
            $table->text('body')->nullable();
            $table->string('icon')->nullable();
            $table->json('added')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->integer('weight')->default(0);
            $table->string('vocabulary')->index();
            // Used Nested https://github.com/lazychaser/laravel-nestedset
            $table->unsignedInteger('_lft')->nullable();
            $table->unsignedInteger('_rgt')->nullable();
            $table->uuid('parent_id')->nullable();
            $table->uuid('domain_id')->nullable()->index();

            $table->json('index')->nullable();
        });
    }


    /**
     * Таблица сущностей "привязанных" к термам
     */
    public function createTermablesTable()
    {
        Schema::create('termables', function (Blueprint $table) {
            $table->foreignUuid('term_id')
                ->constrained()
                ->onDelete('CASCADE');
            $table->uuidMorphs('termable');
            $table->unsignedInteger('weight')->default(0);
            $table->string('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('termables');
        Schema::dropIfExists('terms');
    }
};
