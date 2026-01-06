<?php $page = 'patient-history'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            Dashboard
        @endslot
        @slot('li_2')
            View History
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
                    <div class="dashboard-card w-100">
                        <div class="dashboard-card-head">
                            <div class="header-title">
                                <h5>
                                    <i class="fa-solid fa-history me-2 text-primary"></i>
                                    View History
                                </h5>
                                <p class="text-muted mb-0 small">Access past appointment records, payment history, and prescriptions anytime. Keep track of ongoing treatment and progress.</p>
                            </div>
                        </div>
                        <div class="dashboard-card-body">
                            <!-- Treatment Progress Statistics -->
                            <div class="row mb-4">
                                <div class="col-xl-3 col-sm-6 col-12 mb-3">
                                    <div class="dashboard-widget-box">
                                        <div class="dashboard-content-info">
                                            <h6>Total Sessions</h6>
                                            <h4>{{ $treatment_progress['total_sessions'] ?? 0 }}</h4>
                                        </div>
                                        <div class="dashboard-widget-icon">
                                            <span class="dash-icon-box"><i class="fa-solid fa-calendar-check text-primary"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 col-12 mb-3">
                                    <div class="dashboard-widget-box">
                                        <div class="dashboard-content-info">
                                            <h6>Total Prescriptions</h6>
                                            <h4>{{ $treatment_progress['total_prescriptions'] ?? 0 }}</h4>
                                        </div>
                                        <div class="dashboard-widget-icon">
                                            <span class="dash-icon-box"><i class="fa-solid fa-prescription-bottle text-info"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 col-12 mb-3">
                                    <div class="dashboard-widget-box">
                                        <div class="dashboard-content-info">
                                            <h6>Verified Payments</h6>
                                            <h4>{{ $treatment_progress['total_payments'] ?? 0 }}</h4>
                                        </div>
                                        <div class="dashboard-widget-icon">
                                            <span class="dash-icon-box"><i class="fa-solid fa-check-circle text-success"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 col-12 mb-3">
                                    <div class="dashboard-widget-box">
                                        <div class="dashboard-content-info">
                                            <h6>Total Spent</h6>
                                            <h4>${{ number_format($treatment_progress['total_spent'] ?? 0, 2) }}</h4>
                                        </div>
                                        <div class="dashboard-widget-icon">
                                            <span class="dash-icon-box"><i class="fa-solid fa-dollar-sign text-warning"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabs for different history types -->
                            <ul class="nav nav-tabs mb-4" id="historyTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="appointments-tab" data-bs-toggle="tab" data-bs-target="#appointments-history" type="button" role="tab">
                                        <i class="fa-solid fa-calendar me-2"></i>Appointments
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments-history" type="button" role="tab">
                                        <i class="fa-solid fa-credit-card me-2"></i>Payment History
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="prescriptions-tab" data-bs-toggle="tab" data-bs-target="#prescriptions-history" type="button" role="tab">
                                        <i class="fa-solid fa-prescription-bottle me-2"></i>Prescriptions
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="historyTabsContent">
                                <!-- Appointments History Tab -->
                                <div class="tab-pane fade show active" id="appointments-history" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table dashboard-table">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Psychologist</th>
                                                    <th>Specialization</th>
                                                    <th>Status</th>
                                                    <th>Payment</th>
                                                    <th>Prescription</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($history_appointments as $appointment)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $appointment->appointment_date->format('M d, Y') }}</strong><br>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $appointment->psychologist->user->profile_image ? asset('storage/' . $appointment->psychologist->user->profile_image) : asset('assets/index/doctor-profile-img.jpg') }}" 
                                                                 alt="Psychologist" 
                                                                 class="rounded-circle me-2" 
                                                                 style="width: 35px; height: 35px; object-fit: cover;">
                                                            <div>
                                                                <h6 class="mb-0">{{ $appointment->psychologist->user->name }}</h6>
                                                                <small class="text-muted">#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $appointment->psychologist->specialization }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                            {{ ucfirst($appointment->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($appointment->payment)
                                                            <span class="badge bg-{{ $appointment->payment->status === 'verified' ? 'success' : ($appointment->payment->status === 'rejected' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst(str_replace('_', ' ', $appointment->payment->status)) }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($appointment->prescription)
                                                            <span class="badge bg-info">
                                                                <i class="fa-solid fa-check me-1"></i>Yes
                                                            </span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="{{ route('patient.appointments.show', $appointment) }}" class="btn btn-sm btn-primary">
                                                            <i class="fa-solid fa-eye me-1"></i>View
                                                        </a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <i class="fa-solid fa-calendar text-muted" style="font-size: 48px;"></i>
                                                        <p class="text-muted mt-3 mb-0">No appointment history found</p>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($history_appointments->hasPages())
                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $history_appointments->links() }}
                                    </div>
                                    @endif
                                </div>

                                <!-- Payment History Tab -->
                                <div class="tab-pane fade" id="payments-history" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table dashboard-table">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Appointment</th>
                                                    <th>Psychologist</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Transaction ID</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($history_payments as $payment)
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
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <strong>${{ number_format($payment->amount, 2) }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $payment->status === 'verified' ? 'success' : ($payment->status === 'rejected' ? 'danger' : 'warning') }}">
                                                            {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ $payment->transaction_id }}</small>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="{{ route('patient.appointments.show', $payment->appointment) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fa-solid fa-eye me-1"></i>View
                                                        </a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <i class="fa-solid fa-credit-card text-muted" style="font-size: 48px;"></i>
                                                        <p class="text-muted mt-3 mb-0">No payment history found</p>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($history_payments->hasPages())
                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $history_payments->links() }}
                                    </div>
                                    @endif
                                </div>

                                <!-- Prescriptions History Tab -->
                                <div class="tab-pane fade" id="prescriptions-history" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table dashboard-table">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Psychologist</th>
                                                    <th>Appointment</th>
                                                    <th>Type</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($history_prescriptions as $prescription)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $prescription->created_at->format('M d, Y') }}</strong><br>
                                                        <small class="text-muted">{{ $prescription->created_at->format('h:i A') }}</small>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $prescription->appointment->psychologist->user->profile_image ? asset('storage/' . $prescription->appointment->psychologist->user->profile_image) : asset('assets/index/doctor-profile-img.jpg') }}" 
                                                                 alt="Psychologist" 
                                                                 class="rounded-circle me-2" 
                                                                 style="width: 35px; height: 35px; object-fit: cover;">
                                                            <div>
                                                                <h6 class="mb-0">{{ $prescription->appointment->psychologist->user->name }}</h6>
                                                                <small class="text-muted">{{ $prescription->appointment->psychologist->specialization }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span>#APT{{ str_pad($prescription->appointment->id, 4, '0', STR_PAD_LEFT) }}</span><br>
                                                        <small class="text-muted">{{ $prescription->appointment->appointment_date->format('M d, Y') }}</small>
                                                    </td>
                                                    <td>
                                                        @if($prescription->therapy_plan)
                                                            <span class="badge bg-info">Therapy Plan</span>
                                                        @endif
                                                        @if($prescription->notes)
                                                            <span class="badge bg-primary">Notes</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="{{ route('patient.prescriptions.show', $prescription) }}" class="btn btn-sm btn-primary">
                                                            <i class="fa-solid fa-eye me-1"></i>View Details
                                                        </a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4">
                                                        <i class="fa-solid fa-prescription-bottle text-muted" style="font-size: 48px;"></i>
                                                        <p class="text-muted mt-3 mb-0">No prescriptions found</p>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($history_prescriptions->hasPages())
                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $history_prescriptions->links() }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection

