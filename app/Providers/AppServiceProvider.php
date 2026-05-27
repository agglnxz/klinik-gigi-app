<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notifikasi;

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
        // Mengirim data notifikasi ke navbar secara global
        View::composer('layouts.navbar', function ($view) { // Sesuaikan nama path file navbar Anda
            $notifikasiNavbar = Notifikasi::with('pemesanan.pemeriksaan.pasien')
                ->orderByDesc('created_at')
                ->take(5) // Ambil 5 terbaru untuk dropdown
                ->get();

            $unreadCount = Notifikasi::where('is_read', false)->count();

            $view->with(compact('notifikasiNavbar', 'unreadCount'));
        });
    }
}
