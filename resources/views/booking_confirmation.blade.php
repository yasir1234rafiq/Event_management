<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body>
<h1>Your booking is pending confirmation</h1>
<p>Event: {{ $ticket->event->title }}</p>
<p>Date: {{ $ticket->event->Date }}</p>
<p>We will notify you once your booking is confirmed.</p>
</body>
</html>
