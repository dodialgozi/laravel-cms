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
        Schema::create('custom_post_categories', function (Blueprint $table) {
            $table->bigInteger('cat_id', true);
            $table->bigInteger('post_id')->nullable()->index('fk_custom_post_categories_custom_post1_idx');
            $table->integer('category_id')->nullable()->index('fk_custom_post_categories_custom_post_category1_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_post_categories');
    }
};
