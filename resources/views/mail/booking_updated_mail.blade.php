<h2>Your room booking details for room {{ $mailData['room'] }} has been updated.</h2>

<p>Hello, {{ $mailData['name'] }}!</p>
<p>We are pleased to inform you that your booking details has been updated successfully for the room <strong>{{ $mailData['room'] }}</strong>.</p>

<br><br>

<p><strong>(Updated) Booking Details:</strong></p>
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