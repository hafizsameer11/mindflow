@php
    $user = Auth::user();
    $patient = $user->patient ?? null;
    $userInitials = strtoupper(substr($user->name ?? 'U', 0, 1));
    $patientId = $patient ? 'PT' . str_pad($patient->id, 4, '0', STR_PAD_LEFT) : 'PT0000';
    $profileImage = $user->profile_image 
        ? asset('storage/' . $user->profile_image) 
        : asset('assets/index/doctor-profile-img.jpg');
@endphp

<!-- Profile Sidebar -->
<div class="profile-sidebar patient-sidebar profile-sidebar-new">
        <div class="widget-profile pro-widget-content">
            <div class="profile-info-widget patient-profile-compact">
                <div class="patient-avatar-wrapper">
                    <a href="{{ route('patient.profile') }}" class="booking-doc-img patient-avatar-img">
                        <img src="{{ $profileImage }}" alt="User Image">
                    </a>
                    <span class="patient-verified-badge">
                        <i class="fa-solid fa-check"></i>
                    </span>
                </div>
                <div class="profile-det-info patient-det-info-compact">
                    <h3 class="patient-initials">{{ $userInitials }}.</h3>
                    <div class="patient-label">Pati</div>
                    <div class="patient-details">
                        <h5 class="mb-0">ID : {{ $patientId }}</h5>
                    </div>
                    <span class="patient-status-dot">
                        <i class="fa-solid fa-circle"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="dashboard-widget">
            <nav class="dashboard-menu">
                <ul>
                    <li class="{{ Request::is('patient/dashboard') ? 'active' : '' }}">
                        <a href="{{ route('patient.dashboard') }}">
                            <i class="isax isax-category-2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('patient/notifications*') ? 'active' : '' }}">
                        <a href="{{ route('patient.notifications') }}">
                            <i class="fa-solid fa-bell"></i>
                            <span>Notifications</span>
                            @php
                                $sidebarUnreadCount = Auth::user()->unreadNotifications()
                                    ->where('type', 'App\Notifications\AdminAnnouncementNotification')
                                    ->count();
                            @endphp
                            @if($sidebarUnreadCount > 0)
                                <small class="unread-msg">{{ $sidebarUnreadCount > 9 ? '9+' : $sidebarUnreadCount }}</small>
                            @endif
                        </a>
                    </li>
                    <li class="{{ Request::is('patient/search*') ? 'active' : '' }}">
                        <a href="{{ route('patient.search') }}">
                            <i class="fa-solid fa-search"></i>
                            <span>Search Psychologists</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('patient/appointments*') ? 'active' : '' }}">
                        <a href="{{ route('patient.appointments.index') }}">
                            <i class="isax isax-calendar-1"></i>
                            <span>My Appointments</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('patient/prescriptions*') ? 'active' : '' }}">
                        <a href="{{ route('patient.prescriptions.index') }}">
                            <i class="fa-solid fa-prescription-bottle"></i>
                            <span>Prescriptions</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('patient/feedback*') ? 'active' : '' }}">
                        <a href="{{ route('patient.feedback.index') }}">
                            <i class="fa-solid fa-star"></i>
                            <span>Feedback Review</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('patient/history*') ? 'active' : '' }}">
                        <a href="{{ route('patient.history') }}">
                            <i class="fa-solid fa-history"></i>
                            <span>View History</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('dependent') ? 'active' : '' }}">
                        <a href="{{ url('dependent') }}">
                            <i class="isax isax-user-octagon"></i>
                            <span>Dependants</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('medical-records') ? 'active' : '' }}">
                        <a href="{{ url('medical-records') }}">
                            <i class="isax isax-note-21"></i>
                            <span>Add Medical Records</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('patient-accounts') ? 'active' : '' }}">
                        <a href="{{ url('patient-accounts') }}">
                            <i class="isax isax-wallet-2"></i>
                            <span>Accounts</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('patient-invoices') ? 'active' : '' }}">
                        <a href="{{ url('patient-invoices') }}">
                            <i class="isax isax-document-text"></i>
                            <span>Invoices</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('medical-details') ? 'active' : '' }}">
                        <a href="{{ url('medical-details') }}">
                            <i class="isax isax-note-1"></i>
                            <span>Vitals</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('profile-settings') || Request::is('patient/profile*') ? 'active' : '' }}">
                        <a href="{{ route('profile-settings') }}">
                            <i class="isax isax-setting-2"></i>
                            <span>Settings</span>
                        </a>
                    </li>
    
                    <li class="{{ Request::is('login') ? 'active' : '' }}">
                        <a href="{{ url('login') }}">
                            <i class="isax isax-logout"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
<!-- /Profile Sidebar -->
