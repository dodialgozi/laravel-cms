<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use App\Http\Controllers\BasicController as Controller;
use App\Models\SettingPostMeta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengaturanPostMetaController extends Controller
{
    protected $title = 'Pengaturan Post Meta';
    protected $view = 'backend.admin.partials.pengaturan';
    protected $urlRedirect = 'pengaturan/post-meta';

    protected $permissions = ['post-meta' => 'index,save'];
    protected $permissionName = 'pengaturan';

    public function index()
    {
        $data = SettingPostMeta::all();
        $meta_type = [
            'field' => 'Text',
            'boolean' => 'Boolean',
        ];

        return view($this->getView('post-meta'), [
            'data' => $data,
            'meta_type' => $meta_type,
        ]);
    }

    public function save()
    {
        try {
            SettingPostMeta::truncate();
            DB::beginTransaction();

            $input = request()->input($this->getInput());

            if (!empty($input)) {
                foreach ($input as $item) {
                    if ($item['setting_meta_type'] == 'boolean') {
                        $item['setting_meta_value'] = isset($item['setting_meta_value']) && $item['setting_meta_value'] == 'on' ? 1 : 0;
                    }

                    SettingPostMeta::create([
                        'setting_meta_code' => $item['setting_meta_code'],
                        'setting_meta_type' => $item['setting_meta_type'],
                        'setting_meta_value' => $item['setting_meta_value'],
                    ]);
                }
            }

            DB::commit();

            return redirect($this->getUrlToRedirect())->with([
                'success' => true,
                'message' => 'Pengaturan post meta berhasil disimpan.',
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            dd($ex);
            return redirect($this->getUrlToRedirect())->with([
                'success' => false,
                'errorMessage' => errorMessage($ex),
            ]);
        }
    }
}
