<?php $page = 'patient-appointment-details'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            <a href="{{ route('patient.appointments.index') }}">My Appointments</a>
        @endslot
        @slot('li_2')
            Appointment Details
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
                            <a href="{{ route('patient.appointments.index') }}" class="back-arrow">
                                <i class="fa-solid fa-arrow-left"></i>
                            </a>
                            <h3>Appointment Details</h3>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="appointment-details-wrap">
                        <!-- Appointment Detail Card -->
                        <div class="appointment-wrap appointment-detail-card">
                            <ul>
                                <li>
                                    <div class="patinet-information">
                                        <a href="{{ route('patient.psychologist.show', $appointment->psychologist) }}">
                                            @php
                                                $profileImage = $appointment->psychologist->user->profile_image 
                                                    ? asset('storage/' . $appointment->psychologist->user->profile_image) 
                                                    : asset('assets/img/doctors/doc-profile-02.jpg');
                                            @endphp
                                            <img src="{{ $profileImage }}" alt="Psychologist Image">
                                        </a>
                                        <div class="patient-info">
                                            <p>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</p>
                                            <h6>
                                                <a href="{{ route('patient.psychologist.show', $appointment->psychologist) }}">
                                                    {{ $appointment->psychologist->user->name }}
                                                </a>
                                            </h6>
                                            <div class="mail-info-patient">
                                                <ul>
                                                    <li><i class="fa-solid fa-envelope"></i>{{ $appointment->psychologist->user->email }}</li>
                                                    <li><i class="fa-solid fa-phone"></i>{{ $appointment->psychologist->user->phone ?? 'N/A' }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="appointment-info">
                                    <div class="person-info">
                                        <p>Type of Appointment</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>
                                                <i class="fa-solid fa-video text-indigo"></i>
                                                {{ ucfirst($appointment->consultation_type ?? 'video') }} Call
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="appointment-action">
                                    <div class="detail-badge-info">
                                        @if($appointment->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($appointment->status === 'confirmed')
                                            <span class="badge bg-success">Confirmed</span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="badge bg-info">Completed</span>
                                        @elseif($appointment->status === 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </div>
                                    <div class="consult-fees">
                                        <h6>Consultation Fees: ${{ number_format($appointment->psychologist->consultation_fee, 2) }}</h6>
                                    </div>
                                </li>
                            </ul>
                            <ul class="detail-card-bottom-info">
                                <li>
                                    <h6>Appointment Date & Time</h6>
                                    <span>{{ $appointment->appointment_date->format('d M Y') }} - {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
                                </li>
                                <li>
                                    <h6>Duration</h6>
                                    <span>{{ $appointment->duration }} Minutes</span>
                                </li>
                                <li>
                                    <h6>Specialization</h6>
                                    <span>{{ $appointment->psychologist->specialization }}</span>
                                </li>
                                <li>
                                    <h6>Experience</h6>
                                    <span>{{ $appointment->psychologist->experience_years }} Years</span>
                                </li>
                                @if($appointment->status === 'confirmed' && $appointment->appointment_date == today())
                                <li>
                                    <div class="start-btn">
                                        <a href="{{ route('patient.session.join', $appointment) }}" class="btn btn-primary">
                                            <i class="fa-solid fa-video me-2"></i>Join Session
                                        </a>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                        <!-- /Appointment Detail Card -->

                        <!-- Payment Information -->
                        @if($appointment->payment)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Payment Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Payment Status:</strong> 
                                            @if($appointment->payment->status === 'verified')
                                                <span class="badge bg-success">Verified</span>
                                            @elseif($appointment->payment->status === 'pending')
                                                <span class="badge bg-warning">Pending Verification</span>
                                            @elseif($appointment->payment->status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </p>
                                        <p><strong>Amount Paid:</strong> ${{ number_format($appointment->payment->amount, 2) }}</p>
                                        <p><strong>Transaction ID:</strong> {{ $appointment->payment->transaction_id }}</p>
                                        <p><strong>Bank Name:</strong> {{ $appointment->payment->bank_name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        @if($appointment->payment->receipt_file_path)
                                            <p><strong>Receipt:</strong></p>
                                            <a href="{{ asset('storage/' . $appointment->payment->receipt_file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fa-solid fa-eye me-1"></i>View Receipt
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                @if($appointment->payment->status === 'verified' && $appointment->payment->refund_status === 'none')
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#refundModal{{ $appointment->payment->id }}">
                                            <i class="fa-solid fa-money-bill-transfer me-2"></i>Request Refund
                                        </button>
                                    </div>
                                </div>
                                @elseif($appointment->payment->refund_status === 'requested')
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="fa-solid fa-info-circle me-2"></i>
                                            <strong>Refund Requested:</strong> Your refund request is under review. We will notify you once a decision is made.
                                        </div>
                                    </div>
                                </div>
                                @elseif($appointment->payment->refund_status === 'approved')
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-success">
                                            <i class="fa-solid fa-check-circle me-2"></i>
                                            <strong>Refund Approved:</strong> Your refund request has been approved and is being processed.
                                        </div>
                                    </div>
                                </div>
                                @elseif($appointment->payment->refund_status === 'processed')
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-success">
                                            <i class="fa-solid fa-check-circle me-2"></i>
                                            <strong>Refund Processed:</strong> Your refund has been processed successfully.
                                        </div>
                                    </div>
                                </div>
                                @elseif($appointment->payment->refund_status === 'rejected')
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-danger">
                                            <i class="fa-solid fa-times-circle me-2"></i>
                                            <strong>Refund Rejected:</strong> Your refund request has been rejected.
                                            @if($appointment->payment->refund_notes)
                                                <br><small>Reason: {{ $appointment->payment->refund_notes }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @elseif($appointment->status !== 'cancelled')
                        <div class="card mt-4">
                            <div class="card-header bg-warning">
                                <h5 class="mb-0 text-white">Payment Required</h5>
                            </div>
                            <div class="card-body">
                                <p>Please upload your payment receipt to confirm your appointment.</p>
                                <a href="{{ route('patient.payment.create', $appointment) }}" class="btn btn-primary">
                                    <i class="fa-solid fa-upload me-2"></i>Upload Payment Receipt
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Prescription Information -->
                        @if($appointment->prescription)
                        <div class="card mt-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Prescription & Therapy Notes</h5>
                                <a href="{{ route('patient.prescriptions.show', $appointment->prescription) }}" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-eye me-1"></i>View Full Details
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p><strong>Prescribed Date:</strong> {{ $appointment->prescription->created_at->format('d M Y h:i A') }}</p>
                                        
                                        @if($appointment->prescription->therapy_plan)
                                            <div class="mb-3">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fa-solid fa-heart-pulse me-2 text-primary"></i>Therapy Plan
                                                </h6>
                                                <div class="alert alert-info">
                                                    {!! nl2br(e($appointment->prescription->therapy_plan)) !!}
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($appointment->prescription->notes)
                                            <div class="mb-3">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fa-solid fa-note-sticky me-2 text-info"></i>Prescription Notes
                                                </h6>
                                                <div class="alert alert-light">
                                                    {!! nl2br(e($appointment->prescription->notes)) !!}
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-3">
                                            <a href="{{ route('patient.prescriptions.show', $appointment->prescription) }}" class="btn btn-primary">
                                                <i class="fa-solid fa-file-prescription me-2"></i>View Complete Prescription
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($appointment->status === 'completed')
                        <div class="card mt-4">
                            <div class="card-header bg-info">
                                <h5 class="mb-0 text-white">No Prescription Yet</h5>
                            </div>
                            <div class="card-body">
                                <p>Your psychologist has not created a prescription for this appointment yet. Prescriptions will appear here once they are assigned.</p>
                            </div>
                        </div>
                        @endif

                        <!-- Feedback Information -->
                        @if($appointment->feedback)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Your Feedback</h5>
                            </div>
                            <div class="card-body">
                                <div class="rating mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $appointment->feedback->rating ? 'filled' : '' }}"></i>
                                    @endfor
                                    <span class="ms-2">{{ $appointment->feedback->rating }}/5</span>
                                </div>
                                @if($appointment->feedback->comment)
                                    <p><strong>Comment:</strong></p>
                                    <p>{{ $appointment->feedback->comment }}</p>
                                @endif
                                <div class="mt-3">
                                    <a href="{{ route('patient.feedback.edit', $appointment->feedback) }}" class="btn btn-sm btn-primary">
                                        <i class="fa-solid fa-edit me-1"></i>Edit Feedback
                                    </a>
                                </div>
                            </div>
                        </div>
                        @elseif($appointment->status === 'completed')
                        <div class="card mt-4">
                            <div class="card-header bg-info">
                                <h5 class="mb-0 text-white">Provide Feedback</h5>
                            </div>
                            <div class="card-body">
                                <p>Help us improve by sharing your experience with this appointment.</p>
                                <a href="{{ route('patient.feedback.create', $appointment) }}" class="btn btn-primary">
                                    <i class="fa-solid fa-star me-2"></i>Write Feedback
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Actions -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-2">
                                    @if($appointment->status === 'pending' && !$appointment->payment)
                                        <a href="{{ route('patient.payment.create', $appointment) }}" class="btn btn-primary">
                                            <i class="fa-solid fa-credit-card me-2"></i>Make Payment
                                        </a>
                                    @endif
                                    
                                    @if($appointment->status === 'confirmed' && $appointment->appointment_date >= today())
                                        @if($appointment->meeting_link)
                                            <a href="{{ route('patient.session.join', $appointment) }}" class="btn btn-success">
                                                <i class="fa-solid fa-video me-2"></i>Join Session
                                            </a>
                                        @endif
                                    @endif
                                    
                                    @if(in_array($appointment->status, ['pending', 'confirmed']) && $appointment->status !== 'cancelled')
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                            <i class="fa-solid fa-times me-2"></i>Cancel Appointment
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('patient.psychologist.show', $appointment->psychologist) }}" class="btn btn-secondary">
                                        <i class="fa-solid fa-user-doctor me-2"></i>View Psychologist Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->

    <!-- Cancel Appointment Modal -->
    @if(in_array($appointment->status, ['pending', 'confirmed']))
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to cancel this appointment?</p>
                        <div class="alert alert-warning">
                            <strong>Appointment Details:</strong><br>
                            Date: {{ $appointment->appointment_date->format('d M Y') }}<br>
                            Time: {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}<br>
                            Psychologist: {{ $appointment->psychologist->user->name }}
                        </div>
                        <p class="text-muted">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Cancel Appointment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Refund Request Modal -->
    @if($appointment->payment && $appointment->payment->status === 'verified' && $appointment->payment->refund_status === 'none')
    <div class="modal fade" id="refundModal{{ $appointment->payment->id }}" tabindex="-1" aria-labelledby="refundModalLabel{{ $appointment->payment->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('patient.payments.request-refund', $appointment->payment) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="refundModalLabel{{ $appointment->payment->id }}">Request Refund</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            Request a refund for this payment. The refund will need to be reviewed and approved before processing.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Original Payment Amount:</label>
                            <div class="form-control-plaintext"><strong>${{ number_format($appointment->payment->amount, 2) }}</strong></div>
                        </div>
                        <div class="mb-3">
                            <label for="refund_amount{{ $appointment->payment->id }}" class="form-label">Refund Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       class="form-control" 
                                       id="refund_amount{{ $appointment->payment->id }}" 
                                       name="refund_amount" 
                                       step="0.01" 
                                       min="0" 
                                       max="{{ $appointment->payment->amount }}" 
                                       value="{{ $appointment->payment->amount }}">
                            </div>
                            <small class="text-muted">Maximum refundable amount: ${{ number_format($appointment->payment->amount, 2) }}</small>
                        </div>
                        <div class="mb-3">
                            <label for="refund_reason{{ $appointment->payment->id }}" class="form-label">Reason for Refund <span class="text-danger">*</span></label>
                            <textarea class="form-control" 
                                      id="refund_reason{{ $appointment->payment->id }}" 
                                      name="refund_reason" 
                                      rows="4" 
                                      placeholder="Please provide a detailed reason for your refund request (minimum 10 characters)" 
                                      required 
                                      minlength="10">{{ old('refund_reason') }}</textarea>
                            @error('refund_reason')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fa-solid fa-money-bill-transfer me-2"></i>Submit Refund Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection

