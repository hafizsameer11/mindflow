<?php $page = 'patient-invoices'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            Invoices
        @endslot
        @slot('li_2')
            My Invoices
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
                        <h3>My Invoices</h3>
                        <p class="text-muted">View all your payment invoices and receipts in one place.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @php
                        $patient = Auth::user()->patient;
                        $totalPaid = $payments->where('status', 'verified')->sum('amount');
                        $pendingPayments = $payments->where('status', 'pending_verification')->count();
                        $verifiedPayments = $payments->where('status', 'verified')->count();
                    @endphp

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-primary">${{ number_format($totalPaid, 2) }}</h3>
                                    <p class="text-muted mb-0">Total Paid</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-success">{{ $verifiedPayments }}</h3>
                                    <p class="text-muted mb-0">Verified Payments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-warning">{{ $pendingPayments }}</h3>
                                    <p class="text-muted mb-0">Pending Verification</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoices Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Payment History</h5>
                        </div>
                        <div class="card-body">
                            @if($payments && $payments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Appointment</th>
                                                <th>Psychologist</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Transaction ID</th>
                                                <th>Bank</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($payments as $payment)
                                            <tr>
                                                <td>
                                                    <strong>{{ $payment->created_at->format('M d, Y') }}</strong><br>
                                                    <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    <span>#APT{{ str_pad($payment->appointment->id, 4, '0', STR_PAD_LEFT) }}</span><br>
                                                    <small class="text-muted">{{ $payment->appointment->appointment_date->format('M d, Y') }}</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $payment->appointment->psychologist->user->profile_image ? asset('storage/' . $payment->appointment->psychologist->user->profile_image) : asset('assets/index/doctor-profile-img.jpg') }}" 
                                                             alt="Psychologist" 
                                                             class="rounded-circle me-2" 
                                                             style="width: 35px; height: 35px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="mb-0">{{ $payment->appointment->psychologist->user->name }}</h6>
                                                            <small class="text-muted">{{ $payment->appointment->psychologist->specialization }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <strong>${{ number_format($payment->amount, 2) }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $payment->status === 'verified' ? 'success' : ($payment->status === 'rejected' ? 'danger' : ($payment->status === 'pending_verification' ? 'warning' : 'secondary')) }}">
                                                        {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $payment->transaction_id }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $payment->bank_name }}</small>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('patient.appointments.show', $payment->appointment) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fa-solid fa-eye me-1"></i>View Details
                                                    </a>
                                                    @if($payment->receipt_file_path)
                                                        <a href="{{ route('patient.payments.receipt', $payment) }}" class="btn btn-sm btn-outline-info">
                                                            <i class="fa-solid fa-download me-1"></i>Receipt
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-file-invoice text-muted" style="font-size: 64px;"></i>
                                    <h5 class="mt-3 mb-2">No Invoices Found</h5>
                                    <p class="text-muted mb-4">You haven't made any payments yet. Book an appointment to get started.</p>
                                    <a href="{{ route('patient.search') }}" class="btn btn-primary">
                                        <i class="fa-solid fa-search me-2"></i>Find a Psychologist
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection

