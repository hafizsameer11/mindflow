     <!-- Profile Sidebar -->
     <div class="profile-sidebar doctor-sidebar profile-sidebar-new">
        <div class="widget-profile pro-widget-content">
            <div class="profile-info-widget">
                <a href="{{url('doctor-profile')}}" class="booking-doc-img">
                    <img src="{{ asset('assets/index/doctor-profile-img.jpg') }}" alt="User Image">
                </a>
                <div class="profile-det-info">
                    <h3><a href="{{url('doctor-profile')}}">Dr Edalin Hendry</a></h3>
                   
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
                    <li class="{{ Request::is('doctor-dashboard') ? 'active' : '' }}">
                        <a href="{{url('doctor-dashboard')}}">
                            <i class="fa-solid fa-shapes"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('doctor-request') ? 'active' : '' }}">
                        <a href="{{url('doctor-request')}}">
                            <i class="fa-solid fa-calendar-check"></i>
                            <span>Requests</span>
                            <small class="unread-msg">2</small>
                        </a>
                    </li>
                    <li class="{{ Request::is('appointments','doctor-appointments-grid','doctor-appointment-details','doctor-cancelled-appointment','doctor-cancelled-appointment-2','doctor-upcoming-appointment','doctor-appointment-start','doctor-completed-appointment') ? 'active' : '' }}">
                        <a href="{{url('appointments')}}">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('available-timings') ? 'active' : '' }}">
                        <a href="{{url('available-timings')}}">
                            <i class="fa-solid fa-calendar-day"></i>
                            <span>Available Timings</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('my-patients','patient-profile') ? 'active' : '' }}">
                        <a href="{{url('my-patients')}}">
                            <i class="fa-solid fa-user-injured"></i>
                            <span>My Patients</span>
                        </a>
                    </li>
                   
                  																																				
                
                   
                </ul>
            </nav>
        </div>
    </div>
     <!-- /Profile Sidebar -->
