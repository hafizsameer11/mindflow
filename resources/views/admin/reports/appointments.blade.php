<?php $page = 'reports-appointments'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Appointment Report</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                            <li class="breadcrumb-item active">Appointment Report</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Filter Section -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.reports.appointments') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">From Date</label>
                                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from', $dateFrom->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">To Date</label>
                                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to', $dateTo->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Psychologist</label>
                                    <select class="form-control" name="psychologist_id">
                                        <option value="">All Psychologists</option>
                                        @foreach($psychologists ?? [] as $psychologist)
                                        <option value="{{ $psychologist->id }}" {{ request('psychologist_id') == $psychologist->id ? 'selected' : '' }}>
                                            {{ $psychologist->user->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                    <a href="{{ route('admin.reports.appointments') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Filter Section -->

            <!-- Statistics -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-primary">
                                    <i class="fe fe-calendar"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['total'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Total Appointments</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-success">
                                    <i class="fe fe-check-circle"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['completed'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Completed</h6>
                                <p class="text-muted mb-0">Rate: {{ $stats['completion_rate'] ?? 0 }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-danger">
                                    <i class="fe fe-x-circle"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['cancelled'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Cancelled</h6>
                                <p class="text-muted mb-0">Rate: {{ $stats['cancellation_rate'] ?? 0 }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-warning">
                                    <i class="fe fe-clock"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['pending'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Pending</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Statistics -->

            <!-- Daily Trends Chart -->
            @if(isset($dailyTrends) && count($dailyTrends) > 0)
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Daily Appointment Trends</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="dailyTrendsChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Psychologist Performance -->
            @if(isset($psychologistPerformance) && $psychologistPerformance->count() > 0)
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Top Psychologists by Performance</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Psychologist</th>
                                            <th>Total Appointments</th>
                                            <th>Completed</th>
                                            <th>Cancelled</th>
                                            <th>Completion Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($psychologistPerformance as $performance)
                                        <tr>
                                            <td>{{ $performance['psychologist'] }}</td>
                                            <td>{{ $performance['total'] }}</td>
                                            <td>{{ $performance['completed'] }}</td>
                                            <td>{{ $performance['cancelled'] }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $performance['completion_rate'] }}%">
                                                        {{ $performance['completion_rate'] }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Appointment Details Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Appointment Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Appointment ID</th>
                                            <th>Patient</th>
                                            <th>Psychologist</th>
                                            <th>Date & Time</th>
                                            <th>Status</th>
                                            <th>Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($appointments ?? [] as $appointment)
                                        <tr>
                                            <td>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ $appointment->patient->user->name ?? 'N/A' }}</td>
                                            <td>{{ $appointment->psychologist->user->name ?? 'N/A' }}</td>
                                            <td>
                                                {{ $appointment->appointment_date->format('M d, Y') }}<br>
                                                <small>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                @if($appointment->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($appointment->status == 'confirmed')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @elseif($appointment->status == 'completed')
                                                    <span class="badge bg-info">Completed</span>
                                                @else
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>{{ $appointment->duration }} min</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No appointments found for the selected period</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->

    @if(isset($dailyTrends) && count($dailyTrends) > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('dailyTrendsChart').getContext('2d');
        const dailyTrends = @json($dailyTrends);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyTrends.map(t => t.label),
                datasets: [{
                    label: 'Total Appointments',
                    data: dailyTrends.map(t => t.total),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Completed',
                    data: dailyTrends.map(t => t.completed),
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Cancelled',
                    data: dailyTrends.map(t => t.cancelled),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endif
@endsection

