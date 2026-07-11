<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\BookingReminderDue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReminderBookingNotification;

class KirimNotifikasiReminder
{
    /**
     * Create the event listener.
     */
    public function __construct(BookingReminderDue $event)
    {
        if ($event->booking->email) {
            Notification::route('mail', $event->booking->email)
                ->notify(new ReminderBookingNotification($event->booking));
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
