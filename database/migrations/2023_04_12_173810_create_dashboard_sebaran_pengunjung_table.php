<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_sebaran_pengunjung', function (Blueprint $table) {
            $table->integer('dashboard_id', true);
            $table->string('latitude', 50)->nullable();
            $table->string('longitude', 50)->nullable();
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            $table->integer('summary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dashboard_sebaran_pengunjung');
    }
};
