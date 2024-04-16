<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_menu', function (Blueprint $table) {
            $table->integer("menu_id", true);
            $table->integer('parent_id')->nullable()->index('parent_id_menu_admin_idx');
            $table->bigInteger('permission_id')->unsigned()->nullable()->index('permission_id_menu_admin_idx');
            $table->string('menu_permit',200)->nullable();
            $table->string('menu_name',100)->nullable();
            $table->string('menu_icon',50)->nullable();
            $table->string('menu_link',200)->nullable();
            $table->string('menu_class',100)->nullable();
            $table->boolean('menu_enable',1)->nullable()->default(1);
            $table->integer('menu_order')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_menu');
    }
};