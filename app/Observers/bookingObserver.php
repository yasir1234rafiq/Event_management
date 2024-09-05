<?php

namespace App\Observers;

use App\Mail\BookingConfirmation;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;


class bookingObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        // Check if the status has been updated to 'confirmed'

        if ($ticket->isDirty('status') && $ticket->status === 'confirmed') {
            // Send the email
            Mail::to($ticket->user->email)->send(new BookingConfirmation($ticket));
        }
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "force deleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
