<h2>Room Cancellation Mail</h2>

<p>Hello, {{ $mailData['name'] }}!</p>
<p>We would like to inform you that your booking for the room <strong>{{ $mailData['room'] }}</strong> has been cancelled.</p>

<br><br>

<p><strong>Cancellation Details:</strong></p>
<ul>
    <li><strong>Room:</strong> {{ $mailData['room'] ?? 'N/A' }}</li>
    <li><strong>Date:</strong> {{ $mailData['date'] ?? 'N/A' }}</li>
    <li><strong>Time:</strong> {{ $mailData['time_slot'] ?? $mailData['time'] ?? 'N/A' }}</li>
    <li><strong>Reason:</strong> {{ $mailData['reason'] ?? 'N/A' }}</li>
</ul>

<br><br>

<p>If you have any questions or need further assistance, please feel free to contact us.</p>

<p>Thank you for using our booking system.</p>
<p>Best regards,<br>
The SDS Booking Team</p>