@extends('layouts.app')
@section('title', 'Room Usage Report')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Room Usage Report</h1>
            <form class="row g-3 mb-4" method="get" action="{{ route('admin.reports.room_usage') }}">
                <div class="col-auto">
                    <label for="month" class="form-label">Month</label>
                    <select id="month" name="month" class="form-select">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected($m == $selectedMonth)>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-auto">
                    <label for="year" class="form-label">Year</label>
                    <input id="year" type="number" name="year" class="form-control" value="{{ $selectedYear }}" min="2000" max="2100" />
                </div>
                <div class="col-auto align-self-end">
                    <button type="submit" class="btn btn-primary">Generate</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.reports.room_usage.pdf', ['month' => $selectedMonth, 'year' => $selectedYear]) }}">Export PDF</a>
                </div>
            </form>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Usage Summary ({{ \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->format('F Y') }})</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Room</th>
                                    <th class="text-end">Bookings</th>
                                    <th class="text-end">Total Hours</th>
                                    <th class="text-end">Avg Hours/Booking</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($aggregates as $row)
                                    @php
                                        $roomName = $row->room?->name ?? ('Room #'.$row->room_id);
                                        $totalHours = (float) ($row->total_hours ?? 0);
                                        $count = (int) ($row->bookings_count ?? 0);
                                        $avg = $count > 0 ? $totalHours / $count : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $roomName }}</td>
                                        <td class="text-end">{{ number_format($count) }}</td>
                                        <td class="text-end">{{ number_format($totalHours, 2) }}</td>
                                        <td class="text-end">{{ number_format($avg, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No bookings found for this month.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Most Frequently Used Rooms</h5>
					<div id="chartContainer" class="mx-auto" style="max-width: 900px;">
						<canvas id="usageChart" style="width: 100%; height: 320px;"></canvas>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	const ctx = document.getElementById('usageChart').getContext('2d');
	const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! $chartLabels->toJson() !!},
            datasets: [{
                label: 'Bookings',
                data: {!! $chartData->toJson() !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgb(54, 162, 235)',
				borderWidth: 1,
				maxBarThickness: 36,
				barPercentage: 0.6,
				categoryPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
			maintainAspectRatio: false,
			aspectRatio: 2,
			layout: { padding: { top: 8, right: 8, bottom: 8, left: 8 } },
			plugins: {
				legend: { position: 'top', labels: { boxWidth: 18 } },
				title: { display: false },
				tooltip: { intersect: false, mode: 'index' }
			},
            scales: {
				x: { ticks: { autoSkip: true, maxRotation: 0 }, grid: { display: false } },
				y: { beginAtZero: true, precision: 0, grid: { color: 'rgba(0,0,0,0.05)' } }
            }
        }
    });
</script>
@endsection


