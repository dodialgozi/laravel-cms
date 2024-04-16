<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use Exception;
use App\Models\Page;
use App\Models\Category;
use App\Models\SettingMenu;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Http\Controllers\BaseController as Controller;

class PengaturanMenuController extends Controller
{
    protected $title = 'Pengaturan Menu';
    protected $view = 'backend.admin.partials.pengaturan';
    protected $urlRedirect = 'pengaturan/menu';
    protected $input = 'input';
    public $i = 0;

    protected $permissions = ['menu' => 'index,saveMenu'];
    protected $permissionName = 'pengaturan';

    public function index()
    {
        $postCategories = Category::with('children')
            ->whereNull('parent_id')
            ->where('category_active', 1)
            ->orderBy('category_name_id', 'asc')
            ->get();

        // $locationCategories = LocationCategory::with('children')
        //     ->whereNull('parent_id')
        //     ->where('category_active', 1)
        //     ->orderBy('category_name', 'asc')
        //     ->get();

        $pages = Page::orderBy('page_title_id', 'asc')->get();

        $menu = SettingMenu::with('children')
            ->where('instance_id', decodeId(getInstanceId()))
            ->whereNull('parent_id')
            ->where('menu_active', 1)
            ->orderBy('menu_sequence', 'asc')
            ->get();

        if ($menu->isEmpty()) {
            SettingMenu::firstOrCreate(
                [
                    'menu_name' => 'Beranda',
                    'menu_link' => url('/'),
                ],
                [
                    'parent_id' => null,
                    'menu_active' => 1,
                    'menu_sequence' => 1,
                    'instance_id' => decodeId(getInstanceId()),
                ]
            );
        }

        $formatPostCategories = $this->generateNestedItem($postCategories, 'kategori', 'category_id', 'category_name', 'category_slug');
        // $formatLocationCategories = $this->generateNestedItem($locationCategories, 'location_category', 'category_id', 'category_name', 'category_slug');
        $formatPages = $this->generateNestedItem($pages, 'page', 'page_id', 'page_title', 'page_slug');
        $formatMenu = $this->generateNestedItem($menu, 'menu', 'menu_id', 'menu_name', 'menu_link');

        return view($this->getView('menu'), [
            'mainURL' => $this->getUrlToRedirect(),
            'postCategories' => json_encode($formatPostCategories),
            // 'locationCategories' => json_encode($formatLocationCategories),
            'pages' => json_encode($formatPages),
            'menus' => json_encode($formatMenu),
        ]);
    }

    public function saveMenu()
    {
        try {
            $data = request()->input('tree');
            if (empty($data)) abort(404);

            SettingMenu::truncate();
            DB::beginTransaction();

            $this->insertMenu($data);

            logHistory([
                'nama_proses' => 'UPDATE MENU',
                'ket_proses' => 'TABEL menu',
                'data_proses' => json_encode($data),
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

    public function insertMenu($data, $parentId = null)
    {
        foreach ($data as $item) {

            if ($item['key'] === 'beranda') {
                $item['data']['link'] = url('/');
            }

            $menu = new SettingMenu();
            $menu->instance_id = decodeId(getInstanceId());
            $menu->menu_name = $item['title'];
            $menu->parent_id = $parentId;
            $menu->menu_link = $item['data']['link'];
            $menu->menu_active = 1;
            $menu->menu_sequence = ++$this->i;
            $menu->save();

            if (isset($item['children'])) {
                $this->insertMenu($item['children'], $menu->menu_id);
            }
        }
    }

    public function generateNestedItem($items, $link, $id, $title, $slug)
    {
        $result = [];

        foreach ($items as $item) {
            if ($link == 'kategori') {
                $linkValue = route('frontend.artikel.kategori.show', $item->{$slug});
            } elseif ($link == 'location_category') {
                $linkValue = route('frontend.lokasi.kategori.show', $item->{$slug});
            } elseif ($link == 'page') {
                $linkValue = route('frontend.page.show', $item->{$slug});
            } else {
                $linkValue = url($item->{$slug});
            }

            $result[] = [
                'key' => Str::slug($item->{$title}),
                'title' => $item->{$title},
                'link' => $linkValue,
                'children' => $item->children && $item->children->count() > 0 ? $this->generateNestedItem($item->children, $link, $id, $title, $slug) : [],
            ];
        }

        return $result;
    }
}
