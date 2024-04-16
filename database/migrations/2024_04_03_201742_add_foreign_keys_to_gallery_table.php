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
        Schema::table('gallery', function (Blueprint $table) {
            $table->foreign('instance_id', 'fk_gallery_instance_idx')->references('instance_id')->on('instance')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('gallery_image', function (Blueprint $table) {
            $table->foreign('gallery_id', 'fk_gallery_image_gallery_idx')->references('gallery_id')->on('gallery')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery', function (Blueprint $table) {
            $table->dropForeign('fk_gallery_instance_idx');
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropForeign('fk_gallery_image_gallery_idx');
        });
    }
};
