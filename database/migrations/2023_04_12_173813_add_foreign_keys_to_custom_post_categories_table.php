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
        Schema::table('custom_post_categories', function (Blueprint $table) {
            $table->foreign(['post_id'], 'fk_custom_post_categories_custom_post1')->references(['post_id'])->on('custom_post')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['category_id'], 'fk_custom_post_categories_custom_post_category1')->references(['category_id'])->on('custom_post_category')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_post_categories', function (Blueprint $table) {
            $table->dropForeign('fk_custom_post_categories_custom_post1');
            $table->dropForeign('fk_custom_post_categories_custom_post_category1');
        });
    }
};
