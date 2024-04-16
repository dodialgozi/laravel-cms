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
        Schema::create('post_meta', function (Blueprint $table) {
            $table->integer('post_meta_id', true);
            $table->bigInteger('post_id')->nullable()->index('fk_post_meta_post1_idx');
            $table->string('post_meta_code', 255)->nullable();
            $table->enum('post_meta_type', ['field', 'boolean', 'select'])->nullable();
            $table->string('post_meta_value', 200)->nullable();

            $table->foreign('post_id', 'fk_post_meta_post1')->references('post_id')->on('post')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_meta');
    }
};
