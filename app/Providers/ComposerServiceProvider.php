<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use App\Models\PosNotificationBooking;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // view()->composer('*',function($view){

        //     $count_notification = PosNotificationBooking::where('checked',0)->count();

        //     $view->with('count_notification',$count_notification);
        // });
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
