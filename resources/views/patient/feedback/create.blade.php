<?php $page = 'feedback'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            <a href="{{ route('patient.appointments.show', $appointment) }}">Appointment Details</a>
        @endslot
        @slot('li_2')
            Write Feedback
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
                        <div class="header-back">
                            <a href="{{ route('patient.appointments.show', $appointment) }}" class="back-arrow">
                                <i class="fa-solid fa-arrow-left"></i>
                            </a>
                            <h3>Write Feedback</h3>
                        </div>
                        <p class="text-muted">Share your experience and help us improve our services.</p>
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

                    <!-- Appointment Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Appointment Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Psychologist:</strong> {{ $appointment->psychologist->user->name }}</p>
                                    <p><strong>Specialization:</strong> {{ $appointment->psychologist->specialization }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Appointment Date:</strong> {{ $appointment->appointment_date->format('M d, Y') }}</p>
                                    <p><strong>Appointment Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feedback Form -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Your Feedback</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('patient.feedback.store', $appointment) }}" method="POST">
                                @csrf
                                
                                <div class="mb-4">
                                    <label class="form-label">
                                        <strong>Rating <span class="text-danger">*</span></strong>
                                    </label>
                                    <div class="rating-input">
                                        <div class="star-rating" id="starRating">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                                <label for="rating{{ $i }}" class="star">
                                                    <i class="fa-solid fa-star"></i>
                                                </label>
                                            @endfor
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">Click on a star to rate (1 = Poor, 5 = Excellent)</small>
                                        </div>
                                    </div>
                                    @error('rating')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="comment" class="form-label">
                                        <strong>Your Review/Comment</strong>
                                        <span class="text-muted">(Optional but appreciated)</span>
                                    </label>
                                    <textarea 
                                        name="comment" 
                                        id="comment" 
                                        class="form-control @error('comment') is-invalid @enderror" 
                                        rows="8" 
                                        placeholder="Share your experience with this appointment. What did you like? What could be improved? Your feedback helps us provide better service..."
                                        maxlength="1000"
                                    >{{ old('comment') }}</textarea>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted">Maximum 1000 characters</small>
                                        <small class="text-muted" id="charCount">0 / 1000</small>
                                    </div>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info">
                                    <i class="fa-solid fa-info-circle me-2"></i>
                                    <strong>Note:</strong> Your feedback will be visible to the psychologist and may be used to improve services. Please be respectful and constructive in your comments.
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('patient.appointments.show', $appointment) }}" class="btn btn-light">
                                        <i class="fa-solid fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-paper-plane me-2"></i>Submit Feedback
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

    <style>
        .rating-input {
            margin-bottom: 20px;
        }
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
        }
        .star-rating input[type="radio"] {
            display: none;
        }
        .star-rating label {
            font-size: 32px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffc107;
        }
        .star-rating input[type="radio"]:checked ~ label {
            color: #ffc107;
        }
        .star-rating input[type="radio"]:checked ~ label,
        .star-rating input[type="radio"]:checked ~ label ~ label {
            color: #ffc107;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const commentTextarea = document.getElementById('comment');
            const charCount = document.getElementById('charCount');
            
            // Update character count
            function updateCharCount() {
                const length = commentTextarea.value.length;
                charCount.textContent = length + ' / 1000';
                if (length > 1000) {
                    charCount.classList.add('text-danger');
                } else {
                    charCount.classList.remove('text-danger');
                }
            }
            
            commentTextarea.addEventListener('input', updateCharCount);
            updateCharCount(); // Initial count
        });
    </script>
@endsection

