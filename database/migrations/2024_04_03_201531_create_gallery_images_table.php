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
        Schema::create('gallery_image', function (Blueprint $table) {
            $table->bigInteger('gallery_image_id', true);
            $table->integer('gallery_id')->nullable()->index('fk_gallery_image_gallery_idx');
            $table->string('image', 100)->nullable();
            $table->string('size', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_image');
    }
};
