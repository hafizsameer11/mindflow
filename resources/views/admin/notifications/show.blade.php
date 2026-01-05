<?php $page = 'notifications-show'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Announcement Details</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.notifications.index') }}">Notifications</a></li>
                            <li class="breadcrumb-item active">Details</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <h4 class="mb-3">{{ $announcement->title }}</h4>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <p class="mb-0">{{ $announcement->message }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">Announcement Details</h6>
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th>Type:</th>
                                                        <td>
                                                            <span class="badge bg-{{ $announcement->type == 'announcement' ? 'primary' : ($announcement->type == 'reminder' ? 'info' : ($announcement->type == 'policy_update' ? 'warning' : 'danger')) }}-light">
                                                                {{ ucfirst(str_replace('_', ' ', $announcement->type)) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Target:</th>
                                                        <td>{{ ucfirst($announcement->target_audience) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Priority:</th>
                                                        <td>
                                                            <span class="badge bg-{{ $announcement->priority == 'urgent' ? 'danger' : ($announcement->priority == 'high' ? 'warning' : ($announcement->priority == 'normal' ? 'info' : 'secondary')) }}">
                                                                {{ ucfirst($announcement->priority) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status:</th>
                                                        <td>
                                                            @if($announcement->is_active)
                                                                <span class="badge bg-success">Active</span>
                                                            @else
                                                                <span class="badge bg-secondary">Inactive</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Sent To:</th>
                                                        <td><strong>{{ $announcement->sent_count }}</strong> users</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Created By:</th>
                                                        <td>{{ $announcement->creator->name ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Created At:</th>
                                                        <td>{{ $announcement->created_at->format('M d, Y h:i A') }}</td>
                                                    </tr>
                                                    @if($announcement->scheduled_at)
                                                    <tr>
                                                        <th>Scheduled:</th>
                                                        <td>{{ $announcement->scheduled_at->format('M d, Y h:i A') }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($announcement->expires_at)
                                                    <tr>
                                                        <th>Expires:</th>
                                                        <td>{{ $announcement->expires_at->format('M d, Y h:i A') }}</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.notifications.edit', $announcement) }}" class="btn btn-success">
                                            <i class="fe fe-edit"></i> Edit
                                        </a>
                                        @if($announcement->sent_count == 0 || $announcement->is_active)
                                        <form action="{{ route('admin.notifications.send', $announcement) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-info" onclick="return confirm('Send this announcement to all target users?')">
                                                <i class="fe fe-send"></i> Send Now
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('admin.notifications.toggle-status', $announcement) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-{{ $announcement->is_active ? 'warning' : 'success' }}">
                                                <i class="fe fe-power"></i> {{ $announcement->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.notifications.destroy', $announcement) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this announcement?')">
                                                <i class="fe fe-trash"></i> Delete
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">
                                            <i class="fe fe-arrow-left"></i> Back
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->
@endsection

