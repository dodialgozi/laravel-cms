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
        Schema::create('instance', function (Blueprint $table) {
            $table->integer('instance_id', true);
            $table->string('instance_name', 100)->index('instance_name_idx');
            $table->string('instance_slug', 100)->index('instance_slug_idx');
            $table->boolean('instance_active')->default(1);
            $table->string('instance_thumbnail', 300)->nullable();
            $table->string('instance_domain', 100)->index('instance_domain_idx');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instances');
    }
};
