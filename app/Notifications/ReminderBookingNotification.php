<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class ReminderBookingNotification extends Notification
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
        return (new MailMessage)
            ->subject('Pengingat Jadwal Reservasi Besok - ' . $this->booking->kode_booking)
            ->greeting('Halo, ' . $this->booking->nama_pelanggan . '!')
            ->line('Ini adalah pengingat bahwa Anda memiliki jadwal reservasi aktif untuk esok hari.')
            ->line('Detail Jadwal Anda:')
            ->line('• **Fasilitas:** ' . $this->booking->fasilitas->nama)
            ->line('• **Tanggal:** ' . $this->booking->tanggal_booking->format('d M Y'))
            ->line('• **Waktu:** ' . date('H:i', strtotime($this->booking->jam_mulai)) . ' - ' . date('H:i', strtotime($this->booking->jam_selesai)))
            ->line('• **Kode Booking:** ' . $this->booking->kode_booking)
            ->line('• **Sisa Pembayaran:** Rp ' . number_format($this->booking->sisa_bayar, 2, ',', '.'))

            // Memberikan catatan tambahan jika status masih DP (belum lunas)
            ->when($this->booking->status === 'dp_terbayar', function (MailMessage $mail) {
                return $mail->line('**Catatan:** Harap lakukan pelunasan sisa pembayaran di lokasi sebelum menggunakan fasilitas.');
            })

            ->action('Lihat Detail Reservasi', url('/booking/status?kode=' . $this->booking->kode_booking))
            ->line('Mohon datang 15 menit sebelum waktu reservasi dimulai.')
            ->line('Terima kasih, sampai jumpa esok hari!');
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
            'tanggal_booking' => $this->booking->tanggal_booking
        ];
    }
}
