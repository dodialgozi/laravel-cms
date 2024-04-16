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
        Schema::create('dashboard_other', function (Blueprint $table) {
            $table->integer('dashboard_id', true);
            $table->string('label', 200)->nullable()->index('label_dashboard');
            $table->integer('summary')->nullable();
            $table->enum('type', ['country', 'platform', 'source'])->nullable();
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dashboard_other');
    }
};
