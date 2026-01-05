<?php $page = 'earnings'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Psychologist
        @endslot
        @slot('li_1')
            Earnings & Payment History
        @endslot
        @slot('li_2')
            Earnings Dashboard
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
                        <h3>Earnings & Payment History</h3>
                        <p class="text-muted">View detailed earning reports and payment confirmations. Track income generated through consultation sessions.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Earnings Statistics -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="dashboard-widget-box">
                                <div class="dashboard-content-info">
                                    <h6>Total Earnings</h6>
                                    <h4>${{ number_format($stats['total_earnings'] ?? 0, 2) }}</h4>
                                </div>
                                <div class="dashboard-widget-icon">
                                    <span class="dash-icon-box"><i class="fa-solid fa-dollar-sign"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="dashboard-widget-box">
                                <div class="dashboard-content-info">
                                    <h6>This Month</h6>
                                    <h4>${{ number_format($stats['this_month_earnings'] ?? 0, 2) }}</h4>
                                </div>
                                <div class="dashboard-widget-icon">
                                    <span class="dash-icon-box"><i class="fa-solid fa-calendar-month"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="dashboard-widget-box">
                                <div class="dashboard-content-info">
                                    <h6>Pending Earnings</h6>
                                    <h4>${{ number_format($stats['pending_earnings'] ?? 0, 2) }}</h4>
                                </div>
                                <div class="dashboard-widget-icon">
                                    <span class="dash-icon-box"><i class="fa-solid fa-clock"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="dashboard-widget-box">
                                <div class="dashboard-content-info">
                                    <h6>Total Sessions</h6>
                                    <h4>{{ $stats['total_sessions'] ?? 0 }}</h4>
                                </div>
                                <div class="dashboard-widget-icon">
                                    <span class="dash-icon-box"><i class="fa-solid fa-video"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" action="{{ route('psychologist.earnings.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <select class="form-control" name="status">
                                        <option value="">All Payment Status</option>
                                        <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Payment History -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Payment History</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Payment ID</th>
                                            <th>Patient</th>
                                            <th>Appointment Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Payment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments ?? [] as $payment)
                                        <tr>
                                            <td>#PAY{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $payment->appointment->patient->user->profile_image ? asset('storage/' . $payment->appointment->patient->user->profile_image) : asset('assets/img/patients/patient.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $payment->appointment->patient->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>
                                                {{ $payment->appointment->appointment_date->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($payment->appointment->appointment_time)->format('h:i A') }}</small>
                                            </td>
                                            <td><strong>${{ number_format($payment->amount, 2) }}</strong></td>
                                            <td>
                                                @if($payment->status === 'verified')
                                                    <span class="badge bg-success">Verified</span>
                                                @elseif($payment->status === 'pending_verification')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($payment->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $payment->status)) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $payment->created_at->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fa-solid fa-dollar-sign text-muted" style="font-size: 48px;"></i>
                                                <p class="text-muted mt-3 mb-0">No payments found</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection

