<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Reminder;
use Illuminate\Support\Facades\Artisan;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Ticket::with('user', 'event')->get(); // Adjust query as needed
        return view('booking_index', compact('bookings'));
    }

    public function sendReminder($id)
    {
        $ticket = Ticket::find($id);

        if ($ticket) {
            $user = $ticket->user;
            $event = $ticket->event;

            Artisan::call('app:reminder-mail', ['bookingId' => $id]);

            return redirect()->back()->with('success', 'Reminder sent successfully.');


        }

        return redirect()->back()->with('error', 'Booking not found.');
    }
    public function updateStatus(Request $request, $id)
    {
        $booking = Ticket::findOrFail($id);
        $newStatus = $request->input('status');

        if (in_array($newStatus, ['pending', 'confirmed'])) {
            $booking->status = $newStatus;
            $booking->save();

            return redirect()->route('bookings.index')->with('success', 'Booking status updated successfully.');
        }

        return redirect()->route('bookings.index')->with('error', 'Invalid status.');
    }
    public function search(Request $request)
    {
        $query = $request->input('search');

        // Format the date if it's a valid date format
        $date = null;
        try {
            $date = Carbon::createFromFormat('d/m/Y', $query)->toDateString();
        } catch (\Exception $e) {
            // Handle invalid date format
        }

        // Query bookings using scopes
        $bookings = Ticket::query()
            ->when($query, function ($q) use ($query, $date) {
                $q->whereEventTitle($query)
                    ->orWhereUserName($query)
                    ->orWhereEventDate($date);
            })
            ->get();

        // Return JSON data
        return response()->json(['bookings' => $bookings]);
    }


}
