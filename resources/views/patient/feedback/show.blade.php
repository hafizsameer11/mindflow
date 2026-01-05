<?php $page = 'feedback'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            <a href="{{ route('patient.feedback.index') }}">Feedback Review</a>
        @endslot
        @slot('li_2')
            Review Details
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
                            <h5 class="card-title">Review Details</h5>
                            <a href="{{ route('patient.feedback.index') }}" class="btn btn-sm btn-secondary">Back to Reviews</a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Psychologist Information</h6>
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="{{ $feedback->psychologist->user->profile_image ? asset('storage/' . $feedback->psychologist->user->profile_image) : asset('assets/index/doctor-profile-img.jpg') }}" alt="Psychologist" class="avatar avatar-lg me-3">
                                        <div>
                                            <h5>{{ $feedback->psychologist->user->name }}</h5>
                                            <p class="text-muted mb-0">{{ $feedback->psychologist->user->email }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Rating</h6>
                                    <div class="rating mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $feedback->rating ? 'filled' : '' }}" style="font-size: 32px;"></i>
                                        @endfor
                                    </div>
                                    <p class="mb-0"><strong>{{ $feedback->rating }}/5 Stars</strong></p>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Comment</h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $feedback->comment ?? 'No comment provided' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Appointment Date</h6>
                                    <p>{{ $feedback->appointment->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($feedback->appointment->appointment_time)->format('h:i A') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Review Date</h6>
                                    <p>{{ $feedback->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('patient.feedback.index') }}" class="btn btn-secondary">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Reviews
                                </a>
                                <a href="{{ route('patient.feedback.edit', $feedback->id) }}" class="btn btn-primary">
                                    <i class="fa-solid fa-edit me-1"></i> Edit Review
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection

