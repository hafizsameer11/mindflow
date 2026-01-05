<?php $page = 'prescriptions'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            <a href="{{ route('patient.prescriptions.index') }}">Prescriptions</a>
        @endslot
        @slot('li_2')
            Prescription Details
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
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Prescription Details</h5>
                            <a href="{{ route('patient.prescriptions.index') }}" class="btn btn-sm btn-secondary">Back to Prescriptions</a>
                        </div>
                        <div class="card-body">
                            <!-- Psychologist Information -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Prescribed By</h6>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $prescription->appointment->psychologist->user->profile_image ? asset('storage/' . $prescription->appointment->psychologist->user->profile_image) : asset('assets/img/doctors/doctor-thumb-01.jpg') }}" alt="Psychologist" class="avatar avatar-lg me-3">
                                        <div>
                                            <h5>{{ $prescription->appointment->psychologist->user->name }}</h5>
                                            <p class="text-muted mb-0">{{ $prescription->appointment->psychologist->specialization }}</p>
                                            <small class="text-muted">{{ $prescription->appointment->psychologist->experience_years }} years of experience</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Appointment Information</h6>
                                    <p><strong>Appointment ID:</strong> #APT{{ str_pad($prescription->appointment->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    <p><strong>Date:</strong> {{ $prescription->appointment->appointment_date->format('M d, Y') }}</p>
                                    <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($prescription->appointment->appointment_time)->format('h:i A') }}</p>
                                    <p><strong>Prescription Date:</strong> {{ $prescription->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <!-- Therapy Plan -->
                            @if($prescription->therapy_plan)
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">
                                    <i class="fa-solid fa-heart-pulse me-2 text-primary"></i>Therapy Plan
                                </h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-0">{!! nl2br(e($prescription->therapy_plan)) !!}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Prescription Notes -->
                            @if($prescription->notes)
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">
                                    <i class="fa-solid fa-note-sticky me-2 text-info"></i>Prescription Notes
                                </h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-0">{!! nl2br(e($prescription->notes)) !!}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if(!$prescription->therapy_plan && !$prescription->notes)
                            <div class="alert alert-info">
                                <i class="fa-solid fa-info-circle me-2"></i>
                                No therapy plan or notes have been added to this prescription yet.
                            </div>
                            @endif
                            
                            <!-- Action Buttons -->
                            <div class="mt-4">
                                <a href="{{ route('patient.appointments.show', $prescription->appointment) }}" class="btn btn-primary">
                                    <i class="fa-solid fa-calendar me-2"></i>View Appointment Details
                                </a>
                                <button onclick="window.print()" class="btn btn-secondary">
                                    <i class="fa-solid fa-print me-2"></i>Print Prescription
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
    
    @push('styles')
    <style>
        @media print {
            .sidebar, .breadcrumb, .card-header, .btn, nav {
                display: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
    @endpush
@endsection

