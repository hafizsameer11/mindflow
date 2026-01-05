<?php $page = 'reports-users'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">User Activity Report</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
                            <li class="breadcrumb-item active">User Activity Report</li>
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
                            <form method="GET" action="{{ route('admin.reports.users') }}" class="row g-3">
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
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                    <a href="{{ route('admin.reports.users') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Filter Section -->

            <!-- User Statistics -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-primary">
                                    <i class="fe fe-users"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['total_users'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Total Users</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-success">
                                    <i class="fe fe-user-check"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['active_users'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Active Users</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-info">
                                    <i class="fe fe-user-plus"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['total_psychologists'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Psychologists</h6>
                                <p class="text-muted mb-0">{{ $stats['verified_psychologists'] ?? 0 }} verified</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-warning">
                                    <i class="fe fe-user"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['total_patients'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Patients</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /User Statistics -->

            <!-- New Users Trend Chart -->
            @if(isset($newUsersTrend) && count($newUsersTrend) > 0)
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">New User Registrations Trend</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="newUsersChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Role Distribution -->
            @if(isset($roleDistribution))
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">User Role Distribution</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="roleDistributionChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">User Status Overview</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Active Users</span>
                                    <span><strong>{{ $stats['active_users'] ?? 0 }}</strong></span>
                                </div>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stats['total_users'] > 0 ? round(($stats['active_users'] / $stats['total_users']) * 100, 2) : 0 }}%">
                                        {{ $stats['total_users'] > 0 ? round(($stats['active_users'] / $stats['total_users']) * 100, 2) : 0 }}%
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Inactive Users</span>
                                    <span><strong>{{ $stats['inactive_users'] ?? 0 }}</strong></span>
                                </div>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $stats['total_users'] > 0 ? round(($stats['inactive_users'] / $stats['total_users']) * 100, 2) : 0 }}%">
                                        {{ $stats['total_users'] > 0 ? round(($stats['inactive_users'] / $stats['total_users']) * 100, 2) : 0 }}%
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Verified Psychologists</span>
                                    <span><strong>{{ $stats['verified_psychologists'] ?? 0 }}</strong></span>
                                </div>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $stats['total_psychologists'] > 0 ? round(($stats['verified_psychologists'] / $stats['total_psychologists']) * 100, 2) : 0 }}%">
                                        {{ $stats['total_psychologists'] > 0 ? round(($stats['verified_psychologists'] / $stats['total_psychologists']) * 100, 2) : 0 }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Top Active Users -->
            @if(isset($topActiveUsers) && $topActiveUsers->count() > 0)
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Top Active Users (by Appointments)</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Appointments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topActiveUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'psychologist' ? 'info' : 'success') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td><strong>{{ $user->appointments_count }}</strong></td>
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

    @if(isset($newUsersTrend) && count($newUsersTrend) > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('newUsersChart').getContext('2d');
        const newUsersTrend = @json($newUsersTrend);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: newUsersTrend.map(t => t.label),
                datasets: [{
                    label: 'Total New Users',
                    data: newUsersTrend.map(t => t.total),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }, {
                    label: 'New Psychologists',
                    data: newUsersTrend.map(t => t.psychologists),
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.1
                }, {
                    label: 'New Patients',
                    data: newUsersTrend.map(t => t.patients),
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

    @if(isset($roleDistribution))
    <script>
        const roleCtx = document.getElementById('roleDistributionChart').getContext('2d');
        const roleDistribution = @json($roleDistribution);
        
        new Chart(roleCtx, {
            type: 'doughnut',
            data: {
                labels: ['Admins', 'Psychologists', 'Patients'],
                datasets: [{
                    data: [roleDistribution.admin, roleDistribution.psychologist, roleDistribution.patient],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true
            }
        });
    </script>
    @endif
@endsection

