<?php $page = 'reports-payments'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Payment Report</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                            <li class="breadcrumb-item active">Payment Report</li>
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
                            <form method="GET" action="{{ route('admin.reports.payments') }}" class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">From Date</label>
                                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from', $dateFrom->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">To Date</label>
                                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to', $dateTo->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status">
                                        <option value="">All Status</option>
                                        <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                    <a href="{{ route('admin.reports.payments') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Filter Section -->

            <!-- Financial Statistics -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-success">
                                    <i class="fe fe-dollar-sign"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Total Revenue</h6>
                                <p class="text-muted mb-0">{{ $stats['verified_count'] ?? 0 }} verified payments</p>
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
                                    <h3>${{ number_format($stats['pending_amount'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Pending Verification</h6>
                                <p class="text-muted mb-0">{{ $stats['pending_count'] ?? 0 }} payments</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-primary">
                                    <i class="fe fe-credit-card"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>${{ number_format($stats['average_payment'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Average Payment</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-info">
                                    <i class="fe fe-file-text"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['total_payments'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Total Payments</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Financial Statistics -->

            <!-- Daily Revenue Chart -->
            @if(isset($dailyRevenue) && count($dailyRevenue) > 0)
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Daily Revenue Trends</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="dailyRevenueChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Monthly Comparison -->
            @if(isset($monthlyComparison) && $monthlyComparison->count() > 1)
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Monthly Revenue Comparison</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Revenue</th>
                                            <th>Payment Count</th>
                                            <th>Average Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monthlyComparison as $month)
                                        <tr>
                                            <td>{{ $month['month'] }}</td>
                                            <td><strong>${{ number_format($month['revenue'], 2) }}</strong></td>
                                            <td>{{ $month['count'] }}</td>
                                            <td>${{ number_format($month['count'] > 0 ? $month['revenue'] / $month['count'] : 0, 2) }}</td>
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

            <!-- Payment Details Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Payment Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Payment ID</th>
                                            <th>Patient</th>
                                            <th>Psychologist</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments ?? [] as $payment)
                                        <tr>
                                            <td>#PAY{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ $payment->appointment->patient->user->name ?? 'N/A' }}</td>
                                            <td>{{ $payment->appointment->psychologist->user->name ?? 'N/A' }}</td>
                                            <td><strong>${{ number_format($payment->amount, 2) }}</strong></td>
                                            <td>
                                                @if($payment->status == 'verified')
                                                    <span class="badge bg-success">Verified</span>
                                                @elseif($payment->status == 'pending_verification')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No payments found for the selected period</td>
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

    @if(isset($dailyRevenue) && count($dailyRevenue) > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('dailyRevenueChart').getContext('2d');
        const dailyRevenue = @json($dailyRevenue);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dailyRevenue.map(r => r.label),
                datasets: [{
                    label: 'Daily Revenue ($)',
                    data: dailyRevenue.map(r => r.revenue),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endif
@endsection

