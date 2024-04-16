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
        Schema::create('custom_post', function (Blueprint $table) {
            $table->bigInteger('post_id', true);
            $table->integer('instance_id')->nullable()->index('fk_custom_post_instance1_idx');
            $table->integer('post_type_id')->nullable()->index('fk_custom_post_custom_post_type1_idx');
            // $table->text('post_title')->nullable()->fulltext('cpost_title_idx');
            // $table->text('post_slug')->nullable()->fulltext('cpost_slug_idx');
            $table->string('post_title_id', 300)->nullable()->index('cpost_title_id_idx');
            $table->string('post_title_en', 300)->nullable()->index('cpost_title_en_idx');
            $table->string('post_slug_id', 300)->nullable()->index('cpost_slug_id_idx');
            $table->string('post_slug_en', 300)->nullable()->index('cpost_slug_en_idx');
            // $table->longText('post_content')->nullable();
            $table->text('post_content_id')->nullable();
            $table->text('post_content_en')->nullable();
            $table->dateTime('post_date')->nullable()->index('cpost_daye_idx');
            $table->string('meta_title', 300)->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->string('meta_keyword', 300)->nullable();
            $table->enum('post_status', ['draft', 'submit', 'publish', 'schedule'])->nullable();
            $table->bigInteger('post_view')->nullable();
            $table->integer('user_id')->nullable()->index('fk_custom_post_user1_idx');
            $table->string('post_excerpt_id', 300)->nullable()->index('cpost_excerpt_id_idx');
            $table->string('post_excerpt_en', 300)->nullable()->index('cpost_excerpt_en_idx');
            $table->text('first_image')->nullable();
            $table->text('thumbnail')->nullable();
            $table->text('medium_thumbnail')->nullable();
            $table->json('post_data')->nullable();
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
        Schema::dropIfExists('custom_post');
    }
};
