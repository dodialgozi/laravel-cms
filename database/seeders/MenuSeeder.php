<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuJson = json_decode(file_get_contents(base_path('optional/menu.json')));

        foreach ($menuJson as $key => $menuParent) {
            $hasChildren = count($menuParent->menu_children ?? []) > 0;

            $arrayMenuParent = (array)$menuParent;

            $menuParentModel = Menu::create([
                'menu_name' => $arrayMenuParent['menu_name'],
                'menu_icon' => $arrayMenuParent['menu_icon'],
                'menu_link' => $arrayMenuParent['menu_link'],
                'menu_class' => $arrayMenuParent['menu_class'],
                'menu_enable' => $arrayMenuParent['menu_enable'] ?? 1,
                'menu_order' => $arrayMenuParent['menu_order'] ?? $key,
                'menu_permit' =>  $arrayMenuParent['menu_permit'] ?? "",
                'permission_id' => findPermissionIdByName($arrayMenuParent['permission'] ?? ''),
            ]);

            if ($hasChildren) {
                foreach ($menuParent->menu_children as $keys => $menuChild) {

                    $hasChildren = count($menuChild->menu_children ?? []) > 0;

                    $arrayMenuChild = (array)$menuChild;

                    $menuChildModel = Menu::create([
                        'menu_name' => $arrayMenuChild['menu_name'],
                        // 'menu_icon' => $arrayMenuChild['menu_icon'],
                        'menu_link' => $arrayMenuChild['menu_link'],
                        'menu_class' => $arrayMenuChild['menu_class'],
                        'menu_enable' => $arrayMenuChild['menu_enable'] ?? 1,
                        'menu_order' => $arrayMenuChild['menu_order'] ?? $keys,
                        'menu_permit' =>  $arrayMenuChild['menu_permit'] ?? "",
                        'parent_id' => $menuParentModel->menu_id,
                        'permission_id' => findPermissionIdByName($arrayMenuChild['permission'] ?? ''),
                    ]);

                    if ($hasChildren) {

                        foreach ($menuChild->menu_children as $k => $menuGrandChild) {

                            $arrayMenuGrandChild = (array)$menuGrandChild;

                            Menu::create([
                                'menu_name' => $arrayMenuGrandChild['menu_name'],
                                // 'menu_icon' => $arrayMenuGrandChild['menu_icon'],
                                'menu_link' => $arrayMenuGrandChild['menu_link'],
                                'menu_class' => $arrayMenuGrandChild['menu_class'],
                                'menu_enable' => $arrayMenuGrandChild['menu_enable'] ?? 1,
                                'menu_order' => $arrayMenuGrandChild['menu_order'] ?? $k,
                                'menu_permit' =>  $arrayMenuGrandChild['menu_permit'] ?? "",
                                'parent_id' => $menuChildModel->menu_id,
                                'permission_id' => findPermissionIdByName($arrayMenuGrandChild['permission'] ?? ''),
                            ]);
                        }
                    }
                }
            }
        }
    }
}
