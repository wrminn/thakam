<?php

namespace App\Providers;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Slide;


class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $SlideTop = Slide::active()
                ->where('slide_menu', 68)
                ->get();

            $recentMenu = DB::table('categories')
                ->where('categories_menu', 33)
                ->where('categories_display', "A")
                ->get();
           
            $view->with([
                'SlideTop' => $SlideTop,
                'recentMenu' => $recentMenu,
            ]);
        });
    }
}
