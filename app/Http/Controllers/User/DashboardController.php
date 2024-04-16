<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Http\Traits\Upload;
use App\Http\Controllers\BasicController as Controller;
use App\Models\DashboardOther;
use App\Models\DashboardPengunjung;
use App\Models\DashboardSebaranPengunjung;
use App\Models\DashboardTrendingPost;
use App\Models\Page;
use App\Models\Location;
use App\Models\Post;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $view = 'backend.admin.partials.profile';

    use Upload;

    public function index()
    {
        $publishedPost = Post::where('instance_id', decodeId(getInstanceId()))
            ->where('post_status', 'publish')
            ->count();
        $draftPost = Post::where('instance_id', decodeId(getInstanceId()))
            ->where('post_status', 'draft')
            ->count();
        $publishedPage = Page::where('instance_id', decodeId(getInstanceId()))
            ->where('page_status', 'publish')
            ->count();
        $draftPage = Page::where('instance_id', decodeId(getInstanceId()))
            ->where('page_status', 'draft')
            ->count();

        $current_month = (int) date('m');
        $current_year = (int) date('Y');

        $visitorToday = DashboardPengunjung::where('month', $current_month)
            ->where('year', $current_year)
            // ->where('day', date('d'))
            ->count();

        $visitorThisMonth = DashboardPengunjung::where('month', $current_month)
            ->where('year', $current_year)
            ->count();

        $visitorThisYear = DashboardPengunjung::where('year', $current_year)
            ->count();

        $visitorTotal = DashboardPengunjung::count();

        $recent_post = Post::where('post_status', 'publish')
            ->where('instance_id', decodeId(getInstanceId()))
            ->orderBy('post_id', 'desc')
            ->limit(5)
            ->get();
        // dd($recent_post);

        $popular_post = DashboardTrendingPost::with('post')
            ->where('month', $current_month)
            ->where('year', $current_year)
            ->orderBy('summary', 'desc')
            ->limit(5)
            ->get();


        return view($this->getView('dashboard'), [
            'publishedPost' => $publishedPost,
            'draftPost' => $draftPost,
            'publishedPage' => $publishedPage,
            'draftPage' => $draftPage,
            'visitorToday' => $visitorToday,
            'visitorThisMonth' => $visitorThisMonth,
            'visitorThisYear' => $visitorThisYear,
            'visitorTotal' => $visitorTotal,
            'recent_post' => $recent_post,
            'popular_post' => $popular_post,
        ]);
    }

    public function profile()
    {
        $user = auth()->user();

        if (request()->isMethod('post')) {
            $accept = [
                'user_email',
                'user_name',
                'user_nick',
                'user_bio',
            ];

            try {
                DB::beginTransaction();

                $input = request()->input($this->input);
                foreach ($input as $key => $value) {
                    if (!in_array($key, $accept)) unset($input[$key]);
                }
                // return $input;
                $rules = [
                    "{$this->inputFile}.user_photo" => 'nullable|mimes:jpg,png',
                    "{$this->input}.user_email" => "required|email:rfc,dns",
                ];
                $messages = [
                    "{$this->inputFile}.user_photo.mimes" => ':attribute harus berupa file :values.',
                    "{$this->input}.user_email.required" => "Email harus diinputkan",
                    "{$this->input}.user_email.email" => "Harap inputkan email dengan benar",
                ];
                $validator = Validator::make(request()->all(), $rules, $messages);

                if ($validator->fails()) throw new \Exception($validator->errors());

                if (!empty($fileUpload = $this->uploadFile($this->getInputFile(), 'foto', 'user_photo'))) {
                    $input['user_photo'] = $fileUpload;
                }

                $oldData = DB::table('user')
                    ->where('user_id', $user->user_id)
                    ->first();
                if (empty($oldData)) abort(404);

                DB::table('user')
                    ->where('user_id', $user->user_id)
                    ->update($input);

                logHistory([
                    'nama_proses' => 'UPDATE DATA',
                    'ket_proses' => 'TABEL user',
                    'data_proses' => json_encode([
                        'old' => $oldData,
                        'new' => $input
                    ]),
                ]);

                DB::commit();

                return redirect('profil')->with([
                    'success' => true,
                    'message' => 'Perubahan Berhasil Disimpan',
                ]);
            } catch (Exception $ex) {
                DB::rollback();

                return redirect('profil')->with([
                    'success' => false,
                    'errorMessage' => errorMessage($ex),
                ]);
            }
        }

        $role = UserService::getAssignedRole(User::class, $user->user_id);

        $levels = ['administrator' => 'Super Admin', 'jurnalis' => 'User'];
        $more = [
            'levels' => $levels,
            'role' => $role
        ];

        return view($this->getView('profile'), [
            'more' => $more,
            'user' => $user,
        ]);
    }

    public function gantiPassword()
    {
        $user = auth()->user();
        $password = request()->input('password');

        if ($password['pass_lama'] != decode($user->user_password)) {
            return redirect('profil')->with([
                'success' => false,
                'message' => 'Password lama tidak sesuai.',
            ]);
        }
        if ($password['pass_baru'] != $password['pass_ulangi']) {
            return redirect('profil')->with([
                'success' => false,
                'message' => 'Password baru dan ulangi password baru harus sama.',
            ]);
        }

        User::where('user_id', $user->user_id)->update([
            'user_password' => encode($password['pass_baru']),
        ]);

        return redirect('profil')->with([
            'success' => true,
            'message' => 'Password berhasil diganti.',
        ]);
    }

    protected function getDashboarOtherData($type, $year, $month = null)
    {
        $query = DashboardOther::where('type', $type)
            ->where('year', $year);

        if (!empty($month)) {
            return $query->where('month', $month)->orderBy('summary', 'desc')->get();
        } else {
            return $query->get()
                ->groupBy('label')
                ->map(function ($group) {
                    return $group->sum('summary');
                })
                ->map(function ($summary, $label) {
                    return [
                        'label' => $label,
                        'summary' => $summary,
                    ];
                })
                ->sortDesc()
                ->values()
                ->toArray();
        }
    }
}
