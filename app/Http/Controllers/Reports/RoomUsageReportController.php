<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class RoomUsageReportController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) ($request->query('month', now()->month));
        $year = (int) ($request->query('year', now()->year));

        // Bound month and year to sane ranges
        $month = max(1, min(12, $month));
        $year = max(2000, min(2100, $year));

        $start = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
        $end = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();

		// Pick duration expression per driver
		$driver = DB::getDriverName();
		if ($driver === 'sqlite') {
			$durationExpr = "SUM(strftime('%s', date || ' ' || end_time) - strftime('%s', date || ' ' || start_time))/3600";
		} elseif ($driver === 'pgsql') {
			$durationExpr = "SUM(EXTRACT(EPOCH FROM ((date::date + end_time::time) - (date::date + start_time::time))))/3600";
		} else {
			// mysql/mariadb default
			$durationExpr = "SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time)))/3600";
		}

        // Aggregate bookings within month by room
        // Status filter: include approved and completed usage; exclude pending/rejected
        $aggregates = Booking::query()
            ->select([
                'room_id',
				DB::raw('COUNT(*) as bookings_count'),
				DB::raw($durationExpr . ' as total_hours'),
            ])
            ->whereBetween('date', [$start, $end])
            ->whereIn('status', ['approved', 'completed'])
            ->groupBy('room_id')
            ->with('room')
            ->orderByDesc('bookings_count')
            ->get();

        // Prepare chart data
        $chartLabels = $aggregates->map(fn($row) => $row->room?->name ?? ('Room #'.$row->room_id));
        $chartData = $aggregates->map(fn($row) => (int) $row->bookings_count);

        return view('admin.reports.room_usage', [
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'aggregates' => $aggregates,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $month = (int) ($request->query('month', now()->month));
        $year = (int) ($request->query('year', now()->year));

        $month = max(1, min(12, $month));
        $year = max(2000, min(2100, $year));

        $start = Carbon::create($year, $month, 1)->startOfMonth()->toDateString();
        $end = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();

		$driver = DB::getDriverName();
		if ($driver === 'sqlite') {
			$durationExpr = "SUM(strftime('%s', date || ' ' || end_time) - strftime('%s', date || ' ' || start_time))/3600";
		} elseif ($driver === 'pgsql') {
			$durationExpr = "SUM(EXTRACT(EPOCH FROM ((date::date + end_time::time) - (date::date + start_time::time))))/3600";
		} else {
			$durationExpr = "SUM(TIME_TO_SEC(TIMEDIFF(end_time, start_time)))/3600";
		}

        $aggregates = Booking::query()
            ->select([
                'room_id',
				DB::raw('COUNT(*) as bookings_count'),
				DB::raw($durationExpr . ' as total_hours'),
            ])
            ->whereBetween('date', [$start, $end])
            ->whereIn('status', ['approved', 'completed'])
            ->groupBy('room_id')
            ->with('room')
            ->orderByDesc('bookings_count')
            ->get();

        $data = [
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'aggregates' => $aggregates,
            'generatedAt' => now(),
        ];

        // If dompdf is installed, generate a PDF; otherwise return a printable HTML page
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.room_usage_pdf', $data)->setPaper('a4', 'portrait');
            $filename = sprintf('room-usage-%04d-%02d.pdf', $year, $month);
            return $pdf->download($filename);
        }

        return view('admin.reports.room_usage_pdf', $data);
    }
}


