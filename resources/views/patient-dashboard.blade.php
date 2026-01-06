<?php $page = 'patient-dashboard'; ?>
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
        Dashboard
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

                    <div class="row">
                        <div class="col-xl-4 d-flex">
                            <div class="dashboard-box-col w-100">
                                <div class="dashboard-widget-box">
                                    <div class="dashboard-content-info">
                                        <h6>Total Appointments</h6>
                                        <h4>{{ $stats['total_appointments'] ?? 0 }}</h4>
                                    </div>
                                    <div class="dashboard-widget-icon">
                                        <span class="dash-icon-box"><i class="fa-solid fa-calendar-check"></i></span>
                                    </div>
                                </div>
                                <div class="dashboard-widget-box">
                                    <div class="dashboard-content-info">
                                        <h6>Upcoming Appointments</h6>
                                        <h4>{{ $stats['upcoming_appointments'] ?? 0 }}</h4>
                                    </div>
                                    <div class="dashboard-widget-icon">
                                        <span class="dash-icon-box"><i class="fa-solid fa-calendar-days"></i></span>
                                    </div>
                                </div>
                                <div class="dashboard-widget-box">
                                    <div class="dashboard-content-info">
                                        <h6>Completed Appointments</h6>
                                        <h4>{{ $stats['completed_appointments'] ?? 0 }}</h4>
                                    </div>
                                    <div class="dashboard-widget-icon">
                                        <span class="dash-icon-box"><i class="fa-solid fa-check-circle"></i></span>
                                    </div>
                                </div>
                            </div>							
                        </div>
                        <div class="col-xl-8 d-flex">
                            <div class="dashboard-card w-100">
                                <div class="dashboard-card-head">
                                    <div class="header-title">
                                        <h5>Recent Appointments</h5>
                                    </div>
                                    <div class="card-view-link">
                                        <a href="{{ route('patient.appointments.index') }}">View All</a>
                                    </div>
                                </div>
                                <div class="dashboard-card-body">
                                    <div class="table-responsive">
                                        <table class="table dashboard-table appoint-table">
                                            <tbody>
                                                @forelse($recent_appointments ?? [] as $appointment)
                                                <tr>
                                                    <td>
                                                        <div class="patient-info-profile">
                                                            <a href="{{ route('patient.appointments.show', $appointment) }}" class="table-avatar">
                                                                <img src="{{ asset('assets/index/doctor-profile-img.jpg') }}" alt="Img">
                                                            </a>
                                                            <div class="patient-name-info">
                                                                <span>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</span>
                                                                <h5><a href="{{ route('patient.appointments.show', $appointment) }}">{{ $appointment->psychologist->user->name ?? 'N/A' }}</a></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="appointment-date-created">
                                                            <h6>{{ $appointment->appointment_date->format('d M Y') }} {{ $appointment->appointment_time ?? '' }}</h6>
                                                            <span class="badge table-badge">{{ $appointment->psychologist->specialization ?? 'N/A' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="apponiment-actions d-flex align-items-center">
                                                            @if($appointment->status === 'pending')
                                                                <span class="badge bg-warning">Pending</span>
                                                            @elseif($appointment->status === 'confirmed')
                                                                <span class="badge bg-success">Confirmed</span>
                                                            @elseif($appointment->status === 'completed')
                                                                <span class="badge bg-info">Completed</span>
                                                            @elseif($appointment->status === 'cancelled')
                                                                <span class="badge bg-danger">Cancelled</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ ucfirst($appointment->status) }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">No appointments found</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attend Online Sessions Section -->
                    <div class="col-xl-12 d-flex mt-4">
                        <div class="dashboard-card w-100">
                            <div class="dashboard-card-head">
                                <div class="header-title">
                                    <h5>
                                        <i class="fa-solid fa-video me-2"></i>Attend Online Sessions
                                    </h5>
                                    <p class="text-muted mb-0 small">Join consultations through the integrated secure audio/video system. Communicate in real-time with the psychologist.</p>
                                </div>
                                <div class="card-view-link">
                                    <a href="{{ route('patient.appointments.index') }}">View All</a>
                                </div>
                            </div>
                            <div class="dashboard-card-body">
                                @if(isset($upcoming_sessions) && $upcoming_sessions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table dashboard-table">
                                        <thead>
                                            <tr>
                                                <th>Psychologist</th>
                                                <th>Date & Time</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($upcoming_sessions as $session)
                                            <tr>
                                                <td>
                                                    <div class="patient-info-profile">
                                                        <a href="{{ route('patient.psychologist.show', $session->psychologist) }}" class="table-avatar">
                                                            <img src="{{ $session->psychologist->user->profile_image ? asset('storage/' . $session->psychologist->user->profile_image) : asset('assets/index/doctor-profile-img.jpg') }}" alt="Img">
                                                        </a>
                                                        <div class="patient-name-info">
                                                            <h5><a href="{{ route('patient.psychologist.show', $session->psychologist) }}">{{ $session->psychologist->user->name }}</a></h5>
                                                            <span>{{ $session->psychologist->specialization }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="appointment-date-created">
                                                        <h6>
                                                            @if($session->appointment_date->isToday())
                                                                Today, {{ \Carbon\Carbon::parse($session->appointment_time)->format('h:i A') }}
                                                            @elseif($session->appointment_date->isTomorrow())
                                                                Tomorrow, {{ \Carbon\Carbon::parse($session->appointment_time)->format('h:i A') }}
                                                            @else
                                                                {{ $session->appointment_date->format('d M Y') }}, {{ \Carbon\Carbon::parse($session->appointment_time)->format('h:i A') }}
                                                            @endif
                                                        </h6>
                                                        <span class="text-muted small">Duration: {{ $session->duration }} minutes</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($session->appointment_date->isToday() && \Carbon\Carbon::parse($session->appointment_time)->lte(now()->addMinutes(15)))
                                                        <span class="badge bg-success">Ready to Join</span>
                                                    @elseif($session->appointment_date->isToday())
                                                        <span class="badge bg-info">Upcoming Today</span>
                                                    @else
                                                        <span class="badge bg-primary">Scheduled</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($session->meeting_link && $session->appointment_date->isToday() && \Carbon\Carbon::parse($session->appointment_time)->lte(now()->addMinutes(15)))
                                                        <a href="{{ route('patient.session.join', $session) }}" class="btn btn-success btn-sm">
                                                            <i class="fa-solid fa-video me-1"></i>Join Session
                                                        </a>
                                                    @elseif($session->meeting_link)
                                                        <a href="{{ route('patient.appointments.show', $session) }}" class="btn btn-primary btn-sm">
                                                            <i class="fa-solid fa-eye me-1"></i>View Details
                                                        </a>
                                                    @else
                                                        <span class="text-muted small">Waiting for psychologist to start</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-video-slash text-muted" style="font-size: 48px;"></i>
                                    <p class="text-muted mt-3 mb-0">No upcoming online sessions</p>
                                    <p class="text-muted small">Your confirmed video appointments will appear here</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /Attend Online Sessions Section -->

                    <!-- Feedback and Ratings Section -->
                    <div class="col-xl-12 d-flex mt-4">
                        <div class="dashboard-card w-100">
                            <div class="dashboard-card-head">
                                <div class="header-title">
                                    <h5>
                                        <i class="fa-solid fa-star me-2 text-warning"></i>
                                        Feedback and Ratings
                                    </h5>
                                    <p class="text-muted mb-0 small">Provide ratings and write feedback after each session. Share overall experience to help future patients choose wisely.</p>
                                </div>
                                <div class="card-view-link">
                                    <a href="{{ route('patient.feedback.index') }}">View All</a>
                                </div>
                            </div>
                            <div class="dashboard-card-body">
                                <!-- Feedback Statistics -->
                                <div class="row mb-4">
                                    <div class="col-xl-3 col-sm-6 col-12 mb-3">
                                        <div class="dashboard-widget-box">
                                            <div class="dashboard-content-info">
                                                <h6>Total Reviews</h6>
                                                <h4>{{ $feedbackStats['total'] ?? 0 }}</h4>
                                            </div>
                                            <div class="dashboard-widget-icon">
                                                <span class="dash-icon-box"><i class="fa-solid fa-star text-warning"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12 mb-3">
                                        <div class="dashboard-widget-box">
                                            <div class="dashboard-content-info">
                                                <h6>Average Rating</h6>
                                                <h4>{{ number_format($feedbackStats['average_rating'] ?? 0, 1) }}/5.0</h4>
                                            </div>
                                            <div class="dashboard-widget-icon">
                                                <span class="dash-icon-box"><i class="fa-solid fa-star-half-stroke text-warning"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12 mb-3">
                                        <div class="dashboard-widget-box">
                                            <div class="dashboard-content-info">
                                                <h6>Positive Reviews</h6>
                                                <h4>{{ ($feedbackStats['rating_5'] ?? 0) + ($feedbackStats['rating_4'] ?? 0) }}</h4>
                                            </div>
                                            <div class="dashboard-widget-icon">
                                                <span class="dash-icon-box"><i class="fa-solid fa-thumbs-up text-success"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12 mb-3">
                                        <div class="dashboard-widget-box">
                                            <div class="dashboard-content-info">
                                                <h6>Pending Feedback</h6>
                                                <h4>{{ isset($pending_feedback) ? $pending_feedback->count() : 0 }}</h4>
                                            </div>
                                            <div class="dashboard-widget-icon">
                                                <span class="dash-icon-box"><i class="fa-solid fa-clock text-info"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pending Feedback (Appointments needing feedback) -->
                                @if(isset($pending_feedback) && $pending_feedback->count() > 0)
                                <div class="mb-4">
                                    <h6 class="mb-3">
                                        <i class="fa-solid fa-exclamation-circle text-warning me-2"></i>
                                        Appointments Pending Feedback
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table dashboard-table">
                                            <thead>
                                                <tr>
                                                    <th>Psychologist</th>
                                                    <th>Appointment Date</th>
                                                    <th>Specialization</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pending_feedback as $appointment)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $appointment->psychologist->user->profile_image ? asset('storage/' . $appointment->psychologist->user->profile_image) : asset('assets/index/doctor-profile-img.jpg') }}" 
                                                                 alt="Psychologist" 
                                                                 class="rounded-circle me-2" 
                                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                                            <div>
                                                                <h6 class="mb-0">{{ $appointment->psychologist->user->name }}</h6>
                                                                <small class="text-muted">#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <strong>{{ $appointment->appointment_date->format('M d, Y') }}</strong><br>
                                                            <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $appointment->psychologist->specialization }}</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('patient.feedback.create', $appointment) }}" class="btn btn-sm btn-primary">
                                                            <i class="fa-solid fa-star me-1"></i>Write Feedback
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif

                                <!-- Recent Feedback Given -->
                                @if(isset($recent_feedback) && $recent_feedback->count() > 0)
                                <div>
                                    <h6 class="mb-3">
                                        <i class="fa-solid fa-history me-2"></i>
                                        Recent Feedback Given
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table dashboard-table">
                                            <thead>
                                                <tr>
                                                    <th>Psychologist</th>
                                                    <th>Rating</th>
                                                    <th>Comment</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recent_feedback as $feedback)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $feedback->psychologist->user->profile_image ? asset('storage/' . $feedback->psychologist->user->profile_image) : asset('assets/index/doctor-profile-img.jpg') }}" 
                                                                 alt="Psychologist" 
                                                                 class="rounded-circle me-2" 
                                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                                            <div>
                                                                <h6 class="mb-0">{{ $feedback->psychologist->user->name }}</h6>
                                                                <small class="text-muted">{{ $feedback->psychologist->specialization }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="rating">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                            @endfor
                                                            <span class="ms-1"><strong>{{ $feedback->rating }}/5</strong></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0">{{ Str::limit($feedback->comment ?? 'No comment', 50) }}</p>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ $feedback->created_at->format('M d, Y') }}</small>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('patient.feedback.show', $feedback) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fa-solid fa-eye me-1"></i>View
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @elseif(!isset($pending_feedback) || $pending_feedback->count() == 0)
                                <div class="text-center py-4">
                                    <i class="fa-solid fa-star text-muted" style="font-size: 48px;"></i>
                                    <p class="text-muted mt-3 mb-2">No feedback yet</p>
                                    <p class="text-muted small">Complete appointments and share your experience to help others choose wisely.</p>
                                    <a href="{{ route('patient.appointments.index') }}" class="btn btn-primary mt-2">
                                        <i class="fa-solid fa-calendar me-2"></i>View Appointments
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /Feedback and Ratings Section -->
                                        
                </div>
            </div>

        </div>

    </div>
    <!-- /Page Content -->

@push('scripts')
<script>
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endpush
@endsection

