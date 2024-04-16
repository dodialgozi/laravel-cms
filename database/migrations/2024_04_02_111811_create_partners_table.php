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
        Schema::create('partner', function (Blueprint $table) {
            $table->integer('partner_id', true);
            $table->integer('instance_id')->nullable()->index('fk_partner_instance_idx');
            $table->string('partner_name', 100)->nullable();
            $table->string('partner_logo', 300)->nullable();
            $table->string('partner_url', 300)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
