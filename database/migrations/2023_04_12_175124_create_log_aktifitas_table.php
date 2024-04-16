<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('log_aktifitas', function (Blueprint $table) {
            $table->string('tgl_aktifitas',50)->primary();
            $table->string('id_user',200)->nullable();
            $table->string('level',50)->nullable();
            $table->string('nama_proses',200)->nullable();
            $table->text('ket_proses')->nullable();
            $table->text('data_proses')->nullable();
            $table->string('platform',30)->nullable();
            $table->string('devices',30)->nullable();
            $table->string('ip',30)->nullable();
            $table->string('browser',30)->nullable();
            $table->tinyInteger('baca')->nullable()->default(0);
            $table->text('link')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_aktifitas');
    }
};