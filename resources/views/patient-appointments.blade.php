<?php $page = 'patient-appointments'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            Appointments
        @endslot
        @slot('li_2')
        Appointments
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
                        <h3>My Appointments</h3>
                        <ul class="header-list-btns">
                            <li>
                                <form method="GET" action="{{ route('patient.appointments.index') }}" class="d-inline">
                                    <div class="input-block dash-search-input">
                                        <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}">
                                        <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="appointment-tab-head">
                        <div class="appointment-tabs">
                            <ul class="nav nav-pills inner-tab " id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a href="{{ route('patient.appointments.index', ['status' => 'confirmed']) }}" class="nav-link {{ request('status') == 'confirmed' || !request('status') ? 'active' : '' }}" id="pills-upcoming-tab">
                                        Upcoming<span>{{ $stats['confirmed'] ?? 0 }}</span>
                                    </a>
                                </li>	
                                <li class="nav-item" role="presentation">
                                    <a href="{{ route('patient.appointments.index', ['status' => 'cancelled']) }}" class="nav-link {{ request('status') == 'cancelled' ? 'active' : '' }}" id="pills-cancel-tab">
                                        Cancelled<span>{{ $stats['cancelled'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="{{ route('patient.appointments.index', ['status' => 'completed']) }}" class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" id="pills-complete-tab">
                                        Completed<span>{{ $stats['completed'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="{{ route('patient.appointments.index', ['status' => 'pending']) }}" class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" id="pills-pending-tab">
                                        Pending<span>{{ $stats['pending'] ?? 0 }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content appointment-tab-content">
                        <div class="tab-pane fade show active" id="pills-upcoming" role="tabpanel" aria-labelledby="pills-upcoming-tab">
                            @forelse($appointments ?? [] as $appointment)
                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{ route('patient.appointments.show', $appointment) }}">
                                                @php
                                                    $profileImage = $appointment->psychologist->user->profile_image 
                                                        ? asset('storage/' . $appointment->psychologist->user->profile_image) 
                                                        : asset('assets/index/doctor-profile-img.jpg');
                                                @endphp
                                                <img src="{{ $profileImage }}" alt="Psychologist Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                <h6>
                                                    <a href="{{ route('patient.appointments.show', $appointment) }}">{{ $appointment->psychologist->user->name ?? 'N/A' }}</a>
                                                    @if($appointment->status === 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($appointment->status === 'confirmed')
                                                        <span class="badge bg-success">Confirmed</span>
                                                    @elseif($appointment->status === 'completed')
                                                        <span class="badge bg-info">Completed</span>
                                                    @elseif($appointment->status === 'cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @endif
                                                </h6>
                                                <p class="text-muted mb-0">
                                                    <i class="fa-solid fa-user-doctor me-1"></i>{{ $appointment->psychologist->specialization ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <div class="appointment-details-info">
                                            <p><i class="fa-solid fa-calendar-days me-2"></i><strong>Date:</strong> {{ $appointment->appointment_date->format('d M Y') }}</p>
                                            <p><i class="fa-solid fa-clock me-2"></i><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                            <p><i class="fa-solid fa-hourglass-half me-2"></i><strong>Duration:</strong> {{ $appointment->duration ?? 60 }} Minutes</p>
                                            <ul class="d-flex apponitment-types mt-2">
                                                <li>
                                                    <i class="fa-solid fa-video text-indigo me-1"></i>
                                                    {{ ucfirst($appointment->consultation_type ?? 'video') }} Call
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="mail-info-patient">
                                        <ul>
                                            <li><i class="fa-solid fa-envelope"></i>{{ $appointment->psychologist->user->email ?? 'N/A' }}</li>
                                            <li><i class="fa-solid fa-phone"></i>{{ $appointment->psychologist->user->phone ?? 'N/A' }}</li>
                                            <li>
                                                <i class="fa-solid fa-dollar-sign"></i>
                                                <strong>Fee:</strong> ${{ number_format($appointment->psychologist->consultation_fee ?? 0, 2) }}
                                            </li>
                                            @if($appointment->payment)
                                            <li>
                                                <i class="fa-solid fa-credit-card"></i>
                                                <strong>Payment:</strong> 
                                                @if($appointment->payment->status === 'verified')
                                                    <span class="badge bg-success">Verified</span>
                                                @elseif($appointment->payment->status === 'pending_verification')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($appointment->payment->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </li>
                                            @endif
                                        </ul>
                                    </li>
                                    <li class="appointment-action">
                                        <ul>
                                            <li>
                                                <a href="{{ route('patient.appointments.show', $appointment) }}" title="View Details">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </li>
                                            @if($appointment->status === 'pending' || $appointment->status === 'confirmed')
                                            <li>
                                                <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                                    @csrf
                                                    <button type="submit" class="border-0 bg-transparent text-danger" title="Cancel Appointment">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                        </ul>
                                    </li>
                                    @if($appointment->status === 'confirmed' && $appointment->appointment_date->isToday())
                                    <li class="appointment-start">
                                        <a href="{{ route('patient.session.join', $appointment) }}" class="start-link">
                                            <i class="fa-solid fa-video me-1"></i>Join Now
                                        </a>
                                    </li>
                                    @endif
                                    @if($appointment->status === 'pending' && !$appointment->payment)
                                    <li class="appointment-start">
                                        <a href="{{ route('patient.payment.create', $appointment) }}" class="start-link bg-warning">
                                            <i class="fa-solid fa-credit-card me-1"></i>Pay Now
                                        </a>
                                    </li>
                                    @endif
                                    @if($appointment->status === 'completed' && !$appointment->feedback)
                                    <li class="appointment-start">
                                        <a href="{{ route('patient.feedback.create', $appointment) }}" class="start-link bg-info">
                                            <i class="fa-solid fa-star me-1"></i>Give Feedback
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <!-- /Appointment List -->
                            @empty
                            <div class="text-center py-5">
                                <p>No appointments found</p>
                            </div>
                            @endforelse
                            
                            @if(isset($appointments) && $appointments->hasPages())
                            <div class="mt-4">
                                {{ $appointments->links() }}
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

