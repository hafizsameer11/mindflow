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
                                                <img src="{{ asset('assets/index/doctor-profile-img.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                <h6><a href="{{ route('patient.appointments.show', $appointment) }}">{{ $appointment->psychologist->user->name ?? 'N/A' }}</a>
                                                    @if($appointment->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                    @elseif($appointment->status === 'confirmed')
                                                    <span class="badge bg-success">Confirmed</span>
                                                    @endif
                                                </h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>{{ $appointment->appointment_date->format('d M Y') }} {{ $appointment->appointment_time ?? '' }}</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>{{ $appointment->psychologist->specialization ?? 'N/A' }}</li>
                                            <li>{{ ucfirst($appointment->consultation_type ?? 'video') }} Call</li>
                                        </ul>
                                    </li>
                                    <li class="mail-info-patient">
                                        <ul>
                                            <li><i class="fa-solid fa-envelope"></i>{{ $appointment->psychologist->user->email ?? 'N/A' }}</li>
                                            <li><i class="fa-solid fa-phone"></i>{{ $appointment->psychologist->user->phone ?? 'N/A' }}</li>
                                        </ul>
                                    </li>
                                    <li class="appointment-action">
                                        <ul>
                                            <li>
                                                <a href="{{ route('patient.appointments.show', $appointment) }}"><i class="fa-solid fa-eye"></i></a>
                                            </li>
                                            @if($appointment->status === 'pending' || $appointment->status === 'confirmed')
                                            <li>
                                                <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                                    @csrf
                                                    <button type="submit" class="border-0 bg-transparent text-danger"><i class="fa-solid fa-xmark"></i></button>
                                                </form>
                                            </li>
                                            @endif
                                        </ul>
                                    </li>
                                    @if($appointment->status === 'confirmed' && $appointment->appointment_date->isToday())
                                    <li class="appointment-start">
                                        <a href="{{ route('patient.session.join', $appointment) }}" class="start-link">Join Now</a>
                                    </li>
                                    @endif
                                    @if($appointment->status === 'pending' && !$appointment->payment)
                                    <li class="appointment-start">
                                        <a href="{{ route('patient.payment.create', $appointment) }}" class="start-link">Pay Now</a>
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

