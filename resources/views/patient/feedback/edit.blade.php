<?php $page = 'feedback'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            <a href="{{ route('patient.appointments.show', $feedback->appointment) }}">Appointment Details</a>
        @endslot
        @slot('li_2')
            Edit Feedback
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
                            <a href="{{ route('patient.appointments.show', $feedback->appointment) }}" class="back-arrow">
                                <i class="fa-solid fa-arrow-left"></i>
                            </a>
                            <h3>Edit Feedback</h3>
                        </div>
                        <p class="text-muted">Update your feedback and rating for this appointment.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

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
                                    <p><strong>Psychologist:</strong> {{ $feedback->appointment->psychologist->user->name }}</p>
                                    <p><strong>Specialization:</strong> {{ $feedback->appointment->psychologist->specialization }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Appointment Date:</strong> {{ $feedback->appointment->appointment_date->format('M d, Y') }}</p>
                                    <p><strong>Feedback Submitted:</strong> {{ $feedback->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Feedback -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Current Feedback</h5>
                        </div>
                        <div class="card-body">
                            <div class="rating mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="ms-2">{{ $feedback->rating }}/5</span>
                            </div>
                            @if($feedback->comment)
                                <p><strong>Comment:</strong></p>
                                <p class="text-muted">{{ $feedback->comment }}</p>
                            @else
                                <p class="text-muted">No comment provided.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Feedback Form -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Update Your Feedback</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('patient.feedback.update', $feedback) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-4">
                                    <label class="form-label">
                                        <strong>Rating <span class="text-danger">*</span></strong>
                                    </label>
                                    <div class="rating-input">
                                        <div class="star-rating" id="starRating">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" {{ old('rating', $feedback->rating) == $i ? 'checked' : '' }} required>
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
                                    >{{ old('comment', $feedback->comment) }}</textarea>
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
                                    <strong>Note:</strong> Your updated feedback will be visible to the psychologist and may be used to improve services. Please be respectful and constructive in your comments.
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('patient.appointments.show', $feedback->appointment) }}" class="btn btn-light">
                                        <i class="fa-solid fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-save me-2"></i>Update Feedback
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

