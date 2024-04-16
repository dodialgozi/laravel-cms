<?php

namespace App\Http\Controllers\Admin\Laporan;

use Exception;
use App\Models\Cabang;
use App\Models\Pegawai;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\Http\Controllers\BasicController as Controller;
use App\Models\Post;
use App\Models\User;

class LaporanJumlahPostingJurnalisController extends Controller
{
    protected $title = 'Laporan Jumlah Posting Jurnalis';
    protected $view = 'backend.admin.partials.laporan.jumlah-posting-jurnalis';
    protected $urlRedirect = 'laporan/jumlah-posting-jurnalis';

    protected $permissions = ['jumlah-posting-jurnalis' => 'index,print'];
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
