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
        Schema::create('menu', function (Blueprint $table) {
            $table->integer('menu_id', true);
            $table->integer('instance_id')->nullable()->index('fk_menu_instance1_idx');
            $table->string('menu_name', 100)->nullable()->index('menu_name_idx');
            $table->text('menu_link')->nullable();
            $table->integer('parent_id')->nullable()->index('fk_menu_menu1_idx');
            $table->boolean('menu_active')->nullable();
            $table->integer('menu_sequence')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
};
