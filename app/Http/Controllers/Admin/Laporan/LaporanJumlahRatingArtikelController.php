<?php

namespace App\Http\Controllers\Admin\Laporan;

use Exception;
use App\Models\Cabang;
use App\Models\Pegawai;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\Http\Controllers\BasicController as Controller;
use App\Models\Post;
use App\Models\Rating;
use App\Models\User;

class LaporanJumlahRatingArtikelController extends Controller
{
    protected $title = 'Laporan Rating Artikel Tertinggi';
    protected $view = 'backend.admin.partials.laporan.jumlah-rating-artikel';
    protected $urlRedirect = 'laporan/jumlah-rating-artikel';

    protected $permissions = ['jumlah-rating-artikel' => 'index,print'];
    protected $permissionName = 'laporan';

    public function index()
    {
        return view($this->getView('index'));
    }

    public function print()
    {

        try {
            $input = request()->input($this->getInput());

            $data = Rating::with(['post' => function ($query) use ($input) {
                        $query->whereRentangTanggal($input['mulai'], $input['sampai']);
                    }, 'post.user' => function ($query) use ($input) {
                        $query->when(!empty($input['user']), function ($query) use ($input) {
                            $query->whereIn('user.user_id', $input['user']);
                        });
                    }])     
                    ->orderByHighest()               
                    ->limit(10)
                    ->get()
                    ->reject(fn($item) => empty($item->post->post_title))
                    ->values();

            return Pdf::loadView($this->getView('print'), [
                'input' => $input,
                'data' => $data,
            ])->stream();
        } catch (Exception $ex) {
            abort(500, errorMessage($ex));
        }
    }
}
