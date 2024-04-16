<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth', 'namespace' => 'Admin'], function () {
    Route::get('index', 'DashboardController@index');

    $pref = 'role';
    $ctrl = 'RoleController';
    Route::prefix($pref)->group(function () use ($ctrl) {
        Route::match(['get', 'post'], '/{id}/permission', "{$ctrl}@permission");
        Route::get('select', "{$ctrl}@getSelect");
    });
    Route::resource($pref, "{$ctrl}");

    $pref = 'permission';
    $ctrl = 'PermissionController';
    Route::prefix($pref)->group(function () use ($ctrl) {
        Route::get('select', "{$ctrl}@getSelect");
        Route::delete('hapus-semua', "{$ctrl}@deleteAll");
        Route::post('permission', "{$ctrl}@permission");
    });
    Route::resource($pref, "{$ctrl}");

    $pref = 'permission-group';
    $ctrl = 'PermissionGroupController';
    Route::prefix($pref)->group(function () use ($ctrl) {
        Route::get('select', "{$ctrl}@getSelect");
    });
    Route::resource($pref, "{$ctrl}");

    $pref = 'admin-menu';
    $ctrl = 'AdminMenuController';
    Route::prefix($pref)->group(function () use ($ctrl) {
        Route::post('order', "{$ctrl}@order");
    });
    Route::resource($pref, "{$ctrl}");


    $pref = 'user';
    $ctrl = 'UserController';
    Route::prefix($pref)->group(function () use ($ctrl) {
        Route::get('select', "{$ctrl}@getSelect");
    });
    Route::resource($pref, "{$ctrl}");

    $pref = 'page';
    $ctrl = 'PageController';
    Route::resource($pref, "{$ctrl}");

    $pref = 'post-kategori';
    $ctrl = 'PostCategoryController';
    Route::prefix($pref)->group(function () use ($ctrl) {
        Route::get('select', "{$ctrl}@getSelect");
    });
    Route::resource($pref, "{$ctrl}");

    // $pref = 'lokasi';
    // $ctrl = 'LocationController';
    // Route::prefix($pref)->group(function () use ($ctrl) {
    //     Route::post('upload', "{$ctrl}@upload");
    // });
    // Route::resource($pref, "{$ctrl}");

    // $pref = 'location-kategori';
    // $ctrl = 'LocationCategoryController';
    // Route::prefix($pref)->group(function () use ($ctrl) {
    //     Route::get('select', "{$ctrl}@getSelect");
    // });
    // Route::resource($pref, "{$ctrl}");

    $pref = 'instance';
    $ctrl = 'InstanceController';
    Route::prefix($pref)->group(function () use ($ctrl) {
        Route::get('select', "{$ctrl}@getSelect");
        Route::get('set/{id}', "{$ctrl}@setInstance");
    });
    Route::resource($pref, "{$ctrl}");

    $pref = 'user-instance';
    $ctrl = 'UserInstanceController';
    Route::prefix($pref)->group(function () use ($ctrl) {
        Route::get('select', "{$ctrl}@getSelect");
    });
    Route::resource($pref, "{$ctrl}");

    Route::group(['prefix' => 'pengaturan', 'namespace' => 'Pengaturan'], function () {
        Route::prefix('umum')->group(function () {
            $ctrl = 'PengaturanUmumController';
            Route::get('/', "{$ctrl}@index");
            Route::post('save', "{$ctrl}@save");
        });

        Route::prefix('situs')->group(function () {
            $ctrl = 'PengaturanSitusController';
            Route::get('/', "{$ctrl}@index");
            Route::post('save', "{$ctrl}@save");
        });

        $pref = 'menu';
        $ctrl = 'PengaturanMenuController';
        Route::prefix($pref)->group(function () use ($ctrl) {
            Route::post('save-menu', "{$ctrl}@saveMenu");
        });
        Route::resource($pref, "{$ctrl}");

        Route::prefix('post-meta')->group(function () {
            $ctrl = 'PengaturanPostMetaController';
            Route::get('/', "{$ctrl}@index");
            Route::post('save', "{$ctrl}@save");
        });
    });

    // $pref = 'advertising-position';
    // $ctrl = 'AdvertisingPositionController';
    // Route::prefix($pref)->group(function () use ($ctrl) {
    //     Route::get('select', "{$ctrl}@getSelect");
    // });
    // Route::resource($pref, "{$ctrl}");

    // $pref = 'advertising';
    // $ctrl = 'AdvertisingController';
    // Route::resource($pref, "{$ctrl}");

    $pref = 'custom-post-category';
    $ctrl = 'CustomPostCategoryController';
    Route::prefix($pref)->group(function () use ($ctrl) {
        Route::get('select', "{$ctrl}@getSelect");
    });
    Route::resource($pref, "{$ctrl}");

    $pref = 'custom-post-type';
    $ctrl = 'CustomPostTypeController';
    Route::prefix($pref)->group(function () use ($ctrl) {
        Route::get('select', "{$ctrl}@getSelect");
    });
    Route::resource($pref, "{$ctrl}");

    $pref = 'custom-post';
    $ctrl = 'CustomPostController';
    Route::resource($pref, "{$ctrl}");

    $pref = 'kategori-jurnalis';
    $ctrl = 'CategoryJurnalisController';
    Route::prefix($pref)->group(function () use ($ctrl) {
        Route::get('select', "{$ctrl}@getSelect");
    });
    Route::resource($pref, "{$ctrl}");

    $pref = 'post-tag';
    $ctrl = 'PostTagController';
    Route::prefix($pref)->group(function () use ($ctrl) {
        Route::get('select', "{$ctrl}@getSelect");
    });
    Route::resource($pref, "{$ctrl}");

    $pref = 'post';
    $ctrl = 'PostController';
    Route::resource($pref, "{$ctrl}");

    $pref = 'custom-post/{code}/custom';
    $ctrl = 'CustomPostController';
    Route::resource($pref, "{$ctrl}");

    $pref = 'alumnus';
    $ctrl = 'AlumnusController';
    Route::resource($pref, "{$ctrl}");

    $pref = 'partner';
    $ctrl = 'PartnerController';
    Route::resource($pref, "{$ctrl}");

    $pref = 'lecturer';
    $ctrl = 'LecturerController';
    Route::resource($pref, "{$ctrl}");

    $pref = 'course';
    $ctrl = 'CourseController';
    Route::resource($pref, "{$ctrl}");

    $pref = 'slider';
    $ctrl = 'SliderController';
    Route::resource($pref, "{$ctrl}");

    $pref = 'contact';
    $ctrl = 'ContactController';
    Route::resource($pref, "{$ctrl}");

    $pref = 'gallery';
    $ctrl = 'GalleryController';
    Route::resource($pref, "{$ctrl}");

    // $pref = 'polling';
    // $ctrl = 'PollingController';
    // Route::prefix($pref)->group(function () use ($ctrl) {
    //     Route::get('select', "{$ctrl}@getSelect");

    //     $ctrl = 'PollingOptionController';
    //     $pref = '{polling}/option';
    //     Route::resource($pref, "{$ctrl}");
    // });
    // Route::resource($pref, "{$ctrl}");

    // $pref = 'quiz';
    // $ctrl = 'QuizController';
    // Route::prefix($pref)->group(function () use ($ctrl) {
    //     Route::get('select', "{$ctrl}@getSelect");

    //     $ctrl = 'QuizQuestionController';
    //     $pref = '{quiz}/question';
    //     Route::resource($pref, "{$ctrl}");
    // });
    // Route::resource($pref, "{$ctrl}");

    Route::group(['prefix' => 'laporan', 'namespace' => 'Laporan'], function () {
        Route::prefix('jumlah-posting-jurnalis')->group(function () {
            $ctrl = 'LaporanJumlahPostingJurnalisController';
            Route::get('/', "{$ctrl}@index");
            Route::post('print', "{$ctrl}@print");
        });
        Route::prefix('jumlah-post-view-jurnalis')->group(function () {
            $ctrl = 'LaporanJumlahPostViewJurnalisController';
            Route::get('/', "{$ctrl}@index");
            Route::post('print', "{$ctrl}@print");
        });
        Route::prefix('jumlah-rating-artikel-jurnalis')->group(function () {
            $ctrl = 'LaporanJumlahRatingArtikelJurnalisController';
            Route::get('/', "{$ctrl}@index");
            Route::post('print', "{$ctrl}@print");
        });
        Route::prefix('jumlah-rating-artikel')->group(function () {
            $ctrl = 'LaporanJumlahRatingArtikelController';
            Route::get('/', "{$ctrl}@index");
            Route::post('print', "{$ctrl}@print");
        });
        Route::prefix('pemenang-quiz')->group(function () {
            $ctrl = 'LaporanPemenangQuizController';
            Route::get('/', "{$ctrl}@index");
            Route::post('print', "{$ctrl}@print");
        });
        Route::prefix('jumlah-polling')->group(function () {
            $ctrl = 'LaporanPollingTertinggiController';
            Route::get('/', "{$ctrl}@index");
            Route::post('print', "{$ctrl}@print");
        });
    });
});
