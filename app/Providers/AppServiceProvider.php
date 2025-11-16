<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // <-- 1. TAMBAHKAN INI
use App\Models\ActivityLog;          // <-- 2. TAMBAHKAN INI

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
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8');

        // --- [ 3. TAMBAHKAN BLOK KODE INI ] ---
        // Membagikan data log aktivitas terbaru ke view 'layout'
        // Ini akan membuat variabel $recent_logs tersedia di layout.blade.php
        try {
            View::composer('layout', function ($view) {
                $recent_logs = ActivityLog::with('employee') // Eager load data employee
                                    ->latest()        // Ambil yang terbaru
                                    ->take(5)           // Batasi 5
                                    ->get();
                $view->with('recent_logs', $recent_logs);
            });
        } catch (\Exception $e) {
            // Tangani error jika database belum siap (misal saat migrasi awal)
            View::composer('layout', function ($view) {
                $view->with('recent_logs', collect());
            });
        }
        // --- [ AKHIR BLOK KODE ] ---
    }
}
