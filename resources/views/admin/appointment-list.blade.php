<?php $page = 'appointment-list'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Appointments</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Appointments</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Statistics Cards -->
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
                                <p class="text-muted mb-0">Rate: {{ $cancellationRate ?? 0 }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Statistics Cards -->

            <!-- Pattern Analysis -->
            @if(isset($stats['missed']) && $stats['missed'] > 0)
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <i class="fe fe-alert-triangle"></i> 
                        <strong>Missed Sessions Alert:</strong> {{ $stats['missed'] }} confirmed appointment(s) have passed without being completed.
                    </div>
                </div>
            </div>
            @endif

            @if(isset($patientsWithRepeatedCancellations) && $patientsWithRepeatedCancellations->count() > 0)
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fe fe-alert-circle text-danger"></i> 
                                Patients with Repeated Cancellations (3+)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Patient Name</th>
                                            <th>Email</th>
                                            <th>Cancellation Count</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($patientsWithRepeatedCancellations as $patient)
                                        <tr>
                                            <td>{{ $patient->name }}</td>
                                            <td>{{ $patient->email }}</td>
                                            <td>
                                                <span class="badge bg-danger">{{ $patient->cancellation_count }} cancellations</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.appointments.index', ['search' => $patient->email, 'status' => 'cancelled']) }}" class="btn btn-sm btn-primary">
                                                    View Cancellations
                                                </a>
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
            <!-- /Pattern Analysis -->

            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.appointments.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" placeholder="Search by patient or psychologist name/email..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="status">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_from" placeholder="From Date" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_to" placeholder="To Date" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary w-100 mt-2">Clear</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Search and Filter -->

            <div class="row">
                <div class="col-md-12">
                    <!-- Recent Orders -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">All Appointments</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Psychologist Name</th>
                                            <th>Speciality</th>
                                            <th>Patient Name</th>
                                            <th>Appointment Time</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($appointments ?? [] as $appointment)
                                        <tr>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $appointment->psychologist->user->profile_image ? asset('storage/' . $appointment->psychologist->user->profile_image) : asset('assets_admin/img/doctors/doctor-thumb-01.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $appointment->psychologist->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $appointment->psychologist->specialization }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $appointment->patient->user->profile_image ? asset('storage/' . $appointment->patient->user->profile_image) : asset('assets_admin/img/patients/patient.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $appointment->patient->user->name }}</a>
                                                </h2>
                                            </td>
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
                                            <td>
                                                ${{ number_format($appointment->psychologist->consultation_fee, 2) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-sm btn-primary">View</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No appointments found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Recent Orders -->

                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->
@endsection
