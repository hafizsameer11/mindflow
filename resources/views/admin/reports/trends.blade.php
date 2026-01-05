<?php $page = 'reports-trends'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">System Trends Analysis</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                            <li class="breadcrumb-item active">System Trends</li>
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
                            <form method="GET" action="{{ route('admin.reports.trends') }}" class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">From Date</label>
                                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from', $dateFrom->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">To Date</label>
                                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to', $dateTo->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label><br>
                                    <button type="submit" class="btn btn-primary">Analyze Trends</button>
                                    <a href="{{ route('admin.reports.trends') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Filter Section -->

            <!-- Growth Rates -->
            <div class="row">
                <div class="col-xl-4 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-{{ ($growthRates['appointments'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                    <i class="fe fe-trending-{{ ($growthRates['appointments'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $growthRates['appointments'] ?? 0 }}%</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Appointments Growth</h6>
                                <p class="text-muted mb-0">Compared to previous period</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-{{ ($growthRates['revenue'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                    <i class="fe fe-trending-{{ ($growthRates['revenue'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $growthRates['revenue'] ?? 0 }}%</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Revenue Growth</h6>
                                <p class="text-muted mb-0">Compared to previous period</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-{{ ($growthRates['users'] ?? 0) >= 0 ? 'success' : 'danger' }}">
                                    <i class="fe fe-trending-{{ ($growthRates['users'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $growthRates['users'] ?? 0 }}%</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">User Growth</h6>
                                <p class="text-muted mb-0">Compared to previous period</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Growth Rates -->

            <!-- Key Insights -->
            @if(isset($insights))
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Key Insights</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fe fe-calendar" style="font-size: 32px; color: #007bff;"></i>
                                            <h6 class="mt-2">Peak Appointment Day</h6>
                                            <p class="mb-0"><strong>{{ $insights['peak_appointment_day'] ?? 'N/A' }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fe fe-dollar-sign" style="font-size: 32px; color: #28a745;"></i>
                                            <h6 class="mt-2">Peak Revenue Day</h6>
                                            <p class="mb-0"><strong>{{ $insights['peak_revenue_day'] ?? 'N/A' }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fe fe-user-check" style="font-size: 32px; color: #ffc107;"></i>
                                            <h6 class="mt-2">Most Active Psychologist</h6>
                                            <p class="mb-0"><strong>{{ $insights['most_active_psychologist'] ?? 'N/A' }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fe fe-clock" style="font-size: 32px; color: #17a2b8;"></i>
                                            <h6 class="mt-2">Avg Session Duration</h6>
                                            <p class="mb-0"><strong>{{ $insights['average_session_duration'] ?? 0 }} min</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Monthly Trends Chart -->
            @if(isset($monthlyTrends) && count($monthlyTrends) > 0)
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Monthly Trends Overview</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="monthlyTrendsChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Trends Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Monthly Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Appointments</th>
                                            <th>Completed</th>
                                            <th>Revenue</th>
                                            <th>New Users</th>
                                            <th>New Psychologists</th>
                                            <th>New Patients</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monthlyTrends as $trend)
                                        <tr>
                                            <td><strong>{{ $trend['label'] }}</strong></td>
                                            <td>{{ $trend['appointments'] }}</td>
                                            <td>{{ $trend['completed_appointments'] }}</td>
                                            <td><strong class="text-success">${{ number_format($trend['revenue'], 2) }}</strong></td>
                                            <td>{{ $trend['new_users'] }}</td>
                                            <td>{{ $trend['new_psychologists'] }}</td>
                                            <td>{{ $trend['new_patients'] }}</td>
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
        </div>
    </div>
    <!-- /Page Wrapper -->

    @if(isset($monthlyTrends) && count($monthlyTrends) > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('monthlyTrendsChart').getContext('2d');
        const monthlyTrends = @json($monthlyTrends);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyTrends.map(t => t.label),
                datasets: [{
                    label: 'Appointments',
                    data: monthlyTrends.map(t => t.appointments),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    yAxisID: 'y',
                    tension: 0.1
                }, {
                    label: 'Revenue ($)',
                    data: monthlyTrends.map(t => t.revenue),
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    yAxisID: 'y1',
                    tension: 0.1
                }, {
                    label: 'New Users',
                    data: monthlyTrends.map(t => t.new_users),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    yAxisID: 'y',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toFixed(0);
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endif
@endsection

