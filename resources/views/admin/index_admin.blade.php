<?php $page = 'index_admin'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Welcome Admin!</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-primary border-primary">
                                    <i class="fe fe-users"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['total_psychologists'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Psychologists</h6>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-primary w-50"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-success">
                                    <i class="fe fe-credit-card"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['total_patients'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">

                                <h6 class="text-muted">Patients</h6>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success w-50"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-danger border-danger">
                                    <i class="fe fe-money"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['total_appointments'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">

                                <h6 class="text-muted">Appointments</h6>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-danger w-50"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-warning border-warning">
                                    <i class="fe fe-folder"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">

                                <h6 class="text-muted">Revenue</h6>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning w-50"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-6">
						
                    <!-- Sales Chart -->
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">Revenue</h4>
                        </div>
                        <div class="card-body">
                            <div id="morrisArea"></div>
                        </div>
                    </div>
                    <!-- /Sales Chart -->
                    
                </div>
                <div class="col-md-12 col-lg-6">
                
                    <!-- Invoice Chart -->
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">Status</h4>
                        </div>
                        <div class="card-body">
                            <div id="morrisLine"></div>
                        </div>
                    </div>
                    <!-- /Invoice Chart -->
                    
                </div>		
            </div>
            <div class="row">
                <div class="col-md-6 d-flex">
                
                    <!-- Recent Orders -->
                    <div class="card card-table flex-fill">
                        <div class="card-header">
                            <h4 class="card-title">Doctors List</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Doctor Name</th>
                                            <th>Speciality</th>
                                            <th>Earned</th>
                                            <th>Reviews</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($top_psychologists ?? [] as $psychologist)
                                        <tr>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="{{ route('admin.psychologists.show', $psychologist->id) }}" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $psychologist->user->profile_image ? asset('storage/' . $psychologist->user->profile_image) : asset('assets_admin/img/doctors/doctor-thumb-01.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="{{ route('admin.psychologists.show', $psychologist->id) }}">{{ $psychologist->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $psychologist->specialization }}</td>
                                            <td>${{ number_format($psychologist->total_earnings ?? 0, 2) }}</td>
                                            <td>
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($psychologist->average_rating ?? 0))
                                                        <i class="fe fe-star text-warning"></i>
                                                    @else
                                                        <i class="fe fe-star-o text-secondary"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-1">({{ number_format($psychologist->average_rating ?? 0, 1) }})</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No psychologists found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Recent Orders -->
                    
                </div>
                <div class="col-md-6 d-flex">
                
                    <!-- Feed Activity -->
                    <div class="card  card-table flex-fill">
                        <div class="card-header">
                            <h4 class="card-title">Patients List</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>													
                                            <th>Patient Name</th>
                                            <th>Phone</th>
                                            <th>Last Visit</th>
                                            <th>Paid</th>													
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recent_patients ?? [] as $patient)
                                        <tr>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $patient->user->profile_image ? asset('storage/' . $patient->user->profile_image) : asset('assets_admin/img/patients/patient1.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $patient->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $patient->user->phone ?? 'N/A' }}</td>
                                            <td>{{ $patient->last_visit ? $patient->last_visit->format('d M Y') : 'N/A' }}</td>
                                            <td>${{ number_format($patient->total_paid ?? 0, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No patients found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /Feed Activity -->
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">

                    <!-- Recent Orders -->
                    <div class="card card-table">
                        <div class="card-header">
                            <h4 class="card-title">Appointment List</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0" id="index_admin_data">
                                    <thead>
                                        <tr>
                                            <th>Doctor Name</th>
                                            <th>Speciality</th>
                                            <th>Patient Name</th>
                                            <th>Apointment Time</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recent_appointments ?? [] as $appointment)
                                        <tr>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="{{ route('admin.psychologists.show', $appointment->psychologist->id) }}" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $appointment->psychologist->user->profile_image ? asset('storage/' . $appointment->psychologist->user->profile_image) : asset('assets_admin/img/doctors/doctor-thumb-01.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="{{ route('admin.psychologists.show', $appointment->psychologist->id) }}">{{ $appointment->psychologist->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $appointment->psychologist->specialization }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $appointment->patient->user->profile_image ? asset('storage/' . $appointment->patient->user->profile_image) : asset('assets_admin/img/patients/patient1.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $appointment->patient->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $appointment->appointment_date->format('d M Y') }} <span class="d-block text-info">{{ $appointment->appointment_time }}</span></td>
                                            <td>
                                                <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'completed' ? 'info' : ($appointment->status === 'cancelled' ? 'danger' : 'warning')) }}-light">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td>${{ number_format($appointment->psychologist->consultation_fee, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No appointments found</td>
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
