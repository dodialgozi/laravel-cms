<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Controllers\BaseController as Controller;

class AdminMenuController extends Controller
{
    protected $title = 'Menu Admin';
    protected $view = 'backend.admin.partials.admin_menu';
    protected $urlRedirect = 'admin-menu';
    protected $input = 'input';

    protected $permission = ['manajemen' => 'index,store,update,order,destroy'];
    protected $permissionName = 'menu_m';

    public function index()
    {
        $menu = Menu::with([
            'permission' => function ($query) {
                $query->select(['id', 'name']);
            },
            'children' => function ($query) {
                $query->with([
                    'permission' => function ($query) {
                        $query->select(['id', 'name']);
                    },
                ])
                    ->orderBy('menu_order')
                    ->orderBy('menu_id');
            },
            'children.children' => function ($query) {
                $query->with([
                    'permission' => function ($query) {
                        $query->select(['id', 'name']);
                    },
                ])
                    ->orderBy('menu_order')
                    ->orderBy('menu_id');
            }
        ])
            ->whereNull('parent_id')
            ->orderBy('menu_order')
            ->orderBy('menu_id')
            ->get();

        $data = [];
        foreach ($menu as $value) {
            $data[] = [
                'id' => $value->menu_id,
                'parent_id' => $value->parent_id ?? 0,
                'title' => $value->menu_name,
                'level' => 1,
                'data' => [
                    'name' => $value->menu_name,
                    'icon' => $value->menu_icon,
                    'link' => $value->menu_link,
                    'class' => $value->menu_class,
                    'permission' => $value->permission->name ?? null,
                    'permission_id' => $value->permission_id,
                    'permit' => $value->permit,
                    'enable' => $value->enable,
                ],
            ];
            foreach ($value->children as $value2) {
                $data[] = [
                    'id' => $value2->menu_id,
                    'parent_id' => $value2->parent_id ?? 0,
                    'title' => $value2->menu_name,
                    'level' => 2,
                    'data' => [
                        'name' => $value2->menu_name,
                        'icon' => $value2->menu_icon,
                        'link' => $value2->menu_link,
                        'class' => $value2->menu_class,
                        'permission' => $value2->permission->name ?? null,
                        'permission_id' => $value2->permission_id,
                        'permit' => $value2->permit,
                        'enable' => $value2->enable,
                    ],
                ];
                foreach ($value2->children as $value3) {
                    $data[] = [
                        'id' => $value3->menu_id,
                        'parent_id' => $value3->parent_id ?? 0,
                        'title' => $value3->menu_name,
                        'level' => 3,
                        'data' => [
                            'name' => $value3->menu_name,
                            'icon' => $value3->menu_icon,
                            'link' => $value3->menu_link,
                            'class' => $value3->menu_class,
                            'permission' => $value3->permission->name ?? null,
                            'permission_id' => $value3->permission_id,
                            'permit' => $value3->permit,
                            'enable' => $value3->enable,
                        ],
                    ];
                }
            }
        }

        $dataIcon = explode("\n", file_get_contents(public_path('backend/assets/icon.txt')));
        $icons = [];
        foreach ($dataIcon as $value) {
            $icons[] = [
                'id' => $value,
                'text' => $value,
            ];
        }

        $permits = Menu::getPermit();

        return view($this->getView('index'), [
            'mainURL' => $this->getUrlToRedirect(),
            'data' => $data,
            'icons' => $icons,
            'permits' => $permits,
        ]);
    }

