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
        Schema::create('alumnus', function (Blueprint $table) {
            $table->bigIncrements('alumnus_id', true);
            $table->integer('instance_id')->nullable()->index('fk_alumnus_instance_idx');
            $table->string('alumnus_nim', 100)->nullable()->index('alumnus_nim_idx');
            $table->string('alumnus_nirm', 100)->nullable()->index('alumnus_nirm_idx');
            $table->string('alumnus_nirl', 100)->nullable()->index('alumnus_nirl_idx');
            $table->string('alumnus_email', 100)->nullable()->index('alumnus_email_idx');
            $table->string('alumnus_name', 100)->nullable()->index('alumnus_name_idx');
            $table->string('alumnus_profession', 100)->nullable()->index('alumnus_profession_idx');
            $table->string('alumnus_statement_id', 300)->nullable();
            $table->string('alumnus_statement_en', 300)->nullable();
            $table->string('alumnus_image', 300)->nullable();
            $table->enum('alumnus_status', ['publish', 'draft'])->nullable()->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
