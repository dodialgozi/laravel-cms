<?php

namespace App\Providers;

use App\Models\Instance;
use Jenssegers\Date\Date;
use App\Models\Pengaturan;
use App\Models\SettingMenu;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Date::setLocale('id');
        Paginator::useBootstrap();

        // DB::listen(function ($query) {
        //     Log::info($query->sql, $query->bindings, $query->time);
        // });

        // Frontend
        try {
            View::share('setting', Pengaturan::get()
                ->mapWithKeys(function ($item) {
                    return [$item->key => $item->value];
                })->toArray());
            View::share(
                'menu',
                SettingMenu::with('children')
                    ->whereNull('parent_id')
                    ->where('menu_active', 1)
                    ->orderBy('menu_sequence')->get()
            );
        } catch (\Exception $ex) {
        }
    }
}
