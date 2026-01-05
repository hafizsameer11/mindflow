<?php $page = 'appointment-details'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Appointment Details</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('admin/appointment-list') }}">Appointments</a></li>
                            <li class="breadcrumb-item active">Appointment Details</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Appointment Information -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Appointment Information</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th>Appointment ID:</th>
                                                <td>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Date:</th>
                                                <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Time:</th>
                                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Duration:</th>
                                                <td>{{ $appointment->duration }} minutes</td>
                                            </tr>
                                            <tr>
                                                <th>Status:</th>
                                                <td>
                                                    @if($appointment->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($appointment->status == 'confirmed')
                                                        <span class="badge bg-success">Confirmed</span>
                                                    @elseif($appointment->status == 'completed')
                                                        <span class="badge bg-info">Completed</span>
                                                    @elseif($appointment->status == 'cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($appointment->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Consultation Type:</th>
                                                <td>{{ ucfirst($appointment->consultation_type ?? 'N/A') }}</td>
                                            </tr>
                                            @if($appointment->meeting_link)
                                            <tr>
                                                <th>Meeting Link:</th>
                                                <td>
                                                    <a href="{{ $appointment->meeting_link }}" target="_blank" class="text-primary">
                                                        {{ $appointment->meeting_link }}
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif
                                            @if($appointment->notes)
                                            <tr>
                                                <th>Notes:</th>
                                                <td>{{ $appointment->notes }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Created At:</th>
                                                <td>{{ $appointment->created_at->format('M d, Y h:i A') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3">Patient Information</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th>Patient ID:</th>
                                                <td>#PAT{{ str_pad($appointment->patient->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Name:</th>
                                                <td>
                                                    <h2 class="table-avatar mb-0">
                                                        <a href="#" class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle" src="{{ asset('assets_admin/img/patients/patient.jpg') }}" alt="User Image">
                                                        </a>
                                                        <a href="#">{{ $appointment->patient->user->name }}</a>
                                                    </h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Email:</th>
                                                <td>{{ $appointment->patient->user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone:</th>
                                                <td>{{ $appointment->patient->user->phone ?? 'N/A' }}</td>
                                            </tr>
                                            @if($appointment->patient->user->date_of_birth)
                                            <tr>
                                                <th>Age:</th>
                                                <td>{{ \Carbon\Carbon::parse($appointment->patient->user->date_of_birth)->age }} Years</td>
                                            </tr>
                                            @endif
                                            @if($appointment->patient->user->address)
                                            <tr>
                                                <th>Address:</th>
                                                <td>{{ $appointment->patient->user->address }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Psychologist Information -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3">Psychologist Information</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th>Psychologist ID:</th>
                                                <td>#PSY{{ str_pad($appointment->psychologist->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Name:</th>
                                                <td>
                                                    <h2 class="table-avatar mb-0">
                                                        <a href="{{ route('admin.psychologists.show', $appointment->psychologist->id) }}" class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle" src="{{ asset('assets_admin/img/doctors/doctor-thumb-01.jpg') }}" alt="User Image">
                                                        </a>
                                                        <a href="{{ route('admin.psychologists.show', $appointment->psychologist->id) }}">{{ $appointment->psychologist->user->name }}</a>
                                                    </h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Email:</th>
                                                <td>{{ $appointment->psychologist->user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Specialization:</th>
                                                <td>{{ $appointment->psychologist->specialization }}</td>
                                            </tr>
                                            <tr>
                                                <th>Experience:</th>
                                                <td>{{ $appointment->psychologist->experience_years ?? 'N/A' }} Years</td>
                                            </tr>
                                            <tr>
                                                <th>Consultation Fee:</th>
                                                <td><strong class="text-primary">${{ number_format($appointment->psychologist->consultation_fee, 2) }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Payment Information -->
                            @if($appointment->payment)
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3">Payment Information</h5>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th>Payment ID:</th>
                                                                <td>#PAY{{ str_pad($appointment->payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Amount:</th>
                                                                <td><strong class="text-primary">${{ number_format($appointment->payment->amount, 2) }}</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Status:</th>
                                                                <td>
                                                                    @if($appointment->payment->status == 'verified')
                                                                        <span class="badge bg-success">Verified</span>
                                                                    @elseif($appointment->payment->status == 'pending_verification')
                                                                        <span class="badge bg-warning">Pending Verification</span>
                                                                    @elseif($appointment->payment->status == 'rejected')
                                                                        <span class="badge bg-danger">Rejected</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $appointment->payment->status)) }}</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @if($appointment->payment->bank_name)
                                                            <tr>
                                                                <th>Bank Name:</th>
                                                                <td>{{ $appointment->payment->bank_name }}</td>
                                                            </tr>
                                                            @endif
                                                            @if($appointment->payment->transaction_id)
                                                            <tr>
                                                                <th>Transaction ID:</th>
                                                                <td>{{ $appointment->payment->transaction_id }}</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <a href="{{ route('admin.payments.show', $appointment->payment->id) }}" class="btn btn-primary">
                                                        <i class="fe fe-eye"></i> View Payment Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <i class="fe fe-info"></i> No payment information available for this appointment.
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Prescription Information -->
                            @if($appointment->prescription)
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3">Prescription Information</h5>
                                    <div class="card">
                                        <div class="card-body">
                                            @if($appointment->prescription->therapy_plan)
                                            <div class="mb-3">
                                                <strong>Therapy Plan:</strong>
                                                <p class="mt-2">{{ $appointment->prescription->therapy_plan }}</p>
                                            </div>
                                            @endif
                                            @if($appointment->prescription->notes)
                                            <div>
                                                <strong>Notes:</strong>
                                                <p class="mt-2">{{ $appointment->prescription->notes }}</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Feedback Information -->
                            @if($appointment->feedback)
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3">Feedback</h5>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <strong>Rating:</strong>
                                                <div class="mt-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $appointment->feedback->rating)
                                                            <i class="fe fe-star text-warning"></i>
                                                        @else
                                                            <i class="fe fe-star-o text-secondary"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="ms-2">({{ $appointment->feedback->rating }}/5)</span>
                                                </div>
                                            </div>
                                            @if($appointment->feedback->comment)
                                            <div>
                                                <strong>Comment:</strong>
                                                <p class="mt-2">{{ $appointment->feedback->comment }}</p>
                                            </div>
                                            @endif
                                            <div class="mt-3">
                                                <small class="text-muted">Submitted on {{ $appointment->feedback->created_at->format('M d, Y h:i A') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Actions -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2">
                                        <a href="{{ url('admin/appointment-list') }}" class="btn btn-secondary">
                                            <i class="fe fe-arrow-left"></i> Back to Appointments
                                        </a>
                                        @if($appointment->payment)
                                        <a href="{{ route('admin.payments.show', $appointment->payment->id) }}" class="btn btn-info">
                                            <i class="fe fe-dollar-sign"></i> View Payment
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->
@endsection

