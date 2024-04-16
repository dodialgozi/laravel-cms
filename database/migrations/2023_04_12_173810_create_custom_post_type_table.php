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
        Schema::create('custom_post_type', function (Blueprint $table) {
            $table->integer('post_type_id', true);
            $table->integer('instance_id')->nullable()->index('custom_post_type_instance_idx');
            $table->string('post_type_name', 100)->nullable();
            $table->string('post_type_code', 100)->nullable();
            $table->text('post_type_field')->nullable();
            $table->boolean('post_type_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_post_type');
    }
};
