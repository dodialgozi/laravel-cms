<?php

namespace App\Http\Controllers\Admin\Laporan;

use Exception;
use App\Models\Cabang;
use App\Models\Pegawai;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\Http\Controllers\BasicController as Controller;
use App\Models\Post;
use App\Models\User;

class LaporanJumlahPostViewJurnalisController extends Controller
{
    protected $title = 'Laporan Jumlah Post View Jurnalis';
    protected $view = 'backend.admin.partials.laporan.jumlah-post-view-jurnalis';
    protected $urlRedirect = 'laporan/jumlah-post-view-jurnalis';

    protected $permissions = ['jumlah-post-view-jurnalis' => 'index,print'];
    protected $permissionName = 'laporan';

    public function index()
    {
        return view($this->getView('index'));
    }

    public function print()
    {

        try {
            $input = request()->input($this->getInput());

            $data = User::with(['allPublishedPost' => function ($query) use ($input) {
                        $query
                            ->whereRentangTanggal($input['mulai'], $input['sampai']);
                    }])
                    ->when(!empty($input['user']), function ($query) use ($input) {
                        $query->whereIn('user.user_id', $input['user']);
                    })
                    ->get();

            return Pdf::loadView($this->getView('print'), [
                'input' => $input,
                'data' => $data,
            ])->stream();
        } catch (Exception $ex) {
            abort(500, errorMessage($ex));
        }
    }
}
