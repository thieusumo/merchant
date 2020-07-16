<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\PosNotification;
use App\Http\Controllers\Controller;
use Session;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // View::composer('layouts.master', function ($view) {
        //     $view->with('variable', 'displayDataTables');
        // });
        // $id=Controller::getCurrentPlaceId();
        // View::composer('*', function ($view) {
        //      $number=PosNotification::where('noti_place_id',$id)->where('readed',0)->count('id');
        //     $view->with('num', $number);
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
