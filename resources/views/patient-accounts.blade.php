<?php $page = 'patient-accounts'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            Accounts
        @endslot
        @slot('li_2')
            My Account
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
                        <h3>My Account</h3>
                        <p class="text-muted">View your account balance, transaction history, and payment summary.</p>
                    </div>

                    @php
                        $patient = Auth::user()->patient;
                        
                        // Get all payments for the patient
                        $allPayments = \App\Models\Payment::with(['appointment.psychologist.user'])
                            ->whereHas('appointment', function($query) use ($patient) {
                                $query->where('patient_id', $patient->id);
                            })
                            ->latest()
                            ->get();
                        
                        // Calculate account statistics
                        $totalPaid = $allPayments->where('status', 'verified')->sum('amount');
                        $pendingAmount = $allPayments->where('status', 'pending_verification')->sum('amount');
                        $rejectedAmount = $allPayments->where('status', 'rejected')->sum('amount');
                        $totalTransactions = $allPayments->count();
                        $verifiedTransactions = $allPayments->where('status', 'verified')->count();
                        $pendingTransactions = $allPayments->where('status', 'pending_verification')->count();
                    @endphp

                    <!-- Account Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-success">${{ number_format($totalPaid, 2) }}</h3>
                                    <p class="text-muted mb-0">Total Paid</p>
                                    <small class="text-success">{{ $verifiedTransactions }} verified payments</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-warning">${{ number_format($pendingAmount, 2) }}</h3>
                                    <p class="text-muted mb-0">Pending Verification</p>
                                    <small class="text-warning">{{ $pendingTransactions }} pending</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-primary">{{ $totalTransactions }}</h3>
                                    <p class="text-muted mb-0">Total Transactions</p>
                                    <small class="text-info">All time</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction History -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Transaction History</h5>
                            <a href="{{ route('patient-invoices') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-file-invoice me-1"></i>View All Invoices
                            </a>
                        </div>
                        <div class="card-body">
                            @if($allPayments && $allPayments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Transaction ID</th>
                                                <th>Appointment</th>
                                                <th>Psychologist</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Bank</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allPayments as $payment)
                                            <tr>
                                                <td>
                                                    <strong>{{ $payment->created_at->format('M d, Y') }}</strong><br>
                                                    <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    <small class="text-muted font-monospace">{{ $payment->transaction_id }}</small>
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
                                                             style="width: 30px; height: 30px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="mb-0 small">{{ $payment->appointment->psychologist->user->name }}</h6>
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
                                                    <small>{{ $payment->bank_name }}</small>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('patient.appointments.show', $payment->appointment) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fa-solid fa-eye me-1"></i>View
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-wallet text-muted" style="font-size: 64px;"></i>
                                    <h5 class="mt-3 mb-2">No Transactions Found</h5>
                                    <p class="text-muted mb-4">You haven't made any payments yet. Book an appointment to get started.</p>
                                    <a href="{{ route('patient.search') }}" class="btn btn-primary">
                                        <i class="fa-solid fa-search me-2"></i>Find a Psychologist
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Methods (Placeholder for future wallet functionality) -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fa-solid fa-credit-card me-2"></i>Payment Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <i class="fa-solid fa-info-circle me-2"></i>
                                <strong>Note:</strong> Payments are processed through bank transfers. Upload your payment receipt after making a bank transfer to complete your payment.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection

