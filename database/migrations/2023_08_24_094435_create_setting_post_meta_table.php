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
        Schema::create('setting_post_meta', function (Blueprint $table) {
            $table->integer('setting_meta_id', true);
            $table->string('setting_meta_code', 255)->nullable();
            $table->enum('setting_meta_type', ['field', 'boolean', 'select'])->nullable();
            $table->string('setting_meta_value', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_post_meta');
    }
};
