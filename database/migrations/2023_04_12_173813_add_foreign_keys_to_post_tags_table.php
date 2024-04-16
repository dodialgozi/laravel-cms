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
        Schema::table('post_tags', function (Blueprint $table) {
            $table->foreign(['post_id'], 'fk_post_tags_post1')->references(['post_id'])->on('post')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['tag_id'], 'fk_post_tags_tag1')->references(['tag_id'])->on('tag')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_tags', function (Blueprint $table) {
            $table->dropForeign('fk_post_tags_post1');
            $table->dropForeign('fk_post_tags_tag1');
        });
    }
};
