<?php $page = 'checkout'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            Checkout
        @endslot
        @slot('li_2')
            Checkout
        @endslot
    @endcomponent
    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <!-- Payment Receipt Upload Form -->
                            @if(isset($appointment))
                            <form action="{{ route('patient.payment.store', $appointment->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Appointment Information -->
                                <div class="info-widget">
                                    <h4 class="card-title">Appointment Details</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Psychologist:</strong> {{ $appointment->psychologist->user->name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Specialization:</strong> {{ $appointment->psychologist->specialization }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Date:</strong> {{ $appointment->appointment_date->format('F d, Y') }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Time:</strong> {{ $appointment->appointment_time }}</p>
                                        </div>
                                        <div class="col-md-12">
                                            <p><strong>Consultation Fee:</strong> <span class="text-primary fs-4">${{ number_format($appointment->psychologist->consultation_fee, 2) }}</span></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Appointment Information -->

                                <div class="payment-widget">
                                    <h4 class="card-title">Bank Transfer Payment</h4>
                                    <p class="text-muted">Please transfer the amount to our bank account and upload the receipt below.</p>

                                    <!-- Bank Transfer Details -->
                                    <div class="payment-list">
                                        <div class="mb-3 card-label">
                                            <label class="mb-2">Bank Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="bank_name" value="{{ old('bank_name') }}" required>
                                            @error('bank_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3 card-label">
                                            <label class="mb-2">Transaction ID <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="transaction_id" value="{{ old('transaction_id') }}" required>
                                            @error('transaction_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3 card-label">
                                            <label class="mb-2">Amount Paid <span class="text-danger">*</span></label>
                                            <input class="form-control" type="number" name="amount" step="0.01" value="{{ old('amount', $appointment->psychologist->consultation_fee) }}" required>
                                            @error('amount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3 card-label">
                                            <label class="mb-2">Upload Receipt <span class="text-danger">*</span></label>
                                            <input class="form-control" type="file" name="receipt" accept="image/*,application/pdf" required>
                                            <small class="text-muted">Accepted formats: JPG, PNG, PDF (Max: 5MB)</small>
                                            @error('receipt')
                                                <span class="text-danger d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- /Bank Transfer Details -->

                                    <!-- Terms Accept -->
                                    <div class="terms-accept">
                                        <div class="custom-checkbox">
                                            <input type="checkbox" id="terms_accept" required>
                                            <label for="terms_accept">I have read and accept <a href="{{url('terms-condition')}}">Terms &amp;
                                                    Conditions</a></label>
                                        </div>
                                    </div>
                                    <!-- /Terms Accept -->

                                    <!-- Submit Section -->
                                    <div class="submit-section mt-4">
                                        <button type="submit" class="btn btn-primary submit-btn">Confirm and Pay</button>
                                    </div>
                                    <!-- /Submit Section -->

                                </div>
                            </form>
                            <!-- /Checkout Form -->

                        </div>
                    </div>

                </div>

                <div class="col-md-5 col-lg-4 theiaStickySidebar">

                    <!-- Booking Summary -->
                    <div class="card booking-card">
                        <div class="card-header">
                            <h4 class="card-title">Booking Summary</h4>
                        </div>
                        <div class="card-body">

                            <!-- Booking Doctor Info -->
                            @if(isset($appointment))
                            <div class="booking-doc-info">
                                <a href="{{ route('patient.psychologist.show', $appointment->psychologist->id) }}" class="booking-doc-img">
                                    <img src="{{ asset('assets/img/doctors/doctor-thumb-02.jpg') }}" alt="User Image">
                                </a>
                                <div class="booking-info">
                                    <h4><a href="{{ route('patient.psychologist.show', $appointment->psychologist->id) }}">{{ $appointment->psychologist->user->name }}</a></h4>
                                    <div class="rating">
                                        @php
                                            $avgRating = $appointment->psychologist->feedbacks()->avg('rating') ?? 0;
                                            $totalReviews = $appointment->psychologist->feedbacks()->count();
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= round($avgRating) ? 'filled' : '' }}"></i>
                                        @endfor
                                        <span class="d-inline-block average-rating">{{ $totalReviews }}</span>
                                    </div>
                                    <div class="clinic-details">
                                        <p class="doc-location"><i class="fas fa-briefcase"></i> {{ $appointment->psychologist->specialization }}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Booking Doctor Info -->

                            <div class="booking-summary">
                                <div class="booking-item-wrap">
                                    <ul class="booking-date">
                                        <li>Date :<span>{{ $appointment->appointment_date->format('d M Y') }}</span></li>
                                        <li>Time :<span>{{ $appointment->appointment_time }}</span></li>
                                    </ul>
                                    <ul class="booking-fee">
                                        <li>Consultation Fee <span>${{ number_format($appointment->psychologist->consultation_fee, 2) }}</span></li>
                                        <li>Consultation Type <span>{{ ucfirst($appointment->consultation_type) }}</span></li>
                                    </ul>
                                    <div class="booking-total">
                                        <ul class="booking-total-list">
                                            <li>
                                                <span>Total</span>
                                                <span class="total-cost">${{ number_format($appointment->psychologist->consultation_fee, 2) }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info">
                                <p>No appointment details available.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    <!-- /Booking Summary -->

                </div>
            </div>

        </div>

    </div>
    <!-- /Page Content -->
@endsection
