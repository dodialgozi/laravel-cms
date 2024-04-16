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
        Schema::create('tag', function (Blueprint $table) {
            $table->integer('tag_id', true);
            $table->integer('instance_id')->nullable()->index('fk_tag_instance_idx');
            $table->string('tag_name_id', 100)->nullable()->index('tag_name_id_idx');
            $table->string('tag_name_en', 100)->nullable()->index('tag_name_en_idx');
            $table->string('tag_slug_id', 100)->nullable()->index('tag_slug_id_idx');
            $table->string('tag_slug_en', 100)->nullable()->index('tag_slug_en_idx');
            $table->boolean('tag_popular')->nullable()->default(0);
            $table->integer('tag_count')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag');
    }
};