    public function store()
    {
        try {
            DB::beginTransaction();

            $input = [
                'permission_id' => request()->permission,
                'parent_id' => request()->parent,
                'menu_name' => request()->name,
                'menu_icon' => request()->icon,
                'menu_link' => request()->link,
                'menu_class' => request()->class,
                'menu_permit' => !empty(request()->permit) ? json_encode(request()->permit) : null,
                'menu_enable' => !empty(request()->enable) ? 1 : 0,
            ];

            $id = DB::table('menu')->insertGetId($input);

            logHistory([
                'nama_proses' => 'INSERT DATA',
                'ket_proses' => 'TABEL menu',
                'data_proses' => json_encode($input),
            ]);

            DB::commit();

            $menu = Menu::find($id);
            $permission = $menu->permission;
            $data = [
                'id' => $menu->menu_id,
                'name' => $menu->menu_name,
                'icon' => $menu->menu_icon,
                'link' => $menu->menu_link,
                'class' => $menu->menu_class,
                'permission' => $permission->name ?? null,
                'permission_id' => $permission->id ?? null,
                'permit' => $menu->permit,
                'enable' => $menu->enable,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Ditambahkan',
                'data' => $data,
            ]);
        } catch (Exception $ex) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'errorMessage' => errorMessage($ex),
            ]);
        }
    }

    public function update($id)
    {
        try {
            DB::beginTransaction();

            $oldData = DB::table('menu')
                ->where('menu_id', $id)
                ->first();
            if (empty($oldData)) abort(404);

            $input = [
                'permission_id' => request()->permission,
                'menu_name' => request()->name,
                'menu_icon' => request()->icon,
                'menu_link' => request()->link,
                'menu_class' => request()->class,
                'menu_permit' => !empty(request()->permit) ? json_encode(request()->permit) : null,
                'menu_enable' => !empty(request()->enable) ? 1 : 0,
            ];

            DB::table('menu')
                ->where('menu_id', $id)
                ->update($input);

            logHistory([
                'nama_proses' => 'UPDATE DATA',
                'ket_proses' => 'TABEL menu',
                'data_proses' => json_encode([
                    'old' => $oldData,
                    'new' => $input
                ]),
            ]);

            DB::commit();

            $menu = Menu::find($id);
            $permission = $menu->permission;
            $data = [
                'id' => $menu->menu_id,
                'name' => $menu->menu_name,
                'icon' => $menu->menu_icon,
                'link' => $menu->menu_link,
                'class' => $menu->menu_class,
                'permission' => $permission->name ?? null,
                'permission_id' => $permission->id ?? null,
                'permit' => $menu->permit,
                'enable' => $menu->enable,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Perubahan Berhasil Disimpan',
                'data' => $data,
            ]);
        } catch (Exception $ex) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'errorMessage' => errorMessage($ex),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $menu = Menu::find($id);
            $dataHapus = json_encode($menu);
            $menu->delete();

            logHistory([
                'nama_proses' => 'DELETE DATA',
                'ket_proses' => 'TABEL menu',
                'data_proses' => $dataHapus,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
            ]);
        } catch (QueryException $ex) {
            DB::rollback();
            $message = 'Terjadi kesalahan.';
            // constraint violation
            if ($ex->errorInfo[1] == '1451') {
                $message = 'Tidak bisa menghapus menu induk.';
            }
            return response()->json([
                'success' => false,
                'message' => $message,
                'errorMessage' => errorMessage($ex),
            ]);
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan.',
                'errorMessage' => errorMessage($ex),
            ]);
        }
    }

    public function order()
    {
        try {
            $orders = request()->order;
            if (empty($orders)) abort(404);

            DB::beginTransaction();

            foreach ($orders as $key => $order) {
                Menu::where((new Menu)->getKeyName(), $order['id'])->update([
                    'parent_id' => $order['parent'],
                    'menu_order' => $key + 1,
                ]);
            }

            logHistory([
                'nama_proses' => 'REORDER DATA',
                'ket_proses' => 'TABEL menu',
                'data_proses' => json_encode($orders),
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
            ]);
        } catch (QueryException $ex) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan.',
                'errorMessage' => $ex->getMessage(),
            ]);
        } catch (Exception $ex) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan.',
                'errorMessage' => errorMessage($ex),
            ]);
        }
    }
}
