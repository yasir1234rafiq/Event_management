<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <h1>Manage Bookings</h1>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {!! $message !!}
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            {!! $message !!}
        </div>
    @endif

    <div class="mb-3">
        <input type="text" id="search" class="form-control" placeholder="Search by Event Name, User Name, or Date">
    </div>

    <table class="table table-striped" id="bookings-table">
        <thead>
        <tr>
            <th>Event</th>
            <th>User</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($bookings as $booking)
            <tr>
                <td>{{ $booking->event->title }}</td>
                <td>{{ $booking->user->name }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->event->date)->format('d/m/Y') }}</td>
                <td>{{ ucfirst($booking->status) }}</td>
                <td>
                    @if ($booking->status == 'pending')
                        <form action="{{ route('bookings.updateStatus', $booking->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="btn btn-success">Confirm</button>
                        </form>
                    @elseif ($booking->status == 'confirmed')
                        <button class="btn btn-secondary" disabled>Confirmed</button>
                    @endif

                        @if ($booking->status == 'confirmed')
                            <form action="{{ route('bookings.sendReminder', $booking->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning">Send Reminder</button>
                            </form>
                        @else
                            <button class="btn btn-secondary" disabled>Send Reminder</button>
                        @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var query = $(this).val();

            $.ajax({
                url: "{{ route('bookings.search') }}",
                method: 'GET',
                data: { search: query },
                success: function(response) {
                    var bookings = response.bookings;
                    var tbody = $('#bookings-table tbody');
                    tbody.empty(); // Clear existing rows

                    if (bookings.length > 0) {
                        bookings.forEach(function(booking) {
                            var date = new Date(booking.event.date);
                            var formattedDate = date.toLocaleDateString('en-GB'); // Format date as d/m/Y

                            var statusButton = '';
                            if (booking.status === 'pending') {
                                statusButton = '<form action="{{ route('bookings.updateStatus', '') }}/' + booking.id + '" method="POST" style="display:inline;">' +
                                    '@csrf' +
                                    '<input type="hidden" name="status" value="confirmed">' +
                                    '<button type="submit" class="btn btn-success">Confirm</button>' +
                                    '</form>';
                            } else if (booking.status === 'confirmed') {
                                statusButton = '<button class="btn btn-secondary" disabled>Confirmed</button>';
                            }

                            var reminderButton = '';
                            if (booking.status === 'scheduled') {
                                reminderButton = '<form action="{{ route('bookings.sendReminder', '') }}/' + booking.id + '" method="POST" style="display:inline;">' +
                                    '@csrf' +
                                    '<button type="submit" class="btn btn-warning">Send Reminder</button>' +
                                    '</form>';
                            } else {
                                reminderButton = '<button class="btn btn-secondary" disabled>Send Reminder</button>';
                            }

                            tbody.append('<tr>' +
                                '<td>' + booking.event.title + '</td>' +
                                '<td>' + booking.user.name + '</td>' +
                                '<td>' + formattedDate + '</td>' +
                                '<td>' + booking.status.charAt(0).toUpperCase() + booking.status.slice(1) + '</td>' +
                                '<td>' + statusButton + reminderButton + '</td>' +
                                '</tr>');
                        });
                    } else {
                        tbody.append('<tr><td colspan="5" class="text-center">No results found</td></tr>');
                    }
                },
                error: function(xhr) {
                    alert('An error occurred while processing your request.');
                }
            });
        });
    });
</script>
</body>
</html>
