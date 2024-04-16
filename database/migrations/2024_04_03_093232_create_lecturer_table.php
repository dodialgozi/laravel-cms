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
        Schema::create('lecturer', function (Blueprint $table) {
            $table->integer('lecturer_id', true);
            $table->integer('instance_id')->nullable()->index('fk_lecturer_instance_idx');
            $table->string('lecturer_name', 100)->nullable();
            $table->string('lecturer_email', 100)->nullable();
            $table->string('lecturer_nidn', 100)->nullable();
            $table->string('lecturer_photo', 300)->nullable();
            $table->text('lecturer_bio_id')->nullable();
            $table->text('lecturer_bio_en')->nullable();
            $table->boolean('lecturer_active')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
