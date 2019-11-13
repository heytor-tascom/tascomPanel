<?php

namespace App\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;

use App\Models\Modulo;

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
        view()->composer('*', function ($view) 
        {

            if(Auth::user()) {
                
                $menu    = Modulo::where("ativo", 1)->orderBy('nr_ordem', 'ASC')->with('paginas')->get();
                $view->with('menu', $menu);

            }

        });
    }
}
