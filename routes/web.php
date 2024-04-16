<?php

use Illuminate\Support\Facades\Route;

foreach (File::allFiles(__DIR__ . '/web') as $routeFile) require $routeFile->getPathname();

Route::get('/', 'AuthController@index');
Route::get('/login', 'AuthController@index');
Route::get('/reload-captcha', 'AuthController@reloadCaptcha');
Route::match(['get', 'post'], '/login', 'AuthController@login')->name('login');
Route::get('/logout', 'AuthController@logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/document/{goDriveId}', 'AuthController@document');
    Route::get('/google_drive', 'AuthController@googleDrive');
    Route::post('/upload-image', 'AuthController@uploadImage');
    Route::post('/delete-image', 'AuthController@deleteImage');
});
