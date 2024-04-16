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
        Schema::create('user', function (Blueprint $table) {
            $table->integer('user_id', true);
            $table->string('user_email', 100)->nullable();
            $table->string('user_password', 100)->nullable();
            $table->string('user_name', 100)->nullable();
            $table->string('user_photo', 300)->nullable();
            $table->string('user_nick', 50)->nullable();
            $table->enum('user_level', ['administrator', 'redaksi', 'jurnalis', 'admin_location'])->nullable()->default('jurnalis');
            $table->boolean('user_active')->nullable();
            $table->boolean('user_publish')->nullable();
            $table->text('user_bio')->nullable();
            $table->string('user_remember_me', 255)->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
};
