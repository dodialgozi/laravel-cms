<?php

namespace App\Http\Controllers\Admin\Laporan;

use Exception;
use App\Models\Cabang;
use App\Models\Pegawai;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\Http\Controllers\BasicController as Controller;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LaporanPollingTertinggiController extends Controller
{
    protected $title = 'Laporan Polling Tertinggi';
    protected $view = 'backend.admin.partials.laporan.jumlah-polling';
    protected $urlRedirect = 'laporan/jumlah-polling';

    protected $permissions = ['jumlah-polling' => 'index,print'];
    protected $permissionName = 'laporan';

    public function index()
    {
        return view($this->getView('index'));
    }

    public function print()
    {

        try {
            $input = request()->input($this->getInput());

            
            $poll = Poll::find($input['polling']);
            $data = PollOption::where('poll_id', $input['polling'])
                    ->orderByHighest()               
                    ->limit(10)
                    ->get();

            return Pdf::loadView($this->getView('print'), [
                'input' => $input,
                'data' => $data,
                'poll' => $poll,
            ])->stream();
        } catch (Exception $ex) {
            abort(500, errorMessage($ex));
        }
    }
}
