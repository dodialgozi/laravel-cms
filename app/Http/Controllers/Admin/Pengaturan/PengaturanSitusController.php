<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use App\Http\Traits\Upload;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController as Controller;

class PengaturanSitusController extends Controller
{
    protected $title = 'Pengaturan Situs';
    protected $modelClass = SiteSetting::class;
    protected $view = 'backend.admin.partials.pengaturan';
    protected $urlRedirect = 'pengaturan/situs';
    protected $permissions = ['situs' => 'index,save'];
    protected $permissionName = 'pengaturan';

    use Upload;

    public function index()
    {
        $data = SiteSetting::where('instance_id', decodeId(getInstanceId()))
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->key => $item->value];
            })->toArray();

        return view($this->getView('situs'), [
            'data' => $data,
        ]);
    }

    public function save(Request $request)
    {
        try {
            DB::beginTransaction();

            $input = request()->input($this->getInput());

            foreach ($input as $key => $value) {
                SiteSetting::updateOrCreate([
                    'key' => $key,
                    'instance_id' => decodeId(getInstanceId()),
                ], [
                    'value' => $value,
                ]);
            }

            DB::commit();

            return redirect($this->getUrlToRedirect())->with([
                'success' => true,
                'message' => 'Pengaturan situs berhasil disimpan.',
            ]);
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect($this->getUrlToRedirect())->with([
                'success' => false,
                'errorMessage' => errorMessage($ex),
            ]);
        }
    }
}
