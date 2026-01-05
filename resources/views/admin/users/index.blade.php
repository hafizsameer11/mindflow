<?php $page = 'users'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-7 col-auto">
                        <h3 class="page-title">User Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ul>
                    </div>
                    <div class="col-sm-5 col">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary float-end mt-2">
                            <i class="fe fe-plus"></i> Add New User
                        </a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="search" placeholder="Search by name or email..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="role">
                                        <option value="">All Roles</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="psychologist" {{ request('role') == 'psychologist' ? 'selected' : '' }}>Psychologist</option>
                                        <option value="patient" {{ request('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="status">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Search and Filter -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Last Login</th>
                                            <th>Created</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users ?? [] as $user)
                                        <tr>
                                            <td>#USR{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets_admin/img/profiles/avatar-01.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'psychologist' ? 'info' : 'success') }}-light">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                                @if($user->psychologist)
                                                    <br><small class="text-muted">{{ $user->psychologist->specialization }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}-light">
                                                    {{ ucfirst($user->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($user->last_login)
                                                    {{ $user->last_login->diffForHumans() }}
                                                    <br><small class="text-muted">{{ $user->last_login->format('M d, Y h:i A') }}</small>
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm bg-success-light me-2">
                                                        <i class="fe fe-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline me-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm bg-{{ $user->status === 'active' ? 'warning' : 'info' }}-light" 
                                                                onclick="return confirm('Are you sure you want to {{ $user->status === 'active' ? 'deactivate' : 'activate' }} this user?')">
                                                            <i class="fe fe-{{ $user->status === 'active' ? 'lock' : 'unlock' }}"></i> {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                        </button>
                                                    </form>
                                                    @if($user->id !== Auth::id())
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm bg-danger-light" 
                                                                onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                            <i class="fe fe-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No users found</td>
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

