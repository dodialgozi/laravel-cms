<?php

namespace App\Http\Controllers\Admin\Laporan;

use Exception;
use App\Models\Cabang;
use App\Models\Pegawai;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\Http\Controllers\BasicController as Controller;
use App\Models\Post;
use App\Models\Quiz;
use App\Models\QuizPoint;
use App\Models\User;

class LaporanPemenangQuizController extends Controller
{
    protected $title = 'Laporan Pemenang Quiz';
    protected $view = 'backend.admin.partials.laporan.pemenang-quiz';
    protected $urlRedirect = 'laporan/pemenang-quiz';

    protected $permissions = ['pemenang-quiz' => 'index,print'];
    protected $permissionName = 'laporan';

    public function index()
    {
        return view($this->getView('index'));
    }

    public function print()
    {

        try {
            $input = request()->input($this->getInput());

            $quiz = Quiz::find($input['quiz']);
            $data = QuizPoint::where('quiz_id', $input['quiz'])
                    ->join('quiz_participant', 'quiz_participant.participant_id', '=', 'quiz_point.participant_id')
                    ->orderByHighest()               
                    ->limit(10)
                    ->get();

            return Pdf::loadView($this->getView('print'), [
                'input' => $input,
                'data' => $data,
                'quiz' => $quiz
            ])->stream();
        } catch (Exception $ex) {
            abort(500, errorMessage($ex));
        }
    }
}
