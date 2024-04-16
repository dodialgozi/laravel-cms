<?php

namespace App\Http\Controllers;

use GoDrive;
use App\Http\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Facades\Agent;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use Upload, ThrottlesLogins;

    protected $maxAttempts = 5;
    protected $decayMinutes = 1;

    public function index()
    {
        return redirect('index');
    }

    public function login(Request $request)
    {
        if (auth()->check()) return redirect('index');
        if ($request->isMethod('get')) return view('backend.partials.login');
        if ($request->isMethod('post')) {
            $this->incrementLoginAttempts($request);

            if ($this->hasTooManyLoginAttempts($request)) {
                return $this->sendLockoutResponse($request);
            }

            $validator = Validator::make($request->all(), [
                'captcha' => 'required|captcha',
            ], [
                'captcha.required' => 'Captcha harus diisi.',
                'captcha.captcha' => 'Captcha tidak valid.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $username = request()->username;
            $password = request()->password;
            $ingat = request()->ingat ?? 0;
            // remember me jika akses dari mobile
            $rememberMe = Agent::isMobile() || Agent::isTablet();

            if (auth()->attempt(['user_email' => $username, 'password' => $password], $ingat)) {
                $this->clearLoginAttempts($request);
                return redirect()->intended('index');
            } else {
                return redirect('login')->with('error', 'Username atau password tidak dikenal.');
            }
        }

        return view('backend.partials.login');
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }

    public function logout()
    {
        // clear session instance
        session()->forget('instance_id');
        auth()->logout();

        return redirect('login');
    }

    public function document($goDriveId)
    {
        return view('backend.layouts.document', [
            'goDriveId' => $goDriveId,
        ]);
    }

    public function googleDrive()
    {
        new \GoDrive();
    }

    public function uploadImage()
    {
        try {
            if (request()->hasFile('image')) {
                $imageUrl = $this->uploadFile("image", 'gambarPage');
                if ($imageUrl) {
                    return response()->json(['status' => 1, 'path' => $imageUrl], 200);
                } else {
                    return response()->json(['status' => 0, 'errors' => 'Failed to upload image.'], 400);
                }
            } else {
                return response()->json(['status' => 0, 'errors' => 'Image not found.'], 400);
            }
        } catch (\Exception $e) {
            \Log::debug($e->getMessage());
            return response()->json(['status' => 0, 'errors' => 'Unexpected error!'], 400);
        }
    }

    public function deleteImage()
    {
        try {
            $path = request()->path;
            $path = str_replace(asset('/'), '', $path);
            if (file_exists($path)) {
                unlink($path);
                return response()->json(['status' => 1, 'message' => 'Image deleted successfully.'], 200);
            } else {
                return response()->json(['status' => 0, 'errors' => 'Image not found.'], 200);
            }
        } catch (\Exception $e) {
            \Log::debug($e->getMessage());
            return response()->json(['status' => 0, 'errors' => 'Unexpected error!'], 400);
        }
    }

    public function username()
    {
        $field = (filter_var(request()->email, FILTER_VALIDATE_EMAIL) || !request()->email) ? 'email' : 'username';
        request()->merge([$field => request()->email]);
        return $field;
    }
}
