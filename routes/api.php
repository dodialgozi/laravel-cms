<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// routes api/{domain}
Route::group(['prefix' => '{domain}'], function () {
    Route::get('/', 'Api\InstanceController@index');
    Route::get('/partners', 'Api\InstanceController@partners');
    Route::get('/lecturers', 'Api\InstanceController@lecturers');
    Route::get('/alumni', 'Api\InstanceController@alumni');
    Route::get('/settings', 'Api\InstanceController@settings');
    Route::get('/contacts', 'Api\InstanceController@contacts');
    Route::get('/courses', 'Api\InstanceController@courses');
    Route::get('/menu', 'Api\InstanceController@menu');

    Route::get('/posts', 'Api\PostController@index');
    Route::get('/posts/popular', 'Api\PostController@popular');
    Route::get('/posts/recent', 'Api\PostController@recent');
    Route::get('/posts/{slug}', 'Api\PostController@show');

    Route::get('/categories', 'Api\CategoryController@index');
    Route::get('/categories/{slug}', 'Api\CategoryController@show');

    Route::get('/galleries', 'Api\GalleryController@index');
    Route::get('/galleries/{slug}', 'Api\GalleryController@show');

    Route::get('/pages', 'Api\PageController@index');
    Route::get('/pages/{slug}', 'Api\PageController@show');
});
