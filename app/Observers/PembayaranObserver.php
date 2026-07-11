<?php

namespace App\Observers;

use App\Models\pembayaran;
use App\Events\PembayaranTerverifikasi;

class PembayaranObserver
{
    /**
     * Handle the pembayaran "created" event.
     */
    public function created(pembayaran $pembayaran): void
    {
        //
    }

    /**
     * Handle the pembayaran "updated" event.
     */
    public function updated(pembayaran $pembayaran): void
    {
        if (!$pembayaran->isDirty('status_verifikasi') || $pembayaran->status_verifikasi !== 'terverifikasi') {
            return;
        }

        $booking = $pembayaran->booking;

        if ($pembayaran->tipe === 'dp') {
            $booking->dp_dibayar += $pembayaran->jumlah;
            $booking->sisa_bayar = $booking->total_harga - $booking->dp_dibayar;
            $booking->status = 'dp_terbayar';
        } elseif ($pembayaran->tipe === 'pelunasan') {
            $booking->sisa_bayar = 0;
            $booking->status = 'lunas';
        }

        $booking->save();
        event(new PembayaranTerverifikasi($booking, $pembayaran));
    }
    

    /**
     * Handle the pembayaran "deleted" event.
     */
    public function deleted(pembayaran $pembayaran): void
    {
        //
    }

    /**
     * Handle the pembayaran "restored" event.
     */
    public function restored(pembayaran $pembayaran): void
    {
        //
    }

    /**
     * Handle the pembayaran "force deleted" event.
     */
    public function forceDeleted(pembayaran $pembayaran): void
    {
        //
    }
}
