<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth', 'namespace' => 'User'], function () {
    Route::get('index', 'DashboardController@index');
    Route::prefix('profil')->group(function () {
        Route::match(['get', 'post'], '/', 'DashboardController@profile');
        Route::post('ganti-password', 'DashboardController@gantiPassword');
    });
});
