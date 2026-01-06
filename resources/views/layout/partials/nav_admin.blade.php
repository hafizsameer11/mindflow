<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>
                <li class="{{ Request::is('admin/index_admin') ? 'active' : '' }}">
                    <a href="{{ url('admin/index_admin') }}"><i class="fe fe-home"></i> <span>Dashboard</span></a>
                </li>
                <li class="{{ Request::is('admin/appointment-list') ? 'active' : '' }}">
                    <a href="{{ url('admin/appointment-list') }}" 
                       title="Track all appointments — pending, confirmed, completed, or cancelled. Observe patterns such as repeated cancellations or missed sessions.">
                        <i class="fe fe-calendar"></i>
                        <span>Appointment Monitoring</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/doctor-list') ? 'active' : '' }}">
                    <a href="{{ url('admin/doctor-list') }}"><i class="fe fe-user-plus"></i> <span>Doctors</span></a>
                </li>
                <li class="{{ Request::is('admin/patient-list') ? 'active' : '' }}">
                    <a href="{{ url('admin/patient-list') }}"><i class="fe fe-user"></i> <span>Patients</span></a>
                </li>
                <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}"><i class="fe fe-users"></i> <span>User Management</span></a>
                </li>
                <li class="{{ Request::is('admin/reviews') || Request::is('admin/feedbacks*') ? 'active' : '' }}">
                    <a href="{{ url('admin/reviews') }}" 
                       title="Review feedback submitted by patients regarding sessions. Remove inappropriate or misleading reviews to maintain system integrity.">
                        <i class="fe fe-star-o"></i> <span>Feedback Monitoring</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/transactions-list') || Request::is('admin/payments*') ? 'active' : '' }}">
                    <a href="{{ url('admin/transactions-list') }}" 
                       title="Access complete transaction history and verify financial accuracy. Handle payment disputes or refund requests if necessary.">
                        <i class="fe fe-activity"></i>
                        <span>Payment Oversight</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/notifications*') ? 'active' : '' }}">
                    <a href="{{ route('admin.notifications.index') }}" 
                       title="Send important announcements, reminders, or updates to users. Alert psychologists about new appointments or system policies.">
                        <i class="fe fe-bell"></i> <span>Notification & Communi.</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/backups*') ? 'active' : '' }}">
                    <a href="{{ route('admin.backups.index') }}"
                       title="Maintain encrypted database backups regularly. Ensure quick recovery of data in case of system or server failure.">
                        <i class="fe fe-shield"></i> <span>Data Security & Backup</span>
                    </a>
                </li>
                <li class="submenu">
                    <a href="javascript:;"><i class="fe fe-document"></i> <span> Reports</span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li>
                            <a class="{{ Request::is('admin/reports') ? 'active' : '' }}"
                               href="{{ route('admin.reports.index') }}">
                                <i class="fe fe-bar-chart"></i> <span>Report Generation</span>
                            </a>
                        </li>
                        <li>
                            <a class="{{ Request::is('admin/invoice-report','admin/invoice') ? 'active' : '' }}"
                               href="{{ url('admin/invoice-report') }}">
                                <i class="fe fe-file-text"></i> <span>Invoice Reports</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
