<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->foreign(['group_id'], 'fk_permission_permission_group')->references(['id'])->on('permissions_group')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('admin_menu', function (Blueprint $table) {
            $table->foreign(['permission_id'], 'permission_id_menu_admin')->references(['id'])->on('permissions')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['parent_id'], 'parent_id_menu_admin')->references(['menu_id'])->on('admin_menu')->onUpdate('NO ACTION')->onDelete('NO ACTION');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign('fk_permission_permission_group');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign('permission_id_menu_admin');
            $table->dropForeign('parent_id_menu_admin');
        });
    }
}
