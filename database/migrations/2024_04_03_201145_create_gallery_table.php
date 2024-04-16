<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gallery', function (Blueprint $table) {
            $table->integer('gallery_id', true);
            $table->integer('instance_id')->nullable()->index('fk_gallery_instance_idx');
            $table->string('gallery_title_id', 100)->nullable();
            $table->string('gallery_title_en', 100)->nullable();
            $table->string('gallery_slug_id', 100)->nullable();
            $table->string('gallery_slug_en', 100)->nullable();
            $table->text('gallery_description_id')->nullable();
            $table->text('gallery_description_en')->nullable();
            $table->string('gallery_image', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
