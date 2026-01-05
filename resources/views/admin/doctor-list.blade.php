<?php $page = 'doctor-list'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">List of Doctors</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript:(0);">Users</a></li>
                            <li class="breadcrumb-item active">Doctor</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.psychologists.index') }}" class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="search" placeholder="Search by name, email or specialization..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="verification_status">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('verification_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ request('verification_status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="rejected" {{ request('verification_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                                <div class="col-md-3 text-end">
                                    <a href="{{ route('admin.psychologists.index', ['verification_status' => 'pending']) }}" class="btn btn-warning">
                                        <i class="fe fe-clock"></i> View Pending ({{ \App\Models\Psychologist::where('verification_status', 'pending')->count() }})
                                    </a>
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
                                            <th>Doctor Name</th>
                                            <th>Speciality</th>
                                            <th>Member Since</th>
                                            <th>Earned</th>
                                            <th>Verification Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($psychologists ?? [] as $psychologist)
                                        <tr class="{{ $psychologist->verification_status === 'pending' ? 'table-warning' : '' }}">
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="{{ route('admin.psychologists.show', $psychologist->id) }}" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $psychologist->user->profile_image ? asset('storage/' . $psychologist->user->profile_image) : asset('assets_admin/img/doctors/doctor-thumb-01.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="{{ route('admin.psychologists.show', $psychologist->id) }}">{{ $psychologist->user->name }}</a>
                                                    @if($psychologist->verification_status === 'pending')
                                                        <span class="badge bg-warning ms-2">Needs Review</span>
                                                    @endif
                                                </h2>
                                            </td>
                                            <td>{{ $psychologist->specialization }}</td>
                                            <td>{{ $psychologist->user->created_at->format('M d, Y') }}</td>
                                            <td>${{ number_format(\App\Models\Payment::whereHas('appointment', function($q) use ($psychologist) {
                                                $q->where('psychologist_id', $psychologist->id);
                                            })->where('status', 'verified')->sum('amount'), 2) }}</td>
                                            <td>
                                                @if($psychologist->verification_status == 'verified')
                                                    <span class="badge bg-success">Verified</span>
                                                @elseif($psychologist->verification_status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1 align-items-center">
                                                    <a href="{{ route('admin.psychologists.show', $psychologist->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fe fe-eye"></i> Review
                                                    </a>
                                                    @if($psychologist->verification_status == 'pending')
                                                    <form action="{{ route('admin.psychologists.verify', $psychologist->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" 
                                                                onclick="return confirm('Are you sure you want to approve this psychologist?')" 
                                                                title="Approve">
                                                            <i class="fe fe-check"></i> Approve
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#rejectModal{{ $psychologist->id }}"
                                                            title="Reject">
                                                        <i class="fe fe-x"></i> Reject
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No psychologists found</td>
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

    <!-- Reject Modals -->
    @foreach($psychologists ?? [] as $psychologist)
        @if($psychologist->verification_status === 'pending')
        <div class="modal fade" id="rejectModal{{ $psychologist->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $psychologist->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.psychologists.reject', $psychologist->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectModalLabel{{ $psychologist->id }}">Reject Psychologist Verification</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="fe fe-alert-triangle"></i> Please provide a clear reason for rejection. This will be sent to the psychologist.
                            </div>
                            <div class="mb-3">
                                <label for="rejection_reason{{ $psychologist->id }}" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="rejection_reason{{ $psychologist->id }}" name="rejection_reason" rows="4" required 
                                          placeholder="e.g., Qualifications are not clear or insufficient. Please upload clearer copies of your certificates."></textarea>
                                <small class="text-muted">Minimum 10 characters required.</small>
                                @error('rejection_reason')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject Verification</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endforeach
    <!-- /Reject Modals -->

    </div>
    <!-- /Main Wrapper -->
@endsection
