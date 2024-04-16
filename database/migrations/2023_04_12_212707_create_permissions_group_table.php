<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permissions_group', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 250)->nullable();
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permissions_group');
    }
};