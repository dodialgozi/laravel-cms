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
        Schema::create('dashboard_pengunjung', function (Blueprint $table) {
            $table->integer('dashboard_id', true);
            $table->integer('instance_id')->nullable()->index('fk_dashboard_pengunjung_instance_id');
            $table->integer('day')->nullable();
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
        Schema::dropIfExists('dashboard_pengunjung');
    }
};
