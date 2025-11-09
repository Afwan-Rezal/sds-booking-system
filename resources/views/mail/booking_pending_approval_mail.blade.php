<h2>Your room booking, {{ $mailData['room'] }} is pending for approval.</h2>

<p>Hello, {{ $mailData['name'] }}!</p>
<p>We are pleased to inform you that your booking for the room <strong>{{ $mailData['room'] }}</strong> has been is pending for approval.</p>

<br><br>

<p><strong>Booking Details:</strong></p>
<ul>
    <li><strong>Room:</strong> {{ $mailData['room'] ?? 'N/A' }}</li>
    <li><strong>Date:</strong> {{ $mailData['date'] ?? 'N/A' }}</li>
    <li><strong>Time:</strong> {{ $mailData['time_slot'] ?? $mailData['time'] ?? 'N/A' }}</li>
</ul>

<br><br>

<p>Should you require to make changes to your booking, please change it <strong>within 30 minutes before the start time</strong>.</p>

<p>Thank you for using our booking system.</p>
<p>Best regards,<br>
The SDS Booking Team</p>