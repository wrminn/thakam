<?php

namespace App\Providers;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Slide;
use Carbon\Carbon;

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
                ->where('categories_menu', 38)
                ->where('categories_display', "A")
                ->get();

            $now = Carbon::now();
            $stats = [
                '2min' => DB::table('visits')
                    ->where('visits_visited_at', '>=', $now->copy()->subMinutes(2))
                    ->count(),

                'today' => DB::table('visits')
                    ->whereDate('visits_visited_at', $now->toDateString())
                    ->count(),

                'weekly' => DB::table('visits')
                    ->whereBetween('visits_visited_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()])
                    ->count(),

                'monthly' => DB::table('visits')
                    ->whereYear('visits_visited_at', $now->year)
                    ->whereMonth('visits_visited_at', $now->month)
                    ->count(),

                'yearly' => DB::table('visits')
                    ->whereYear('visits_visited_at', $now->year)
                    ->count(),

                'total' => DB::table('visits')->count(),
            ];

            $view->with([
                'SlideTop' => $SlideTop,
                'recentMenu' => $recentMenu,
                'stats' => $stats,
            ]);
        });
    }
}
