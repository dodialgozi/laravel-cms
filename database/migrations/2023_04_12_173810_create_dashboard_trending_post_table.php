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
        Schema::create('dashboard_trending_post', function (Blueprint $table) {
            $table->integer('dashboard_id', true);
            $table->bigInteger('post_id')->nullable()->index('post_id_dashboard_trending_idx');
            $table->integer('summary')->nullable();
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
        Schema::dropIfExists('dashboard_trending_post');
    }
};
