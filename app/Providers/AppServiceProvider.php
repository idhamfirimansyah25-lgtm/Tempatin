<?php

namespace App\Providers;

use App\Models\DetailTransaksi;
use App\Observers\DetailTransaksiObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\PembayaranObserver;
use App\Models\Pembayaran;

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
    Pembayaran::observe(PembayaranObserver::class);
    }
}
