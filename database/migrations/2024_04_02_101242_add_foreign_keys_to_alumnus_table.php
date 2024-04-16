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
        Schema::table('alumnus', function (Blueprint $table) {
            $table->foreign(['instance_id'], 'fk_alumnus_instance')->references(['instance_id'])->on('instance')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumnus', function (Blueprint $table) {
            $table->dropForeign('fk_alumnus_instance');
        });
    }
};
