<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PembayaranTerverifikasi;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingTerkonfirmasiNotification;


class KirimNotifikasiPembayaranTerverifikasi
{
    /**
     * Create the event listener.
     */
    public function __construct(PembayaranTerverifikasi $event)
    {
        if ($event->booking->email) {
            Notification::route('mail', $event->booking->email)
                ->notify(new BookingTerkonfirmasiNotification($event->booking));
        }

    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
    }
}
