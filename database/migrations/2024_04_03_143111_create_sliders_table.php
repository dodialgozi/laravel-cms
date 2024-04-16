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
        Schema::create('slider', function (Blueprint $table) {
            $table->integer('slider_id', true);
            $table->integer('instance_id')->nullable()->index('fk_slider_instance_idx');
            $table->string('slider_title', 100)->nullable();
            $table->string('slider_description', 300)->nullable();
            $table->string('slider_image', 300)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
