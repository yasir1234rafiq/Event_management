<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EventService;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;

class EventController extends Controller
{
protected $eventService;

public function __construct(EventService $eventService)
{
$this->eventService = $eventService;
}

public function index(Request $request)
{
$filters = [
'from_date' => $request->input('from_date'),
'to_date' => $request->input('to_date'),
'place' => $request->input('place'),
];

$user = $request->user();
if ($user->role === 'organizer') {
$filters['organizer_id'] = $user->id;
} elseif ($user->role === 'Attendee') {
return redirect()->route('home')->with('error', 'You are not authorized to access this route.');
}

$data['events'] = $this->eventService->getAllEvents($filters);

return view('events.index', $data);
}

public function create()
{
return view('events.create');
}

public function store(Request $request)
{
$request->validate([
'title' => 'required',
'description' => 'required',
'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
'date' => 'required',
'place' => 'required',
'ticket' => 'required| min:1',
'status' => 'required',
]);

$userId = auth()->user()->id;

$path = $request->file('image')->store('public/images');
$data = $request->only(['title', 'description', 'date', 'place', 'ticket', 'status']);
$data['image'] = $path;
$data['organizer_id'] = $userId;

$this->eventService->createEvent($data);

return redirect()->route('events.index')->with('success', 'Event has been created successfully.');
}

public function show(Event $event)
{
return view('events.index', compact('event'));
}

public function edit(Event $event)
{
return view('events.edit', compact('event'));
}

public function update(Request $request, $id)
{
$request->validate([
'title' => 'required',
'description' => 'required',
'date' => 'required',
'place' => 'required',
'status' => 'required',
]);

$data = $request->only(['title', 'description', 'date', 'place', 'status']);
if ($request->hasFile('image')) {
$request->validate([
'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
]);
$data['image'] = $request->file('image')->store('public/images');
}

$this->eventService->updateEvent($id, $data);

return redirect()->route('events.index')->with('success', 'Event updated successfully.');
}

public function destroy(Event $event)
{
$this->eventService->deleteEvent($event->id);
return redirect()->route('events.index')->with('success', 'Event has been deleted successfully.');
}

public function event(Request $request)
{
$filters = [
'from_date' => $request->input('from_date'),
'to_date' => $request->input('to_date'),
'place' => $request->input('place'),
];

$data['events'] = $this->eventService->getAllEvents($filters);

return view('event', $data);
}

public function bookEvent(Request $request)
{
$request->validate([
'event_id' => 'required|exists:events,id',
'user_id' => 'required|exists:users,id',
]);

$existingBooking = Ticket::where('event_id', $request->event_id)
->where('user_id', $request->user_id)
->first();

if ($existingBooking) {
return redirect()->back()->with('error', 'You have already booked this event.');
}

$ticket = new Ticket();
$ticket->event_id = $request->event_id;
$ticket->user_id = $request->user_id;
$ticket->status = 'pending';
$ticket->save();

Mail::to(auth()->user()->email)->send(new BookingConfirmation($ticket));

return redirect()->back()->with('success', 'Your booking is pending confirmation.');
}
}
