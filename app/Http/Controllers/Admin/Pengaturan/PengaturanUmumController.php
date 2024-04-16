<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use Exception;
use App\Models\Pengaturan;
use App\Http\Traits\Upload;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BasicController as Controller;

class PengaturanUmumController extends Controller
{
    protected $title = 'Pengaturan Umum';
    protected $view = 'backend.admin.partials.pengaturan';
    protected $urlRedirect = 'pengaturan/umum';

    protected $permissions = ['umum' => 'index,save'];
    protected $permissionName = 'pengaturan';

    use Upload;

    public function index()
    {
        $data = Pengaturan::get()
            ->mapWithKeys(function ($item) {
                return [$item->key => $item->value];
            })->toArray();

        return view($this->getView('umum'), [
            'data' => $data,
        ]);
    }

    public function save()
    {
        try {
            DB::beginTransaction();

            $input = request()->input($this->getInput());

            if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'logo', 'sistem_logo'))) {
                $input['sistem_logo'] = $fileUpload;
            }
            if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'logo_appbar', 'sistem_logo_appbar'))) {
                $input['sistem_logo_appbar'] = $fileUpload;
            }
            if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'icon', 'sistem_icon'))) {
                $input['sistem_icon'] = $fileUpload;
            }

            foreach ($input as $key => $value) {
                Pengaturan::updateOrCreate([
                    'key' => $key,
                ], [
                    'value' => $value,
                ]);
            }

            DB::commit();

            return redirect($this->getUrlToRedirect())->with([
                'success' => true,
                'message' => 'Pengaturan umum berhasil disimpan.',
            ]);
        } catch (Exception $ex) {
            DB::rollback();
            return redirect($this->getUrlToRedirect())->with([
                'success' => false,
                'errorMessage' => errorMessage($ex),
            ]);
        }
    }
}
