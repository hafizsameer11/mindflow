<?php $page = 'psychologist-verification'; ?>
@extends('layout.mainlayout_admin')
@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Psychologist Profile Review</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.psychologists.index') }}">Psychologists</a></li>
                            <li class="breadcrumb-item active">Profile Review</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Profile Information -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Profile Information</h4>
                            <div class="text-end">
                                <span class="badge bg-{{ $psychologist->verification_status === 'verified' ? 'success' : ($psychologist->verification_status === 'rejected' ? 'danger' : 'warning') }}-light">
                                    {{ ucfirst($psychologist->verification_status) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th width="40%">Name:</th>
                                                <td><strong>{{ $psychologist->user->name }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Email:</th>
                                                <td>{{ $psychologist->user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone:</th>
                                                <td>{{ $psychologist->user->phone ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Date of Birth:</th>
                                                <td>{{ $psychologist->user->date_of_birth ? $psychologist->user->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Gender:</th>
                                                <td>{{ ucfirst($psychologist->user->gender ?? 'N/A') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Account Status:</th>
                                                <td>
                                                    <span class="badge bg-{{ $psychologist->user->status === 'active' ? 'success' : 'danger' }}-light">
                                                        {{ ucfirst($psychologist->user->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Member Since:</th>
                                                <td>{{ $psychologist->user->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th width="40%">Specialization:</th>
                                                <td><strong>{{ $psychologist->specialization ?? 'N/A' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Experience:</th>
                                                <td>{{ $psychologist->experience_years ?? 0 }} Years</td>
                                            </tr>
                                            <tr>
                                                <th>Consultation Fee:</th>
                                                <td><strong class="text-primary">${{ number_format($psychologist->consultation_fee ?? 0, 2) }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Verification Status:</th>
                                                <td>
                                                    <span class="badge bg-{{ $psychologist->verification_status === 'verified' ? 'success' : ($psychologist->verification_status === 'rejected' ? 'danger' : 'warning') }}-light">
                                                        {{ ucfirst($psychologist->verification_status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @if($psychologist->verified_at)
                                            <tr>
                                                <th>Verified At:</th>
                                                <td>{{ $psychologist->verified_at->format('M d, Y h:i A') }}</td>
                                            </tr>
                                            @endif
                                            @if($psychologist->rejection_reason)
                                            <tr>
                                                <th>Rejection Reason:</th>
                                                <td class="text-danger">{{ $psychologist->rejection_reason }}</td>
                                            </tr>
                                            @endif
                                            @if(isset($stats))
                                            <tr>
                                                <th>Total Appointments:</th>
                                                <td>{{ $stats['total_appointments'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Average Rating:</th>
                                                <td>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= floor($stats['average_rating']))
                                                            <i class="fe fe-star text-warning"></i>
                                                        @else
                                                            <i class="fe fe-star-o text-secondary"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="ms-1">({{ number_format($stats['average_rating'], 1) }}) - {{ $stats['total_reviews'] }} reviews</span>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if($psychologist->bio)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>Bio:</h5>
                                    <p class="text-muted">{{ $psychologist->bio }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Qualifications -->
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Uploaded Qualifications</h4>
                        </div>
                        <div class="card-body">
                            @if($psychologist->qualifications && count($psychologist->qualifications) > 0)
                                <div class="row">
                                    @foreach($psychologist->qualifications as $index => $qualification)
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <i class="fe fe-file-text" style="font-size: 48px; color: #007bff;"></i>
                                                <p class="mt-2 mb-2"><strong>Qualification {{ $index + 1 }}</strong></p>
                                                <a href="{{ asset('storage/' . $qualification) }}" target="_blank" class="btn btn-sm btn-primary">
                                                    <i class="fe fe-eye"></i> View Document
                                                </a>
                                                <a href="{{ asset('storage/' . $qualification) }}" download class="btn btn-sm btn-success">
                                                    <i class="fe fe-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fe fe-alert-circle"></i> No qualifications uploaded.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Verification Actions -->
                @if($psychologist->verification_status === 'pending')
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Verification Actions</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="{{ route('admin.psychologists.verify', $psychologist->id) }}" method="POST" class="mb-3">
                                        @csrf
                                        <div class="alert alert-info">
                                            <h5><i class="fe fe-check-circle"></i> Verify Psychologist</h5>
                                            <p class="mb-0">By verifying, you confirm that:</p>
                                            <ul class="mb-0">
                                                <li>All qualifications have been reviewed</li>
                                                <li>Information is authentic and accurate</li>
                                                <li>Psychologist can now accept appointments</li>
                                            </ul>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-lg w-100" 
                                                onclick="return confirm('Are you sure you want to verify this psychologist? This will allow them to accept appointments.')">
                                            <i class="fe fe-check"></i> Verify Psychologist
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-warning">
                                        <h5><i class="fe fe-x-circle"></i> Reject Verification</h5>
                                        <p class="mb-2">If qualifications are insufficient or information is incorrect:</p>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-lg w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        <i class="fe fe-x"></i> Reject Verification
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($psychologist->verification_status === 'rejected')
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header bg-danger-light">
                            <h4 class="card-title text-danger">Rejection Details</h4>
                        </div>
                        <div class="card-body">
                            @if($psychologist->rejection_reason)
                                <p><strong>Rejection Reason:</strong></p>
                                <p class="text-danger">{{ $psychologist->rejection_reason }}</p>
                            @endif
                            <form action="{{ route('admin.psychologists.verify', $psychologist->id) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-success" 
                                        onclick="return confirm('Are you sure you want to verify this psychologist?')">
                                    <i class="fe fe-check"></i> Verify Now
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header bg-success-light">
                            <h4 class="card-title text-success">Verified Psychologist</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-success"><i class="fe fe-check-circle"></i> This psychologist has been verified and can accept appointments.</p>
                            @if($psychologist->verified_at)
                                <p><strong>Verified At:</strong> {{ $psychologist->verified_at->format('M d, Y h:i A') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <a href="{{ route('admin.psychologists.index') }}" class="btn btn-secondary">
                        <i class="fe fe-arrow-left"></i> Back to Psychologists List
                    </a>
                </div>
            </div>

        </div>
    </div>
    <!-- /Page Wrapper -->

    <!-- Reject Modal -->
    @if($psychologist->verification_status === 'pending')
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.psychologists.reject', $psychologist->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Reject Psychologist Verification</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fe fe-alert-triangle"></i> Please provide a clear reason for rejection. This will be sent to the psychologist.
                        </div>
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" required 
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
    <!-- /Reject Modal -->
@endsection

