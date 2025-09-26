<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        $ip = request()->ip();
        $userAgent = request()->header('User-Agent');
        $today = now()->startOfDay();

        $exists = DB::table('visits')
            ->where('visits_ip_address', $ip)
            ->where('visits_user_agent', $userAgent)
            ->whereDate('visits_visited_at', $today)
            ->exists();

        if ($exists) {
            DB::table('visits')
                ->where('visits_ip_address', $ip)
                ->where('visits_user_agent', $userAgent)
                ->whereDate('visits_visited_at', $today)
                ->update([
                    'visits_visited_at' => now()
                ]);
        } else {
            DB::table('visits')->insert([
                'visits_ip_address' => $ip,
                'visits_user_agent' => $userAgent,
                'visits_visited_at' => now(),
                'visits_created_at' => now(),
            ]);
        }


        Paginator::useBootstrapFive();
    }
}
