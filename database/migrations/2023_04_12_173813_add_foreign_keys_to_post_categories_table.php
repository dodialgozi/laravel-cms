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
        Schema::table('post_categories', function (Blueprint $table) {
            $table->foreign(['category_id'], 'fk_post_categories_category1')->references(['category_id'])->on('category')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['post_id'], 'fk_post_categories_post1')->references(['post_id'])->on('post')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_categories', function (Blueprint $table) {
            $table->dropForeign('fk_post_categories_category1');
            $table->dropForeign('fk_post_categories_post1');
        });
    }
};
