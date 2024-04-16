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
        Schema::create('user_instance', function (Blueprint $table) {
            $table->integer('user_instance_id', true);
            $table->integer('instance_id')->nullable()->index('fk_user_instance_instance1_idx');
            $table->integer('user_id')->nullable()->index('fk_user_instance_user1_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_instances');
    }
};
