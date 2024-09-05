<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMail as ReminderMailMailable;

class ReminderMail extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reminder-mail {bookingId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send email for  reminder abut event';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bookingId = $this->argument('bookingId');
        $booking = Ticket::with('user')->findOrFail($bookingId);

        // Send email
        Mail::to($booking->user->email)->send(new ReminderMailMailable($booking));

        $this->info('Reminder email sent.');
    }
}
