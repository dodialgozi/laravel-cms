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
        Schema::create('custom_post_category', function (Blueprint $table) {
            $table->integer('category_id', true);
            $table->integer('instance_id')->nullable()->index('cpost_cat_instance_idx');
            $table->string('category_name_id', 100)->nullable()->index('cpost_cat_id_idx');
            $table->string('category_name_en', 100)->nullable()->index('cpost_cat_name_en_idx');
            $table->string('category_slug_id', 100)->nullable()->index('cpost_cat_slug_id_idx');
            $table->string('category_slug_en', 100)->nullable()->index('cpost_cat_slug_en_idx');
            $table->tinyInteger('category_active')->nullable();
            $table->integer('parent_id')->nullable()->index('fk_custom_post_category_custom_post_category1_idx');
            $table->integer('post_type_id')->nullable()->index('fk_custom_post_category_custom_post_type1_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_post_category');
    }
};
