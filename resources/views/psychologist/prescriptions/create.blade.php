<?php $page = 'prescriptions'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Psychologist
        @endslot
        @slot('li_1')
            <a href="{{ route('psychologist.appointments.show', $appointment) }}">Appointment Details</a>
        @endslot
        @slot('li_2')
            Create Prescription
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
                        <div class="header-back">
                            <a href="{{ route('psychologist.appointments.show', $appointment) }}" class="back-arrow">
                                <i class="fa-solid fa-arrow-left"></i>
                            </a>
                            <h3>Create Prescription</h3>
                        </div>
                        <p class="text-muted">Generate and assign prescriptions or therapy notes to patients. Store prescriptions securely in the database for patient access.</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Patient Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Patient Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Patient Name:</strong> {{ $appointment->patient->user->name }}</p>
                                    <p><strong>Email:</strong> {{ $appointment->patient->user->email }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Appointment Date:</strong> {{ $appointment->appointment_date->format('M d, Y') }}</p>
                                    <p><strong>Appointment Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prescription Form -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Prescription & Therapy Notes</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('psychologist.prescriptions.store', $appointment) }}" method="POST">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="therapy_plan" class="form-label">
                                        <i class="fa-solid fa-heart-pulse me-2 text-primary"></i>
                                        <strong>Therapy Plan</strong>
                                        <span class="text-muted">(Treatment plan, recommendations, and therapeutic approach)</span>
                                    </label>
                                    <textarea 
                                        name="therapy_plan" 
                                        id="therapy_plan" 
                                        class="form-control @error('therapy_plan') is-invalid @enderror" 
                                        rows="8" 
                                        placeholder="Enter the therapy plan, treatment recommendations, therapeutic approach, and any specific instructions for the patient..."
                                    >{{ old('therapy_plan') }}</textarea>
                                    @error('therapy_plan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">This will be visible to the patient and stored securely in the database.</small>
                                </div>

                                <div class="mb-4">
                                    <label for="notes" class="form-label">
                                        <i class="fa-solid fa-note-sticky me-2 text-info"></i>
                                        <strong>Prescription Notes</strong>
                                        <span class="text-muted">(Additional notes, observations, or instructions)</span>
                                    </label>
                                    <textarea 
                                        name="notes" 
                                        id="notes" 
                                        class="form-control @error('notes') is-invalid @enderror" 
                                        rows="6" 
                                        placeholder="Enter any additional notes, observations, follow-up instructions, or important information for the patient..."
                                    >{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">These notes will be included in the prescription and accessible to the patient.</small>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fa-solid fa-info-circle me-2"></i>
                                    <strong>Note:</strong> Once created, this prescription will be securely stored in the database and immediately accessible to the patient through their dashboard and appointment details.
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('psychologist.appointments.show', $appointment) }}" class="btn btn-light">
                                        <i class="fa-solid fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-prescription-bottle me-2"></i>Create Prescription
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection

