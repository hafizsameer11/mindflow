     <!-- Profile Sidebar -->
     <style>
        .logout-menu-item button.sidebar-menu-link {
            width: 100%;
            padding: 0.75rem 1.5rem;
            color: inherit;
            text-decoration: none;
            background: transparent !important;
            border: none;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .logout-menu-item button.sidebar-menu-link:hover {
            background-color: rgba(0, 0, 0, 0.05) !important;
            color: #0d6efd;
        }
        .logout-menu-item button.sidebar-menu-link i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }
        .logout-menu-item button.sidebar-menu-link span {
            font-size: 0.9375rem;
            font-weight: 400;
        }
     </style>
     <div class="profile-sidebar doctor-sidebar profile-sidebar-new">
        <div class="widget-profile pro-widget-content">
            <div class="profile-info-widget">
                @php
                    $user = Auth::user();
                    $profileImage = $user->profile_image 
                        ? asset('storage/' . $user->profile_image) 
                        : asset('assets/index/doctor-profile-img.jpg');
                @endphp
                <a href="{{ route('psychologist.profile') }}" class="booking-doc-img">
                    <img src="{{ $profileImage }}" alt="User Image">
                </a>
                <div class="profile-det-info">
                    <h3><a href="{{ route('psychologist.profile') }}">{{ $user->name }}</a></h3>
                   
                    <span class="badge doctor-role-badge"><i class="fa-solid fa-circle"></i>Psychologist</span>
                </div>
            </div>
        </div>
        <div class="doctor-available-head">
            <div class="input-block input-block-new">
                <label class="form-label">Availability <span class="text-danger">*</span></label>
                <select class="select form-control">
                    <option>I am Available Now</option>
                    <option>Not Available</option>
                </select>
            </div>
        </div>
        <div class="dashboard-widget">
            <nav class="dashboard-menu">
                <ul>
                    <li class="{{ Request::is('psychologist/dashboard') ? 'active' : '' }}">
                        <a href="{{ route('psychologist.dashboard') }}">
                            <i class="fa-solid fa-shapes"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('psychologist/dashboard') && isset($notifications) ? 'active' : '' }}">
                        <a href="{{ route('psychologist.notifications') }}">
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
                    <li class="{{ Request::is('psychologist/appointments*') ? 'active' : '' }}">
                        <a href="{{ route('psychologist.appointments.index') }}">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('psychologist/availability*') ? 'active' : '' }}">
                        <a href="{{ route('psychologist.availability.index') }}">
                            <i class="fa-solid fa-calendar-day"></i>
                            <span>Available Timings</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('psychologist/my-patients*') ? 'active' : '' }}">
                        <a href="{{ route('psychologist.my-patients') }}">
                            <i class="fa-solid fa-user-injured"></i>
                            <span>My Patients</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('psychologist/profile') ? 'active' : '' }}">
                        <a href="{{ route('psychologist.profile') }}">
                            <i class="fa-solid fa-user-gear"></i>
                            <span>Profile Settings</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('psychologist/prescriptions*') ? 'active' : '' }}">
                        <a href="{{ route('psychologist.prescriptions.index') }}">
                            <i class="fa-solid fa-prescription-bottle"></i>
                            <span>Prescriptions</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('psychologist/feedback*') ? 'active' : '' }}">
                        <a href="{{ route('psychologist.feedback.index') }}">
                            <i class="fa-solid fa-star"></i>
                            <span>Feedback Review</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('psychologist/earnings*') ? 'active' : '' }}">
                        <a href="{{ route('psychologist.earnings.index') }}">
                            <i class="fa-solid fa-dollar-sign"></i>
                            <span>Earnings & Payments</span>
                        </a>
                    </li>
                    <li class="logout-menu-item">
                        <form method="POST" action="{{ route('psychologist.logout') }}" class="d-inline w-100 m-0">
                            @csrf
                            <button type="submit" class="w-100 text-start border-0 bg-transparent p-0 d-flex align-items-center sidebar-menu-link" style="color: inherit; text-decoration: none; background: transparent !important; width: 100%; padding: 0.75rem 1.5rem; transition: all 0.3s ease; cursor: pointer;">
                                <i class="isax isax-logout me-2"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
     <!-- /Profile Sidebar -->
