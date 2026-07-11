<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingTerkonfirmasiNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Booking $booking)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusTeks = $this->booking->status === 'lunas' ? 'LUNAS' : 'TERBAYAR (DP)';

        return (new MailMessage)
            ->subject('Pembayaran Diterima - Kode Booking: ' . $this->booking->kode_booking)
            ->greeting('Halo, ' . $this->booking->nama_pelanggan . '!')
            ->line('Pembayaran Anda untuk reservasi fasilitas telah kami verifikasi.')
            ->line('Berikut adalah rincian reservasi Anda:')
            ->line('• **Kode Booking:** ' . $this->booking->kode_booking)
            ->line('• **Fasilitas:** ' . $this->booking->fasilitas->nama)
            ->line('• **Tanggal:** ' . $this->booking->tanggal_booking->format('d M Y'))
            ->line('• **Waktu:** ' . date('H:i', strtotime($this->booking->jam_mulai)) . ' - ' . date('H:i', strtotime($this->booking->jam_selesai)))
            ->line('• **Total Harga:** Rp ' . number_format($this->booking->total_harga, 2, ',', '.'))
            ->line('• **Sisa Pembayaran:** Rp ' . number_format($this->booking->sisa_bayar, 2, ',', '.'))
            ->line('• **Status Booking:** ' . strtoupper($statusTeks))
            ->action('Cek Status Booking', url('/booking/status?kode=' . $this->booking->kode_booking))
            ->line('Simpan kode booking Anda untuk melakukan pengecekan status atau pelunasan di lokasi/sistem.')
            ->line('Terima kasih telah menggunakan layanan kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'kode_booking' => $this->booking->kode_booking,
            'status' => $this->booking->status
        ];
    }
}
