<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->integer('category_id', true);
            $table->integer('instance_id')->nullable()->index('fk_category_instance1_idx');
            $table->string('category_name_en', 100)->nullable()->index('category_name_en');
            $table->string('category_name_id', 100)->nullable()->index('category_name_id');
            $table->string('category_slug_en', 100)->nullable()->index('category_slug_en');
            $table->string('category_slug_id', 100)->nullable()->index('category_slug_id');
            $table->text('category_thumbnail')->nullable();
            $table->integer('parent_id')->nullable()->index('fk_category_category1_idx');
            $table->boolean('category_active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category');
    }
};
