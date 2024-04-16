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
        Schema::create('page', function (Blueprint $table) {
            $table->integer('page_id', true);
            $table->integer('instance_id')->nullable()->index('fk_page_instance_idx');
            $table->string('page_title_en', 300)->nullable();
            $table->string('page_title_id', 300)->nullable();
            $table->string('page_slug_en', 300)->nullable();
            $table->string('page_slug_id', 300)->nullable();
            $table->text('page_content_id')->nullable();
            $table->text('page_content_en')->nullable();
            $table->enum('page_status', ['publish', 'draft'])->nullable();
            $table->string('meta_title', 300)->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->string('meta_keyword', 300)->nullable();
            $table->string('page_thumbnail', 300)->nullable();
            $table->bigInteger('page_view')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrentOnUpdate();
            $table->integer('user_id')->nullable()->index('fk_page_user1_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page');
    }
};
