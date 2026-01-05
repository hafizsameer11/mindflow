<?php $page = 'doctor-dashboard'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Doctor
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
                    @component('components.sidebar_doctor')
                    @endcomponent
                </div>
                <div class="col-lg-8 col-xl-9">

                    <div class="row">
                        <div class="col-xl-4 d-flex">
                            <div class="dashboard-box-col w-100">
                                <div class="dashboard-widget-box">
                                    <div class="dashboard-content-info">
                                        <h6>Total Patients</h6>
                                        <h4>{{ $stats['total_patients'] ?? 0 }}</h4>
                                    </div>
                                    <div class="dashboard-widget-icon">
                                        <span class="dash-icon-box"><i class="fa-solid fa-user-injured"></i></span>
                                    </div>
                                </div>
                                <div class="dashboard-widget-box">
                                    <div class="dashboard-content-info">
                                        <h6>Appointments Today</h6>
                                        <h4>{{ $stats['appointments_today'] ?? 0 }}</h4>
                                    </div>
                                    <div class="dashboard-widget-icon">
                                        <span class="dash-icon-box"><i class="fa-solid fa-user-clock"></i></span>
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
                            </div>							
                        </div>
                        <div class="col-xl-8 d-flex">
                            <div class="dashboard-card w-100">
                                <div class="dashboard-card-head">
                                    <div class="header-title">
                                        <h5>Appointment</h5>
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
                                                            <a href="{{ route('psychologist.appointments.show', $appointment->id) }}" class="table-avatar">
                                                                <img src="{{ $appointment->patient->user->profile_image ? asset('storage/' . $appointment->patient->user->profile_image) : asset('assets/index/patient.jpg') }}" alt="Img">
                                                            </a>
                                                            <div class="patient-name-info">
                                                                <span>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</span>
                                                                <h5><a href="{{ route('psychologist.appointments.show', $appointment->id) }}">{{ $appointment->patient->user->name }}</a></h5>
                                                            </div>
                                                        </div>
                                                        
                                                    </td>
                                                    <td>
                                                        <div class="appointment-date-created">
                                                            <h6>{{ $appointment->appointment_date->format('d M Y') }} {{ $appointment->appointment_time }}</h6>
                                                            <span class="badge table-badge">{{ $appointment->psychologist->specialization }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="apponiment-actions d-flex align-items-center">
                                                            @if($appointment->status === 'pending')
                                                            <form action="{{ route('psychologist.appointments.confirm', $appointment->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="text-success-icon me-2 border-0 bg-transparent"><i class="fa-solid fa-check"></i></button>
                                                            </form>
                                                            @endif
                                                            <form action="{{ route('psychologist.appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="text-danger-icon border-0 bg-transparent"><i class="fa-solid fa-xmark"></i></button>
                                                            </form>
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
                        <div class="col-xl-5 d-flex">
                            <div class="dashboard-chart-col w-100">
                                <div class="dashboard-card w-100">
                                    <div class="dashboard-card-head border-0">
                                        <div class="header-title">
                                            <h5>Weekly Overview</h5>
                                        </div>
                                        <div class="chart-create-date">
                                            <h6>{{ \Carbon\Carbon::now()->subDays(7)->format('M d') }} - {{ \Carbon\Carbon::now()->format('M d') }}</h6>
                                        </div>
                                    </div>
                                    <div class="dashboard-card-body">
                                        <div class="chart-tab">
                                            <ul class="nav nav-pills product-licence-tab" id="pills-tab2" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="pills-revenue-tab" data-bs-toggle="pill" data-bs-target="#pills-revenue" type="button" role="tab" aria-controls="pills-revenue" aria-selected="false">Revenue</button>
                                                </li>	
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-appointment-tab" data-bs-toggle="pill" data-bs-target="#pills-appointment" type="button" role="tab" aria-controls="pills-appointment" aria-selected="true">Appointments</button>
                                                </li>	
                                            </ul>
                                            <div class="tab-content w-100" id="v-pills-tabContent">
                                                <div class="tab-pane fade show active" id="pills-revenue" role="tabpanel" aria-labelledby="pills-revenue-tab">
                                                    <div id="revenue-chart"></div>
                                                </div>
                                                <div class="tab-pane fade" id="pills-appointment" role="tabpanel" aria-labelledby="pills-appointment-tab">
                                                    <div id="appointment-chart"></div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="dashboard-card w-100">
                                    <div class="dashboard-card-head">
                                        <div class="header-title">
                                            <h5>Recent Patients</h5>
                                        </div>
                                        <div class="card-view-link">
                                            <a href="{{url('my-patients')}}">View All</a>
                                        </div>
                                    </div>
                                    <div class="dashboard-card-body">
                                        <div class="d-flex recent-patient-grid-boxes">
                                            @forelse($recent_patients ?? [] as $item)
                                            <div class="recent-patient-grid">
                                                <a href="{{ route('psychologist.appointments.show', $item['last_appointment']->id ?? '#') }}" class="patient-img">
                                                    <img src="{{ $item['patient']->user->profile_image ? asset('storage/' . $item['patient']->user->profile_image) : asset('assets/index/patient.jpg') }}" alt="Img">
                                                </a>
                                                <h5><a href="{{ route('psychologist.appointments.show', $item['last_appointment']->id ?? '#') }}">{{ $item['patient']->user->name }}</a></h5>
                                                <span>Patient ID : PT{{ str_pad($item['patient']->id, 4, '0', STR_PAD_LEFT) }}</span>
                                                @if($item['last_appointment'])
                                                <div class="date-info">
                                                    <p>Last Appointment<br>{{ $item['last_appointment']->appointment_date->format('d M Y') }}</p>
                                                </div>
                                                @endif
                                            </div>
                                            @empty
                                            <div class="text-center w-100 py-3">
                                                <p class="text-muted">No recent patients</p>
                                            </div>
                                            @endforelse
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7 d-flex">
                            <div class="dashboard-main-col w-100">
                                <div class="upcoming-appointment-card">
                                    <div class="title-card">
                                        <h5>Upcoming Appointment</h5>
                                    </div>
                                    @if(isset($upcoming_appointment) && $upcoming_appointment)
                                    <div class="upcoming-patient-info">
                                        <div class="info-details">
                                            <span class="img-avatar">
                                                <img src="{{ $upcoming_appointment->patient->user->profile_image ? asset('storage/' . $upcoming_appointment->patient->user->profile_image) : asset('assets/index/patient.jpg') }}" alt="Img">
                                            </span>
                                            <div class="name-info">
                                                <span>#APT{{ str_pad($upcoming_appointment->id, 4, '0', STR_PAD_LEFT) }}</span>
                                                <h6><a href="{{ route('psychologist.appointments.show', $upcoming_appointment->id) }}">{{ $upcoming_appointment->patient->user->name }}</a></h6>
                                            </div>
                                        </div>
                                        <div class="date-details">
                                            <span>{{ $upcoming_appointment->psychologist->specialization }}</span>
                                            <h6>
                                                @if($upcoming_appointment->appointment_date->isToday())
                                                    Today, {{ \Carbon\Carbon::parse($upcoming_appointment->appointment_time)->format('h:i A') }}
                                                @elseif($upcoming_appointment->appointment_date->isTomorrow())
                                                    Tomorrow, {{ \Carbon\Carbon::parse($upcoming_appointment->appointment_time)->format('h:i A') }}
                                                @else
                                                    {{ $upcoming_appointment->appointment_date->format('M d, Y') }}, {{ \Carbon\Carbon::parse($upcoming_appointment->appointment_time)->format('h:i A') }}
                                                @endif
                                            </h6>
                                        </div>
                                        <div class="circle-bg">
                                            <img src="{{URL::asset('/assets/img/bg/dashboard-circle-bg.png')}}" alt="Img">
                                        </div>
                                    </div>
                                    <div class="appointment-card-footer">
                                        <h5><i class="fa-solid fa-video"></i>{{ ucfirst($upcoming_appointment->consultation_type ?? 'Video') }} Appointment</h5>
                                        <div class="btn-appointments">
                                            @if($upcoming_appointment->status === 'confirmed' && $upcoming_appointment->appointment_date->isToday())
                                            <a href="{{ route('psychologist.session.start', $upcoming_appointment->id) }}" class="btn">Start Appointment</a>
                                            @else
                                            <a href="{{ route('psychologist.appointments.show', $upcoming_appointment->id) }}" class="btn">View Details</a>
                                            @endif
                                        </div>
                                    </div>
                                    @else
                                    <div class="upcoming-patient-info">
                                        <div class="text-center py-4">
                                            <i class="fa-solid fa-calendar-xmark text-muted" style="font-size: 48px;"></i>
                                            <p class="text-muted mt-3 mb-0">No upcoming appointments</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="dashboard-card w-100">
                                    <div class="dashboard-card-head">
                                        <div class="header-title">
                                            <h5>Recent Invoices</h5>
                                        </div>
                                        <div class="card-view-link">
                                            <a href="{{ route('psychologist.earnings.index') }}">View All</a>
                                        </div>
                                    </div>
                                    <div class="dashboard-card-body">
                                        <div class="table-responsive">
                                            <table class="table dashboard-table">
                                                <tbody>
                                                    @forelse($recent_invoices ?? [] as $payment)
                                                    <tr>
                                                        <td>
                                                            <div class="patient-info-profile">
                                                                <a href="{{ route('psychologist.appointments.show', $payment->appointment_id) }}" class="table-avatar">
                                                                    <img src="{{ $payment->appointment->patient->user->profile_image ? asset('storage/' . $payment->appointment->patient->user->profile_image) : asset('assets/index/patient.jpg') }}" alt="Img">
                                                                </a>
                                                                <div class="patient-name-info">
                                                                    <h5><a href="{{ route('psychologist.appointments.show', $payment->appointment_id) }}">{{ $payment->appointment->patient->user->name }}</a></h5>
                                                                    <span>#APT{{ str_pad($payment->appointment_id, 4, '0', STR_PAD_LEFT) }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="appointment-date-created">
                                                                <span class="paid-text">Amount</span>
                                                                <h6>${{ number_format($payment->amount, 2) }}</h6>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="appointment-date-created">
                                                                <span class="paid-text">Paid On</span>
                                                                <h6>{{ $payment->created_at->format('d M Y') }}</h6>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="appointment-date-created">
                                                                <span class="badge badge-{{ $payment->status === 'verified' ? 'success' : ($payment->status === 'pending_verification' ? 'warning' : 'danger') }}">
                                                                    {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="apponiment-view d-flex align-items-center">
                                                                <a href="{{ route('psychologist.appointments.show', $payment->appointment_id) }}"><i class="isax isax-eye4"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">No invoices found</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <!-- Conduct Online Sessions Section -->
                        <div class="col-xl-12 d-flex mt-4">
                            <div class="dashboard-card w-100">
                                <div class="dashboard-card-head">
                                    <div class="header-title">
                                        <h5>
                                            <i class="fa-solid fa-video me-2"></i>Conduct Online Sessions
                                        </h5>
                                        <p class="text-muted mb-0 small">Use integrated audio/video tools to conduct virtual consultations. Communicate securely with patients within the platform.</p>
                                    </div>
                                    <div class="card-view-link">
                                        <a href="{{ route('psychologist.appointments.index') }}">View All</a>
                                    </div>
                                </div>
                                <div class="dashboard-card-body">
                                    @if(isset($upcoming_sessions) && $upcoming_sessions->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table dashboard-table">
                                            <thead>
                                                <tr>
                                                    <th>Patient</th>
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
                                                            <a href="{{ route('psychologist.appointments.show', $session) }}" class="table-avatar">
                                                                <img src="{{ $session->patient->user->profile_image ? asset('storage/' . $session->patient->user->profile_image) : asset('assets/index/patient.jpg') }}" alt="Img">
                                                            </a>
                                                            <div class="patient-name-info">
                                                                <h5><a href="{{ route('psychologist.appointments.show', $session) }}">{{ $session->patient->user->name }}</a></h5>
                                                                <span>#APT{{ str_pad($session->id, 4, '0', STR_PAD_LEFT) }}</span>
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
                                                            <span class="badge bg-success">Ready to Start</span>
                                                        @elseif($session->appointment_date->isToday())
                                                            <span class="badge bg-info">Upcoming Today</span>
                                                        @else
                                                            <span class="badge bg-primary">Scheduled</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($session->appointment_date->isToday() && \Carbon\Carbon::parse($session->appointment_time)->lte(now()->addMinutes(15)))
                                                            <a href="{{ route('psychologist.session.start', $session) }}" class="btn btn-success btn-sm">
                                                                <i class="fa-solid fa-video me-1"></i>Start Session
                                                            </a>
                                                        @else
                                                            <a href="{{ route('psychologist.appointments.show', $session) }}" class="btn btn-primary btn-sm">
                                                                <i class="fa-solid fa-eye me-1"></i>View Details
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
                                        <i class="fa-solid fa-video-slash text-muted" style="font-size: 48px;"></i>
                                        <p class="text-muted mt-3 mb-0">No upcoming online sessions</p>
                                        <p class="text-muted small">Your confirmed video appointments will appear here</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- /Conduct Online Sessions Section -->

                        <!-- Notifications Section -->
                        <div id="notifications" class="col-xl-12 d-flex mt-4">
                        @if(isset($notifications) && $notifications->count() > 0)
                            <div class="dashboard-card w-100">
                                <div class="dashboard-card-head">
                                    <div class="header-title">
                                        <h5>Recent Notifications 
                                            @if($unreadCount > 0)
                                                <span class="badge bg-danger ms-2">{{ $unreadCount }} unread</span>
                                            @endif
                                        </h5>
                                    </div>
                                    @if($unreadCount > 0)
                                    <div class="card-view-link">
                                        <a href="javascript:void(0)" onclick="markAllAsRead()">Mark All as Read</a>
                                    </div>
                                    @endif
                                </div>
                                <div class="dashboard-card-body">
                                    <div class="table-responsive">
                                        <table class="table dashboard-table">
                                            <tbody>
                                                @foreach($notifications as $notification)
                                                    @php
                                                        $data = $notification->data;
                                                        $isRead = !is_null($notification->read_at);
                                                    @endphp
                                                    <tr class="{{ !$isRead ? 'table-active' : '' }}">
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-3">
                                                                    <i class="fa-solid fa-bullhorn text-primary" style="font-size: 24px;"></i>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-1">
                                                                        {{ $data['title'] ?? 'Announcement' }}
                                                                        @if(!$isRead)
                                                                            <span class="badge bg-danger badge-sm ms-2">New</span>
                                                                        @endif
                                                                        @if(isset($data['priority']) && in_array($data['priority'], ['urgent', 'high']))
                                                                            <span class="badge bg-{{ $data['priority'] == 'urgent' ? 'danger' : 'warning' }} badge-sm ms-1">{{ ucfirst($data['priority']) }}</span>
                                                                        @endif
                                                                    </h6>
                                                                    <p class="text-muted mb-1">{{ $data['message'] ?? '' }}</p>
                                                                    <small class="text-muted">{{ $notification->created_at->format('M d, Y h:i A') }} ({{ $notification->created_at->diffForHumans() }})</small>
                                                                </div>
                                                                @if(!$isRead)
                                                                <div class="ms-3">
                                                                    <button class="btn btn-sm btn-outline-primary" onclick="markAsRead('{{ $notification->id }}')">Mark as Read</button>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="dashboard-card w-100">
                            <div class="dashboard-card-head">
                                <div class="header-title">
                                    <h5>Notifications</h5>
                                </div>
                            </div>
                            <div class="dashboard-card-body">
                                <div class="text-center py-4">
                                    <i class="fa-solid fa-bell-slash text-muted" style="font-size: 48px;"></i>
                                    <p class="text-muted mt-3 mb-0">No notifications yet</p>
                                </div>
                            </div>
                        </div>
                        @endif
                       
                    </div>
                                        
                </div>
            </div>

        </div>

    </div>
    <!-- /Page Content -->

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Weekly Revenue Chart
    @if(isset($weeklyRevenue) && $weeklyRevenue->count() > 0)
    var revenueOptions = {
        series: [{
            name: 'Revenue',
            data: [
                @foreach($weeklyRevenue as $revenue)
                {{ $revenue->total ?? 0 }},
                @endforeach
            ]
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: { show: false }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' },
        xaxis: {
            categories: [
                @foreach($weeklyRevenue as $revenue)
                '{{ \Carbon\Carbon::parse($revenue->date)->format("M d") }}',
                @endforeach
            ]
        },
        colors: ['#4CAF50'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
            }
        }
    };
    var revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions);
    revenueChart.render();
    @else
    document.getElementById('revenue-chart').innerHTML = '<div class="text-center py-5"><p class="text-muted">No revenue data available</p></div>';
    @endif

    // Weekly Appointments Chart
    @if(isset($weeklyAppointments) && $weeklyAppointments->count() > 0)
    var appointmentOptions = {
        series: [{
            name: 'Appointments',
            data: [
                @foreach($weeklyAppointments as $appointment)
                {{ $appointment->total ?? 0 }},
                @endforeach
            ]
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: { show: false }
        },
        dataLabels: { enabled: true },
        xaxis: {
            categories: [
                @foreach($weeklyAppointments as $appointment)
                '{{ \Carbon\Carbon::parse($appointment->date)->format("M d") }}',
                @endforeach
            ]
        },
        colors: ['#2196F3']
    };
    var appointmentChart = new ApexCharts(document.querySelector("#appointment-chart"), appointmentOptions);
    appointmentChart.render();
    @else
    document.getElementById('appointment-chart').innerHTML = '<div class="text-center py-5"><p class="text-muted">No appointment data available</p></div>';
    @endif

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
