<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Room Usage Report - {{ sprintf('%04d-%02d', $selectedYear, $selectedMonth) }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 20px; margin-bottom: 8px; }
        .muted { color: #666; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #f3f4f6; text-align: left; }
        td.num { text-align: right; }
        .footer { margin-top: 24px; font-size: 11px; color: #666; }
    </style>
    @if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class))
        <script>
            window.addEventListener('load', () => window.print());
        </script>
    @endif
    </head>
<body>
    <h1>Room Usage Report</h1>
    <div class="muted">
        Period: {{ \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->format('F Y') }}<br>
        Generated: {{ $generatedAt->format('Y-m-d H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Room</th>
                <th>Bookings</th>
                <th>Total Hours</th>
                <th>Avg Hours/Booking</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandBookings = 0;
                $grandHours = 0.0;
            @endphp
            @forelse ($aggregates as $row)
                @php
                    $roomName = $row->room?->name ?? ('Room #'.$row->room_id);
                    $totalHours = (float) ($row->total_hours ?? 0);
                    $count = (int) ($row->bookings_count ?? 0);
                    $avg = $count > 0 ? $totalHours / $count : 0;
                    $grandBookings += $count;
                    $grandHours += $totalHours;
                @endphp
                <tr>
                    <td>{{ $roomName }}</td>
                    <td class="num">{{ number_format($count) }}</td>
                    <td class="num">{{ number_format($totalHours, 2) }}</td>
                    <td class="num">{{ number_format($avg, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#666;">No bookings found for this month.</td>
                </tr>
            @endforelse
            @if(count($aggregates) > 0)
            <tr>
                <th>Total</th>
                <th class="num">{{ number_format($grandBookings) }}</th>
                <th class="num">{{ number_format($grandHours, 2) }}</th>
                <th class="num">{{ $grandBookings > 0 ? number_format($grandHours / $grandBookings, 2) : '0.00' }}</th>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        SDS Booking System • Room Usage Report
    </div>
</body>
</html>


