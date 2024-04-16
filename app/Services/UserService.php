<?php 

namespace App\Services;

use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\DB;

class UserService {

    public static function getAssignedRole($modelClass, $id){
        $role = Role::from('roles AS r')
            ->join('model_has_roles AS mhr', function ($join) use($modelClass) {
                $join->on('mhr.role_id', 'r.id')
                    ->where('model_type', $modelClass);
            })
            ->where('mhr.model_id', $id)
            ->first();

        return $role;
    }
    public static function assignRole($role, $modelClass, $id){
        if (!empty($role)) {
            $cek = DB::table('model_has_roles')
                ->where('model_type', $modelClass)
                ->where('model_id', $id)
                ->count();
            if (empty($cek)) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $role,
                    'model_type' => $modelClass,
                    'model_id' => $id,
                ]);
            } else {
                DB::table('model_has_roles')
                    ->where('model_type', $modelClass)
                    ->where('model_id', $id)
                    ->update(['role_id' => $role]);
            }
        }
    }

    public static function reAssignRole($role, $modelClass, $id){
        if (!empty($role)) {
            $cek = DB::table('model_has_roles')
                ->where('model_type', $modelClass)
                ->where('model_id', $id)
                ->count();
            if (empty($cek)) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $role,
                    'model_type' => $modelClass,
                    'model_id' => $id,
                ]);
            } else {
                DB::table('model_has_roles')
                    ->where('model_type', $modelClass)
                    ->where('model_id', $id)
                    ->update(['role_id' => $role]);
            }
        } else {
            DB::table('model_has_roles')
                ->where('model_type', $modelClass)
                ->where('model_id', $id)
                ->delete();
        }
    }

    public static function removeRole($modelClass, $id){
        DB::table('model_has_roles')
            ->where('model_type', $modelClass)
            ->where('model_id', $id)
            ->delete();
    }

}