<?php $page = 'appointments'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Psychologist
        @endslot
        @slot('li_1')
            Appointment Management
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
                    @component('components.sidebar_doctor')
                    @endcomponent 
                </div>
                
                <div class="col-lg-8 col-xl-9">
                    <div class="dashboard-header">
                        <h3>Appointment Management</h3>
                        <ul class="header-list-btns">
                            <li>
                                <form action="{{ route('psychologist.appointments.index') }}" method="GET" class="d-flex">
                                    <div class="input-block dash-search-input">
                                        <input type="text" name="search" class="form-control" placeholder="Search by patient name, email, or ID" value="{{ request('search') }}">
                                        <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    </div>
                                    @if(request('status'))
                                        <input type="hidden" name="status" value="{{ request('status') }}">
                                    @endif
                                    @if(request('date_from'))
                                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                                    @endif
                                    @if(request('date_to'))
                                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                                    @endif
                                </form>
                            </li>
                        </ul>
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

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4 col-sm-6">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-1">Upcoming</h6>
                                            <h3 class="mb-0">{{ $stats['upcoming'] ?? 0 }}</h3>
                                        </div>
                                        <div class="text-primary">
                                            <i class="fa-solid fa-calendar-check fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="card border-info">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-1">Ongoing</h6>
                                            <h3 class="mb-0">{{ $stats['ongoing'] ?? 0 }}</h3>
                                        </div>
                                        <div class="text-info">
                                            <i class="fa-solid fa-video fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="card border-success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-1">Completed</h6>
                                            <h3 class="mb-0">{{ $stats['completed'] ?? 0 }}</h3>
                                        </div>
                                        <div class="text-success">
                                            <i class="fa-solid fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="appointment-tab-head">
                        <div class="appointment-tabs">
                            <ul class="nav nav-pills inner-tab" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a href="{{ route('psychologist.appointments.index', array_merge(request()->except('status'), ['status' => 'upcoming'])) }}" 
                                       class="nav-link {{ ($status ?? 'upcoming') == 'upcoming' ? 'active' : '' }}" 
                                       id="pills-upcoming-tab">
                                        Upcoming<span>{{ $stats['upcoming'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="{{ route('psychologist.appointments.index', array_merge(request()->except('status'), ['status' => 'ongoing'])) }}" 
                                       class="nav-link {{ ($status ?? '') == 'ongoing' ? 'active' : '' }}" 
                                       id="pills-ongoing-tab">
                                        Ongoing<span>{{ $stats['ongoing'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="{{ route('psychologist.appointments.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}" 
                                       class="nav-link {{ ($status ?? '') == 'pending' ? 'active' : '' }}" 
                                       id="pills-pending-tab">
                                        Pending<span>{{ $stats['pending'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="{{ route('psychologist.appointments.index', array_merge(request()->except('status'), ['status' => 'completed'])) }}" 
                                       class="nav-link {{ ($status ?? '') == 'completed' ? 'active' : '' }}" 
                                       id="pills-complete-tab">
                                        Completed<span>{{ $stats['completed'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="{{ route('psychologist.appointments.index', array_merge(request()->except('status'), ['status' => 'cancelled'])) }}" 
                                       class="nav-link {{ ($status ?? '') == 'cancelled' ? 'active' : '' }}" 
                                       id="pills-cancel-tab">
                                        Cancelled<span>{{ $stats['cancelled'] ?? 0 }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="filter-head">
                            <form action="{{ route('psychologist.appointments.index') }}" method="GET" class="d-flex align-items-center">
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                <div class="position-relative daterange-wraper me-2">
                                    <div class="input-groupicon calender-input">
                                        <input type="date" name="date_from" class="form-control" placeholder="From Date" value="{{ request('date_from') }}">
                                    </div>
                                </div>
                                <div class="position-relative daterange-wraper me-2">
                                    <div class="input-groupicon calender-input">
                                        <input type="date" name="date_to" class="form-control" placeholder="To Date" value="{{ request('date_to') }}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-filter me-1"></i>Filter
                                </button>
                                @if(request('date_from') || request('date_to'))
                                    <a href="{{ route('psychologist.appointments.index', request()->except(['date_from', 'date_to'])) }}" class="btn btn-secondary btn-sm ms-2">
                                        <i class="fa-solid fa-times me-1"></i>Clear
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>

                    <div class="tab-content appointment-tab-content">
                        <div class="tab-pane fade show active" id="pills-upcoming" role="tabpanel">
                            @forelse($appointments ?? [] as $appointment)
                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{ route('psychologist.appointments.show', $appointment->id) }}">
                                                @php
                                                    $profileImage = $appointment->patient->user->profile_image 
                                                        ? asset('storage/' . $appointment->patient->user->profile_image) 
                                                        : asset('assets/index/patient.jpg');
                                                @endphp
                                                <img src="{{ $profileImage }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                <h6>
                                                    <a href="{{ route('psychologist.appointments.show', $appointment->id) }}">{{ $appointment->patient->user->name }}</a>
                                                    @if($appointment->status === 'pending')
                                                        <span class="badge bg-warning">New</span>
                                                    @elseif($appointment->status === 'confirmed')
                                                        <span class="badge bg-success">Confirmed</span>
                                                    @endif
                                                </h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>{{ $appointment->appointment_date->format('d M Y') }} {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>{{ $appointment->psychologist->specialization ?? 'N/A' }}</li>
                                            <li><i class="fa-solid fa-video me-1"></i>{{ ucfirst($appointment->consultation_type ?? 'video') }}</li>
                                        </ul>
                                    </li>
                                    <li class="mail-info-patient">
                                        <ul>
                                            <li><i class="fa-solid fa-envelope"></i>{{ $appointment->patient->user->email }}</li>
                                            <li><i class="fa-solid fa-phone"></i>{{ $appointment->patient->user->phone ?? 'N/A' }}</li>
                                        </ul>
                                    </li>
                                    <li class="appointment-action">
                                        <ul>
                                            <li>
                                                <a href="{{ route('psychologist.appointments.show', $appointment->id) }}" title="View Details">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </li>
                                            @if($appointment->status === 'pending')
                                            <li>
                                                <button type="button" class="border-0 bg-transparent text-success" data-bs-toggle="modal" data-bs-target="#confirmModal{{ $appointment->id }}" title="Confirm">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </li>
                                            @endif
                                            @if(in_array($appointment->status, ['pending', 'confirmed']))
                                            <li>
                                                <button type="button" class="border-0 bg-transparent text-primary" data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $appointment->id }}" title="Reschedule">
                                                    <i class="fa-solid fa-calendar-days"></i>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="border-0 bg-transparent text-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $appointment->id }}" title="Cancel">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </li>
                                            @endif
                                        </ul>
                                    </li>
                                    @if($appointment->status === 'confirmed' && $appointment->appointment_date == today())
                                    <li class="appointment-start">
                                        <a href="{{ route('psychologist.session.start', $appointment->id) }}" class="start-link">
                                            <i class="fa-solid fa-video me-1"></i>Start Session
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Confirm Modal -->
                            <div class="modal fade" id="confirmModal{{ $appointment->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('psychologist.appointments.confirm', $appointment->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Appointment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to confirm this appointment?</p>
                                                <div class="alert alert-info">
                                                    <strong>Patient:</strong> {{ $appointment->patient->user->name }}<br>
                                                    <strong>Date:</strong> {{ $appointment->appointment_date->format('d M Y') }}<br>
                                                    <strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">Confirm Appointment</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Reschedule Modal -->
                            <div class="modal fade" id="rescheduleModal{{ $appointment->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('psychologist.appointments.reschedule', $appointment->id) }}" method="POST">
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
                            <div class="modal fade" id="cancelModal{{ $appointment->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('psychologist.appointments.cancel', $appointment->id) }}" method="POST">
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
                            @empty
                            <div class="text-center py-5">
                                <i class="fa-solid fa-calendar-xmark fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No appointments found</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection
