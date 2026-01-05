<?php $page = 'medical-records'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            Medical Records
        @endslot
        @slot('li_2')
            My Medical Records
        @endslot
    @endcomponent
   
   	<!-- Page Content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-xl-3 theiaStickySidebar">
                    @component('components.sidebar_patient')
                    @endcomponent 
                </div>
                
                <div class="col-lg-8 col-xl-9">
                    <div class="dashboard-header">
                        <h3>My Medical Records</h3>
                        <p class="text-muted">View your complete medical history, appointments, diagnoses, and prescriptions in one place.</p>
                    </div>

                    @php
                        $patient = Auth::user()->patient;
                        $appointments = $patient ? $patient->appointments()->with(['psychologist.user', 'prescription'])->latest()->get() : collect();
                        $prescriptions = $patient ? $patient->prescriptions()->with(['appointment.psychologist.user'])->latest()->get() : collect();
                    @endphp

                    <!-- Medical History Section -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-heart-pulse me-2 text-primary"></i>Medical History
                            </h5>
                            <a href="{{ route('patient.profile') }}#medical" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-edit me-1"></i>Edit
                            </a>
                        </div>
                        <div class="card-body">
                            @if($patient && $patient->medical_history)
                                <div class="medical-history-content">
                                    {!! nl2br(e($patient->medical_history)) !!}
                                </div>
                            @else
                                <p class="text-muted mb-0">No medical history recorded. <a href="{{ route('patient.profile') }}#medical">Add medical history</a></p>
                            @endif
                        </div>
                    </div>

                    <!-- Emergency Contact Section -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-phone me-2 text-danger"></i>Emergency Contact
                            </h5>
                            <a href="{{ route('patient.profile') }}#medical" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-edit me-1"></i>Edit
                            </a>
                        </div>
                        <div class="card-body">
                            @if($patient && $patient->emergency_contact)
                                <p class="mb-0">{{ $patient->emergency_contact }}</p>
                            @else
                                <p class="text-muted mb-0">No emergency contact recorded. <a href="{{ route('patient.profile') }}#medical">Add emergency contact</a></p>
                            @endif
                        </div>
                    </div>

                    <!-- Appointments & Diagnoses Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-calendar-check me-2 text-info"></i>Appointment History & Diagnoses
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($appointments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Psychologist</th>
                                                <th>Status</th>
                                                <th>Diagnosis</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments as $appointment)
                                            <tr>
                                                <td>
                                                    {{ $appointment->appointment_date->format('M d, Y') }}<br>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a href="#" class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle" src="{{ $appointment->psychologist->user->profile_image ? asset('storage/' . $appointment->psychologist->user->profile_image) : asset('assets/img/doctors/doctor-thumb-01.jpg') }}" alt="User Image">
                                                        </a>
                                                        <a href="#">{{ $appointment->psychologist->user->name }}</a>
                                                    </h2>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'confirmed' ? 'info' : ($appointment->status === 'cancelled' ? 'danger' : 'warning')) }}">
                                                        {{ ucfirst($appointment->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($appointment->diagnosis)
                                                        <span class="badge bg-primary">Has Diagnosis</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('patient.appointments.show', $appointment) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa-solid fa-eye me-1"></i>View Details
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center py-4 mb-0">No appointments found</p>
                            @endif
                        </div>
                    </div>

                    <!-- Detailed Diagnoses Section -->
                    @if($appointments->where('diagnosis', '!=', null)->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-stethoscope me-2 text-warning"></i>Diagnoses
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($appointments->where('diagnosis', '!=', null) as $appointment)
                            <div class="diagnosis-item mb-4 pb-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">{{ $appointment->appointment_date->format('M d, Y') }}</h6>
                                        <p class="text-muted small mb-0">Dr. {{ $appointment->psychologist->user->name }}</p>
                                    </div>
                                    <a href="{{ route('patient.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary">View Appointment</a>
                                </div>
                                <div class="diagnosis-content mt-3">
                                    <strong class="text-primary">Diagnosis:</strong>
                                    <p class="mb-2 mt-1">{!! nl2br(e($appointment->diagnosis)) !!}</p>
                                    
                                    @if($appointment->session_notes)
                                        <strong class="text-info">Session Notes:</strong>
                                        <p class="mb-2 mt-1">{!! nl2br(e($appointment->session_notes)) !!}</p>
                                    @endif
                                    
                                    @if($appointment->observations)
                                        <strong class="text-success">Observations:</strong>
                                        <p class="mb-2 mt-1">{!! nl2br(e($appointment->observations)) !!}</p>
                                    @endif
                                    
                                    @if($appointment->follow_up_recommendations)
                                        <strong class="text-warning">Follow-up Recommendations:</strong>
                                        <p class="mb-0 mt-1">{!! nl2br(e($appointment->follow_up_recommendations)) !!}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Prescriptions Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-prescription-bottle me-2 text-success"></i>Prescriptions & Therapy Plans
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($prescriptions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Psychologist</th>
                                                <th>Type</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($prescriptions as $prescription)
                                            <tr>
                                                <td>
                                                    {{ $prescription->created_at->format('M d, Y') }}<br>
                                                    <small class="text-muted">{{ $prescription->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a href="#" class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle" src="{{ $prescription->appointment->psychologist->user->profile_image ? asset('storage/' . $prescription->appointment->psychologist->user->profile_image) : asset('assets/img/doctors/doctor-thumb-01.jpg') }}" alt="User Image">
                                                        </a>
                                                        <a href="#">{{ $prescription->appointment->psychologist->user->name }}</a>
                                                    </h2>
                                                </td>
                                                <td>
                                                    @if($prescription->therapy_plan)
                                                        <span class="badge bg-info">Therapy Plan</span>
                                                    @endif
                                                    @if($prescription->notes)
                                                        <span class="badge bg-primary">Notes</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('patient.prescriptions.show', $prescription) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa-solid fa-eye me-1"></i>View Details
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center py-4 mb-0">No prescriptions found</p>
                            @endif
                        </div>
                    </div>

                    <!-- Summary Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-primary">{{ $appointments->count() }}</h3>
                                    <p class="text-muted mb-0">Total Appointments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-success">{{ $prescriptions->count() }}</h3>
                                    <p class="text-muted mb-0">Total Prescriptions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-warning">{{ $appointments->where('diagnosis', '!=', null)->count() }}</h3>
                                    <p class="text-muted mb-0">Diagnoses Recorded</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection

