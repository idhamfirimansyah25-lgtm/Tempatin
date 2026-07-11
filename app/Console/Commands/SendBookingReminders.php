<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\BookingReminderDue;
use App\Models\Booking;
use Carbon\Carbon;

class SendBookingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mencari jadwal booking H-1 esok hari dan memicu event untuk mengirim email pengingat kepada pelanggan.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $besok = Carbon::tomorrow()->toDateString();

        // Cari booking esok hari yang sudah terkonfirmasi (DP maupun Lunas)
        $bookings = Booking::where('tanggal_booking', $besok)
            ->whereIn('status', ['dp_terbayar', 'lunas'])
            ->get();

        if ($bookings->isEmpty()) {
            $this->info('Tidak ada jadwal booking terkonfirmasi untuk esok hari (' . $besok . ').');
            return Command::SUCCESS;
        }

        $counter = 0;
        foreach ($bookings as $booking) {
            // Memicu Event, yang nantinya ditangkap oleh Listener untuk mengirimkan Notification
            event(new BookingReminderDue($booking));
            $counter++;
        }

        $this->info("Berhasil memproses dan memicu event pengingat untuk {$counter} data booking.");
        return Command::SUCCESS;
    }
    }

