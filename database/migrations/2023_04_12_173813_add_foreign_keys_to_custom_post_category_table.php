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
        Schema::table('custom_post_category', function (Blueprint $table) {
            $table->foreign(['parent_id'], 'fk_custom_post_category_custom_post_category1')->references(['category_id'])->on('custom_post_category')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['post_type_id'], 'fk_custom_post_category_custom_post_type1')->references(['post_type_id'])->on('custom_post_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_post_category', function (Blueprint $table) {
            $table->dropForeign('fk_custom_post_category_custom_post_category1');
            $table->dropForeign('fk_custom_post_category_custom_post_type1');
        });
    }
};
