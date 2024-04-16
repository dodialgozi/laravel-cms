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
        Schema::create('post', function (Blueprint $table) {
            $table->bigInteger('post_id', true);
            $table->integer('instance_id')->nullable()->index('fk_post_instance_idx');
            $table->text('post_title_id')->nullable()->fulltext('post_title_id_idx');
            $table->text('post_title_en')->nullable()->fulltext('post_title_en_idx');
            $table->text('post_slug_id')->nullable()->fulltext('post_slug_id_idx');
            $table->text('post_slug_en')->nullable()->fulltext('post_slug_en_idx');
            $table->longText('post_content_id')->nullable();
            $table->longText('post_content_en')->nullable();
            $table->dateTime('post_date')->nullable()->index('post_date_idx');
            $table->string('meta_title', 300)->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->string('meta_keyword', 300)->nullable();
            $table->enum('post_status', ['draft', 'submit', 'publish', 'schedule'])->nullable();
            $table->string('post_type', 10)->nullable();
            $table->bigInteger('post_view')->nullable();
            $table->integer('user_id')->nullable()->index('fk_post_user1_idx');
            $table->string('post_video_url', 200)->nullable();
            $table->text('post_excerpt_id')->nullable()->fulltext('post_excerpt_id_idx');
            $table->text('post_excerpt_en')->nullable()->fulltext('post_excerpt_en_idx');
            $table->text('first_image')->nullable();
            $table->text('thumbnail')->nullable();
            $table->text('medium_thumbnail')->nullable();
            $table->boolean('post_trending_topic')->nullable();
            $table->boolean('post_hottopic')->nullable();
            $table->boolean('post_slider')->nullable();
            $table->boolean('post_malayhomeland')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post');
    }
};
