<?php $page = 'doctor-appointment-details'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Psychologist
        @endslot
        @slot('li_1')
            <a href="{{ route('psychologist.appointments.index') }}">Appointments</a>
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
                    @component('components.sidebar_doctor')
                    @endcomponent
                </div>

                <div class="col-lg-8 col-xl-9">
                    <div class="dashboard-header">
                        <div class="header-back">
                            <a href="{{ route('psychologist.appointments.index') }}" class="back-arrow">
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
                                        <a href="#">
                                            @php
                                                $profileImage = $appointment->patient->user->profile_image 
                                                    ? asset('storage/' . $appointment->patient->user->profile_image) 
                                                    : asset('assets/img/patients/patient.jpg');
                                            @endphp
                                            <img src="{{ $profileImage }}" alt="Patient Image">
                                        </a>
                                        <div class="patient-info">
                                            <p>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</p>
                                            <h6>
                                                <a href="#">{{ $appointment->patient->user->name }}</a>
                                                @if($appointment->status === 'pending')
                                                    <span class="badge new-tag">New</span>
                                                @endif
                                            </h6>
                                            <div class="mail-info-patient">
                                                <ul>
                                                    <li><i class="fa-solid fa-envelope"></i>{{ $appointment->patient->user->email }}</li>
                                                    <li><i class="fa-solid fa-phone"></i>{{ $appointment->patient->user->phone ?? 'N/A' }}</li>
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
                                            <span class="badge bg-warning me-2">Pending</span>
                                        @elseif($appointment->status === 'confirmed')
                                            <span class="badge bg-success me-2">Confirmed</span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="badge bg-info me-2">Completed</span>
                                        @elseif($appointment->status === 'cancelled')
                                            <span class="badge bg-danger me-2">Cancelled</span>
                                        @endif
                                    </div>
                                    <div class="consult-fees">
                                        <h6>Consultation Fees: ${{ number_format($appointment->psychologist->consultation_fee, 2) }}</h6>
                                    </div>
                                    <ul>
                                        @if($appointment->status === 'pending')
                                        <li>
                                            <form action="{{ route('psychologist.appointments.confirm', $appointment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="border-0 bg-transparent text-success" title="Confirm">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                        @if($appointment->status === 'confirmed' && $appointment->appointment_date >= today())
                                        <li>
                                            <a href="{{ route('psychologist.session.start', $appointment) }}" class="border-0 bg-transparent text-primary" title="Start Session">
                                                <i class="fa-solid fa-video"></i>
                                            </a>
                                        </li>
                                        @endif
                                        @if(in_array($appointment->status, ['pending', 'confirmed']))
                                        <li>
                                            <button type="button" class="border-0 bg-transparent text-primary" data-bs-toggle="modal" data-bs-target="#rescheduleModal" title="Reschedule">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="border-0 bg-transparent text-danger" data-bs-toggle="modal" data-bs-target="#cancelModal" title="Cancel">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </li>
                                        @endif
                                    </ul>
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
                                @if($appointment->patient->user->address)
                                <li>
                                    <h6>Patient Location</h6>
                                    <span>{{ $appointment->patient->user->address }}</span>
                                </li>
                                @endif
                                @if($appointment->status === 'confirmed' && $appointment->appointment_date >= today())
                                <li>
                                    <div class="start-btn">
                                        <a href="{{ route('psychologist.session.start', $appointment) }}" class="btn btn-primary">
                                            <i class="fa-solid fa-video me-2"></i>Start Session
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
                                            @elseif($appointment->payment->status === 'pending_verification')
                                                <span class="badge bg-warning">Pending Verification</span>
                                            @elseif($appointment->payment->status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </p>
                                        <p><strong>Amount Paid:</strong> ${{ number_format($appointment->payment->amount, 2) }}</p>
                                        <p><strong>Transaction ID:</strong> {{ $appointment->payment->transaction_id }}</p>
                                        <p><strong>Bank Name:</strong> {{ $appointment->payment->bank_name }}</p>
                                        <p><strong>Uploaded At:</strong> {{ $appointment->payment->uploaded_at ? $appointment->payment->uploaded_at->format('d M Y h:i A') : 'N/A' }}</p>
                                        
                                        @if($appointment->payment->status === 'pending_verification')
                                        <div class="mt-3">
                                            <form action="{{ route('psychologist.payments.verify', $appointment->payment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this payment? This will confirm the appointment.');">
                                                    <i class="fa-solid fa-check me-2"></i>Approve Payment
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectPaymentModal">
                                                <i class="fa-solid fa-times me-2"></i>Reject Payment
                                            </button>
                                        </div>
                                        @endif
                                        
                                        @if($appointment->payment->status === 'rejected' && $appointment->payment->rejection_reason)
                                        <div class="alert alert-danger mt-3">
                                            <strong>Rejection Reason:</strong> {{ $appointment->payment->rejection_reason }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        @if($appointment->payment && $appointment->payment->receipt_file_path)
                                            <p><strong>Payment Receipt:</strong></p>
                                            @php
                                                $receiptPath = asset('storage/' . $appointment->payment->receipt_file_path);
                                                $fileExtension = strtolower(pathinfo($appointment->payment->receipt_file_path, PATHINFO_EXTENSION));
                                                $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                $fileExists = \Storage::disk('public')->exists($appointment->payment->receipt_file_path);
                                            @endphp
                                            
                                            @if(!$fileExists)
                                                <div class="alert alert-warning mb-3">
                                                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                                    Receipt file not found at: {{ $appointment->payment->receipt_file_path }}
                                                </div>
                                            @endif
                                            
                                            @if($isImage && $fileExists)
                                                <div class="mb-3">
                                                    <img src="{{ $receiptPath }}?t={{ time() }}" 
                                                         alt="Payment Receipt" 
                                                         class="img-fluid rounded border shadow-sm" 
                                                         style="max-height: 400px; cursor: pointer; width: 100%; object-fit: contain; background: #f8f9fa; display: block;" 
                                                         onclick="window.open('{{ $receiptPath }}', '_blank')"
                                                         onerror="this.onerror=null; this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';">
                                                    <div style="display:none;" class="alert alert-warning">
                                                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                                        Image could not be loaded. 
                                                        <a href="{{ $receiptPath }}" target="_blank" class="alert-link">Click here to try viewing directly</a>
                                                    </div>
                                                </div>
                                            @elseif($isImage)
                                                <div class="alert alert-info mb-3">
                                                    <i class="fa-solid fa-info-circle me-2"></i>
                                                    Image file exists but may not be accessible. Try the view button below.
                                                </div>
                                            @else
                                                <div class="mb-3">
                                                    <div class="alert alert-info d-flex align-items-center">
                                                        <i class="fa-solid fa-file-pdf me-2" style="font-size: 24px;"></i>
                                                        <div>
                                                            <strong>PDF Receipt</strong><br>
                                                            <small>Click the button below to view the receipt</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="d-flex gap-2">
                                                <a href="{{ $receiptPath }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fa-solid fa-eye me-1"></i>View Receipt
                                                </a>
                                                <a href="{{ $receiptPath }}" download class="btn btn-sm btn-secondary">
                                                    <i class="fa-solid fa-download me-1"></i>Download
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="card mt-4">
                            <div class="card-header bg-warning">
                                <h5 class="mb-0 text-white">Payment Not Uploaded</h5>
                            </div>
                            <div class="card-body">
                                <p>The patient has not uploaded the payment receipt yet.</p>
                            </div>
                        </div>
                        @endif

                        <!-- Prescription Information -->
                        @if($appointment->prescription)
                        <div class="card mt-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Prescription & Therapy Notes</h5>
                                <div>
                                    <a href="{{ route('psychologist.prescriptions.edit', $appointment->prescription) }}" class="btn btn-sm btn-primary">
                                        <i class="fa-solid fa-edit me-1"></i>Edit Prescription
                                    </a>
                                </div>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($appointment->status === 'completed')
                        <div class="card mt-4">
                            <div class="card-header bg-info">
                                <h5 class="mb-0 text-white">Create Prescription</h5>
                            </div>
                            <div class="card-body">
                                <p>Create a prescription or therapy notes for this completed appointment.</p>
                                <a href="{{ route('psychologist.prescriptions.create', $appointment) }}" class="btn btn-primary">
                                    <i class="fa-solid fa-prescription-bottle me-2"></i>Create Prescription
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Feedback Information -->
                        @if($appointment->feedback)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Patient Feedback</h5>
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
                                <p class="text-muted small mb-0">Submitted on {{ $appointment->feedback->created_at->format('d M Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Session Notes and Diagnosis -->
                        @if($appointment->session_notes || $appointment->diagnosis || $appointment->observations || $appointment->follow_up_recommendations)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Session Notes & Diagnosis</h5>
                            </div>
                            <div class="card-body">
                                @if($appointment->session_notes)
                                <div class="mb-4">
                                    <h6 class="text-muted mb-2">Session Notes</h6>
                                    <div class="alert alert-light">
                                        {!! nl2br(e($appointment->session_notes)) !!}
                                    </div>
                                </div>
                                @endif
                                
                                @if($appointment->diagnosis)
                                <div class="mb-4">
                                    <h6 class="text-muted mb-2">Diagnosis</h6>
                                    <div class="alert alert-info">
                                        {!! nl2br(e($appointment->diagnosis)) !!}
                                    </div>
                                </div>
                                @endif
                                
                                @if($appointment->observations)
                                <div class="mb-4">
                                    <h6 class="text-muted mb-2">Clinical Observations</h6>
                                    <div class="alert alert-light">
                                        {!! nl2br(e($appointment->observations)) !!}
                                    </div>
                                </div>
                                @endif
                                
                                @if($appointment->follow_up_recommendations)
                                <div class="mb-4">
                                    <h6 class="text-muted mb-2">Follow-up Recommendations</h6>
                                    <div class="alert alert-warning">
                                        {!! nl2br(e($appointment->follow_up_recommendations)) !!}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Notes -->
                        @if($appointment->notes)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">General Notes</h5>
                            </div>
                            <div class="card-body">
                                <p>{!! nl2br(e($appointment->notes)) !!}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->

    <!-- Reschedule Modal -->
    @if(in_array($appointment->status, ['pending', 'confirmed']))
    <div class="modal fade" id="rescheduleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('psychologist.appointments.reschedule', $appointment) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reschedule Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info mb-3">
                            <strong>Current Schedule:</strong><br>
                            Date: {{ $appointment->appointment_date->format('d M Y') }}<br>
                            Time: {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">New Appointment Date <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" class="form-control" value="{{ $appointment->appointment_date->format('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">New Appointment Time <span class="text-danger">*</span></label>
                            <input type="time" name="appointment_time" class="form-control" value="{{ $appointment->appointment_time }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Reason for Rescheduling (Optional)</label>
                            <textarea name="reschedule_reason" class="form-control" rows="3" placeholder="Enter reason for rescheduling..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Reschedule Appointment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('psychologist.appointments.cancel', $appointment) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to cancel this appointment?</p>
                        <div class="alert alert-warning mb-3">
                            <strong>Patient:</strong> {{ $appointment->patient->user->name }}<br>
                            <strong>Date:</strong> {{ $appointment->appointment_date->format('d M Y') }}<br>
                            <strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Cancellation Reason (Optional)</label>
                            <textarea name="cancellation_reason" class="form-control" rows="3" placeholder="Enter reason for cancellation..."></textarea>
                        </div>
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

    <!-- Reject Payment Modal -->
    @if($appointment->payment && $appointment->payment->status === 'pending_verification')
    <div class="modal fade" id="rejectPaymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('psychologist.payments.reject', $appointment->payment) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to reject this payment?</p>
                        <div class="alert alert-warning mb-3">
                            <strong>Payment Details:</strong><br>
                            Amount: ${{ number_format($appointment->payment->amount, 2) }}<br>
                            Transaction ID: {{ $appointment->payment->transaction_id }}<br>
                            Bank: {{ $appointment->payment->bank_name }}
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Enter reason for rejecting this payment..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection
