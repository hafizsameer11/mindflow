<?php $page = 'notifications'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Notification & Communication</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Notifications</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-primary">
                                    <i class="fe fe-bell"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['total'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Total Announcements</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-success">
                                    <i class="fe fe-check-circle"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['active'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Active Announcements</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-info">
                                    <i class="fe fe-send"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['total_sent'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Total Messages Sent</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-warning">
                                    <i class="fe fe-alert-circle"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['policy_updates'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Policy Updates</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Statistics Cards -->

            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.notifications.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" placeholder="Search by title or message..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="type">
                                        <option value="">All Types</option>
                                        <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                        <option value="reminder" {{ request('type') == 'reminder' ? 'selected' : '' }}>Reminder</option>
                                        <option value="policy_update" {{ request('type') == 'policy_update' ? 'selected' : '' }}>Policy Update</option>
                                        <option value="system_alert" {{ request('type') == 'system_alert' ? 'selected' : '' }}>System Alert</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="target_audience">
                                        <option value="">All Audiences</option>
                                        <option value="all" {{ request('target_audience') == 'all' ? 'selected' : '' }}>All Users</option>
                                        <option value="patients" {{ request('target_audience') == 'patients' ? 'selected' : '' }}>Patients</option>
                                        <option value="psychologists" {{ request('target_audience') == 'psychologists' ? 'selected' : '' }}>Psychologists</option>
                                        <option value="admins" {{ request('target_audience') == 'admins' ? 'selected' : '' }}>Admins</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="status">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary w-100 mt-2">Clear</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Search and Filter -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">All Announcements</h5>
                                <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
                                    <i class="fe fe-plus"></i> Create Announcement
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Target Audience</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Sent</th>
                                            <th>Created</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($announcements ?? [] as $announcement)
                                        <tr>
                                            <td>
                                                <strong>{{ $announcement->title }}</strong><br>
                                                <small class="text-muted">{{ Str::limit($announcement->message, 60) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $announcement->type == 'announcement' ? 'primary' : ($announcement->type == 'reminder' ? 'info' : ($announcement->type == 'policy_update' ? 'warning' : 'danger')) }}-light">
                                                    {{ ucfirst(str_replace('_', ' ', $announcement->type)) }}
                                                </span>
                                            </td>
                                            <td>{{ ucfirst($announcement->target_audience) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $announcement->priority == 'urgent' ? 'danger' : ($announcement->priority == 'high' ? 'warning' : ($announcement->priority == 'normal' ? 'info' : 'secondary')) }}">
                                                    {{ ucfirst($announcement->priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($announcement->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $announcement->sent_count }} users</td>
                                            <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                                            <td class="text-end">
                                                <div class="d-flex gap-1 justify-content-end">
                                                    <a href="{{ route('admin.notifications.show', $announcement) }}" class="btn btn-sm btn-primary">
                                                        <i class="fe fe-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.notifications.edit', $announcement) }}" class="btn btn-sm btn-success">
                                                        <i class="fe fe-edit"></i>
                                                    </a>
                                                    @if($announcement->sent_count == 0 || $announcement->is_active)
                                                    <form action="{{ route('admin.notifications.send', $announcement) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Send this announcement to all target users?')">
                                                            <i class="fe fe-send"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                    <form action="{{ route('admin.notifications.destroy', $announcement) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this announcement?')">
                                                            <i class="fe fe-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No announcements found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->
@endsection

