<?php $page = 'notifications-edit'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Edit Announcement</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.notifications.index') }}">Notifications</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.notifications.update', $announcement) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" value="{{ old('title', $announcement->title) }}" required>
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Message <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="message" rows="6" required>{{ old('message', $announcement->message) }}</textarea>
                                        @error('message')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Type <span class="text-danger">*</span></label>
                                        <select class="form-control" name="type" required>
                                            <option value="announcement" {{ old('type', $announcement->type) == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                            <option value="reminder" {{ old('type', $announcement->type) == 'reminder' ? 'selected' : '' }}>Reminder</option>
                                            <option value="policy_update" {{ old('type', $announcement->type) == 'policy_update' ? 'selected' : '' }}>Policy Update</option>
                                            <option value="system_alert" {{ old('type', $announcement->type) == 'system_alert' ? 'selected' : '' }}>System Alert</option>
                                        </select>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Target Audience <span class="text-danger">*</span></label>
                                        <select class="form-control" name="target_audience" required>
                                            <option value="all" {{ old('target_audience', $announcement->target_audience) == 'all' ? 'selected' : '' }}>All Users</option>
                                            <option value="patients" {{ old('target_audience', $announcement->target_audience) == 'patients' ? 'selected' : '' }}>Patients Only</option>
                                            <option value="psychologists" {{ old('target_audience', $announcement->target_audience) == 'psychologists' ? 'selected' : '' }}>Psychologists Only</option>
                                            <option value="admins" {{ old('target_audience', $announcement->target_audience) == 'admins' ? 'selected' : '' }}>Admins Only</option>
                                        </select>
                                        @error('target_audience')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Priority <span class="text-danger">*</span></label>
                                        <select class="form-control" name="priority" required>
                                            <option value="low" {{ old('priority', $announcement->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="normal" {{ old('priority', $announcement->priority) == 'normal' ? 'selected' : '' }}>Normal</option>
                                            <option value="high" {{ old('priority', $announcement->priority) == 'high' ? 'selected' : '' }}>High</option>
                                            <option value="urgent" {{ old('priority', $announcement->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        </select>
                                        @error('priority')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Scheduled At (Optional)</label>
                                        <input type="datetime-local" class="form-control" name="scheduled_at" value="{{ old('scheduled_at', $announcement->scheduled_at ? $announcement->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                                        @error('scheduled_at')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expires At (Optional)</label>
                                        <input type="datetime-local" class="form-control" name="expires_at" value="{{ old('expires_at', $announcement->expires_at ? $announcement->expires_at->format('Y-m-d\TH:i') : '') }}">
                                        @error('expires_at')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Update Announcement</button>
                                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->
@endsection

