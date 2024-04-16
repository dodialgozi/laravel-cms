<?php 

namespace App\Services;

use App\Models\Permission;
use App\Models\PermissionGroup;
use Exception;
use Illuminate\Support\Facades\DB;

class RoleService {
    
    /**
     * Get the permissions for a specific role.
     *
     * @param int $id The ID of the role.
     *
     * @return array The role's permissions and related data.
     */
    public static function getPermission($id) {
        // Get the default permissions
        $defaultPermission = Permission::getDefaultPermission();
    
        // Get all permission groups
        $group = PermissionGroup::orderBy('name')->get();
    
        // Create a temporary data array
        $tempData = [];
        foreach ($group as $value) {
            $tempData[$value->id] = Permission::where('group_id', $value->id)
                ->select(['id', 'name', 'group_id', 'description'])
                ->get();
        }
        $tempData['other'] = Permission::whereNull('group_id')
            ->select(['id', 'name', 'group_id', 'description'])
            ->get();
    
        // Create a permission array
        $permission = [];
        foreach ($tempData as $key => $value) {
            $tempPerm = [];
            foreach ($value as $value2) {
                $name = explode('.', $value2->name, 2);
                if (isset($defaultPermission[$name[1]])) {
                    $tempPerm[$name[1]] = $value2;
                } else {
                    $tempPerm['lainnya'][] = $value2;
                }
            }
            $permission[$key] = $tempPerm;
        }
    
        // Get the role permissions
        $rolePermission = DB::table('role_has_permissions')
            ->where('role_id', $id)
            ->get()
            ->pluck('permission_id')
            ->toArray();
        
        // Return the data
        return [
            'group' => $group,
            'defaultPermission' => $defaultPermission,
            'permission' => $permission,
            'rolePermission' => $rolePermission,
        ];
    }
  

    /**
     * Save the permissions for a specific role.
     *
     * @param array $input The array of permission IDs to be saved.
     * @param int $id The ID of the role.
     *
     * @return array The result of the save operation.
     */
    public static function savePermission($input, $id){
        try {
            DB::beginTransaction();

            // Remove existing permissions for the role
            DB::table('role_has_permissions')
                ->where('role_id', $id)
                ->delete();

            // Insert new permissions for the role
            foreach ($input as $value) {
                DB::table('role_has_permissions')->insert([
                    'role_id' => $id,
                    'permission_id' => $value
                ]);
            }

            DB::commit();

            // Return success message
            return [
                'success' => true,
                'message' => 'Data Berhasil Disimpan',
            ];
        } catch (Exception $ex) {
            DB::rollback();

            // Return error message
            return [
                'success' => false,
                'errorMessage' => errorMessage($ex),
            ];
        }
    }

}