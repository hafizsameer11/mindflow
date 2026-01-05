<?php $page = 'prescriptions'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Psychologist
        @endslot
        @slot('li_1')
            Prescriptions
        @endslot
        @slot('li_2')
            My Prescriptions
        @endslot
    @endcomponent
   
   	<!-- Page Content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-xl-3 theiaStickySidebar">
                    @component('components.sidebar_doctor')
                    @endcomponent 
                </div>
                
                <div class="col-lg-8 col-xl-9">
                    <div class="dashboard-header">
                        <h3>My Prescriptions</h3>
                        <p class="text-muted">View and manage all prescriptions and therapy notes you've created for your patients.</p>
                    </div>

                    @if(isset($prescriptions) && $prescriptions->count() > 0)
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            Showing <strong>{{ $prescriptions->count() }}</strong> prescription(s) out of <strong>{{ $stats['total'] ?? 0 }}</strong> total.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-sm-6 col-12 mb-3">
                            <div class="dashboard-widget-box">
                                <div class="dashboard-content-info">
                                    <h6>Total Prescriptions</h6>
                                    <h4>{{ $stats['total'] ?? 0 }}</h4>
                                </div>
                                <div class="dashboard-widget-icon">
                                    <span class="dash-icon-box"><i class="fa-solid fa-prescription-bottle text-primary"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-3">
                            <div class="dashboard-widget-box">
                                <div class="dashboard-content-info">
                                    <h6>This Month</h6>
                                    <h4>{{ $stats['this_month'] ?? 0 }}</h4>
                                </div>
                                <div class="dashboard-widget-icon">
                                    <span class="dash-icon-box"><i class="fa-solid fa-calendar text-info"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-3">
                            <div class="dashboard-widget-box">
                                <div class="dashboard-content-info">
                                    <h6>With Therapy Plan</h6>
                                    <h4>{{ $stats['with_therapy_plan'] ?? 0 }}</h4>
                                </div>
                                <div class="dashboard-widget-icon">
                                    <span class="dash-icon-box"><i class="fa-solid fa-heart-pulse text-success"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12 mb-3">
                            <div class="dashboard-widget-box">
                                <div class="dashboard-content-info">
                                    <h6>With Notes</h6>
                                    <h4>{{ $stats['with_notes'] ?? 0 }}</h4>
                                </div>
                                <div class="dashboard-widget-icon">
                                    <span class="dash-icon-box"><i class="fa-solid fa-note-sticky text-warning"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('psychologist.prescriptions.index') }}" method="GET" class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Search</label>
                                    <input type="text" name="search" class="form-control" placeholder="Search by patient name, email, or prescription content..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date From</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date To</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fa-solid fa-search me-1"></i>Filter
                                        </button>
                                        @if(request()->hasAny(['search', 'date_from', 'date_to']))
                                        <a href="{{ route('psychologist.prescriptions.index') }}" class="btn btn-secondary">
                                            <i class="fa-solid fa-times"></i>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Prescriptions List -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Prescriptions & Therapy Notes</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Patient</th>
                                            <th>Appointment</th>
                                            <th>Type</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($prescriptions ?? [] as $prescription)
                                        @php
                                            $appointment = $prescription->appointment;
                                            $patient = $appointment->patient ?? null;
                                            $patientUser = $patient->user ?? null;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $prescription->created_at->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $prescription->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $patientUser && $patientUser->profile_image ? asset('storage/' . $patientUser->profile_image) : asset('assets/img/doctors/doctor-thumb-01.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $patientUser ? $patientUser->name : 'N/A' }}</a>
                                                </h2>
                                            </td>
                                            <td>
                                                <span>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</span><br>
                                                <small class="text-muted">{{ $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y') : 'N/A' }}</small>
                                            </td>
                                            <td>
                                                @if($prescription->therapy_plan)
                                                    <span class="badge bg-info">Therapy Plan</span>
                                                @endif
                                                @if($prescription->notes)
                                                    <span class="badge bg-primary">Notes</span>
                                                @endif
                                                @if(!$prescription->therapy_plan && !$prescription->notes)
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    @if($appointment)
                                                    <a href="{{ route('psychologist.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary" title="View Appointment">
                                                        <i class="fa-solid fa-calendar me-1"></i>Appointment
                                                    </a>
                                                    @endif
                                                    <a href="{{ route('psychologist.prescriptions.edit', $prescription) }}" class="btn btn-sm btn-primary" title="Edit Prescription">
                                                        <i class="fa-solid fa-edit me-1"></i>Edit
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fa-solid fa-prescription-bottle text-muted" style="font-size: 48px;"></i>
                                                <p class="text-muted mt-3 mb-0">No prescriptions found</p>
                                                <p class="text-muted small">Prescriptions you create will appear here.</p>
                                                <a href="{{ route('psychologist.appointments.index') }}" class="btn btn-primary mt-2">
                                                    <i class="fa-solid fa-calendar me-2"></i>View Appointments
                                                </a>
                                            </td>
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
    <!-- /Page Content -->
@endsection

