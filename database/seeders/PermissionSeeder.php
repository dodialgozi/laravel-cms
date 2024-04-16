<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionJson = json_decode(file_get_contents(base_path('optional/permission.json')), true);

        foreach($permissionJson as $permission){

            $rolesGroup = PermissionGroup::create([
                'name' => $permission['group'],
            ]);

            if($permission['default']){
                foreach(['lihat', 'tambah' , 'ubah', 'hapus'] as $key){
                    $keyPermissions = $permission['key'] . "." . $key;

                    Permission::create([
                        'name' => $keyPermissions,
                        'guard_name' => 'web',
                        'group_id' => $rolesGroup->id,
                    ]);
                }
            }

            if(!empty($permission['permissions'])){
                foreach($permission['permissions'] as $key){

                    if(!is_array($key)){
                        $keyPermissions = $permission['key'] . "." . $key;
                    }else{
                        $keyPermissions = $permission['key'] . "." . $key['key'];
                    }
                    Permission::create([
                        'name' => $keyPermissions,
                        'guard_name' => 'web',
                        'group_id' => $rolesGroup->id,
                        'description' => (!is_array($key) ? '' : $key['name'])
                    ]);
                }
            }
        }
    }
}
