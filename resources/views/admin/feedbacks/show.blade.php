<?php $page = 'feedback-details'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Feedback Details</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.feedbacks.index') }}">Reviews</a></li>
                            <li class="breadcrumb-item active">Feedback Details</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Feedback Information -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Feedback Information</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th>Feedback ID:</th>
                                                <td>#FB{{ str_pad($feedback->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Rating:</th>
                                                <td>
                                                    <div class="mb-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $feedback->rating)
                                                                <i class="fe fe-star text-warning" style="font-size: 20px;"></i>
                                                            @else
                                                                <i class="fe fe-star-o text-secondary" style="font-size: 20px;"></i>
                                                            @endif
                                                        @endfor
                                                        <span class="ms-2"><strong>{{ $feedback->rating }}/5</strong></span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Comment:</th>
                                                <td>
                                                    <div class="card bg-light">
                                                        <div class="card-body">
                                                            {{ $feedback->comment ?? 'No comment provided' }}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Submitted On:</th>
                                                <td>{{ $feedback->created_at->format('M d, Y h:i A') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3">Patient Information</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th>Patient ID:</th>
                                                <td>#PAT{{ str_pad($feedback->patient->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Name:</th>
                                                <td>
                                                    <h2 class="table-avatar mb-0">
                                                        <a href="#" class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle" src="{{ asset('assets_admin/img/patients/patient.jpg') }}" alt="User Image">
                                                        </a>
                                                        <a href="#">{{ $feedback->patient->user->name }}</a>
                                                    </h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Email:</th>
                                                <td>{{ $feedback->patient->user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone:</th>
                                                <td>{{ $feedback->patient->user->phone ?? 'N/A' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Psychologist Information -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3">Psychologist Information</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th>Psychologist ID:</th>
                                                <td>#PSY{{ str_pad($feedback->psychologist->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Name:</th>
                                                <td>
                                                    <h2 class="table-avatar mb-0">
                                                        <a href="{{ route('admin.psychologists.show', $feedback->psychologist->id) }}" class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle" src="{{ asset('assets_admin/img/doctors/doctor-thumb-01.jpg') }}" alt="User Image">
                                                        </a>
                                                        <a href="{{ route('admin.psychologists.show', $feedback->psychologist->id) }}">{{ $feedback->psychologist->user->name }}</a>
                                                    </h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Email:</th>
                                                <td>{{ $feedback->psychologist->user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Specialization:</th>
                                                <td>{{ $feedback->psychologist->specialization }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Appointment Information -->
                            @if($feedback->appointment)
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3">Related Appointment</h5>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th>Appointment ID:</th>
                                                                <td>#APT{{ str_pad($feedback->appointment->id, 4, '0', STR_PAD_LEFT) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Date:</th>
                                                                <td>{{ $feedback->appointment->appointment_date->format('M d, Y') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Time:</th>
                                                                <td>{{ \Carbon\Carbon::parse($feedback->appointment->appointment_time)->format('h:i A') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Status:</th>
                                                                <td>
                                                                    @if($feedback->appointment->status == 'completed')
                                                                        <span class="badge bg-info">Completed</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">{{ ucfirst($feedback->appointment->status) }}</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <a href="{{ route('admin.appointments.show', $feedback->appointment->id) }}" class="btn btn-primary">
                                                        <i class="fe fe-eye"></i> View Appointment Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Actions -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fe fe-trash"></i> Delete Review
                                        </button>
                                        <a href="{{ route('admin.feedbacks.index') }}" class="btn btn-secondary">
                                            <i class="fe fe-arrow-left"></i> Back to Reviews
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

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.feedbacks.destroy', $feedback->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fe fe-alert-triangle"></i> This action cannot be undone. The review will be permanently removed from the system to maintain integrity.
                        </div>
                        <div class="mb-3">
                            <label for="delete_reason" class="form-label">Reason for Deletion <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="delete_reason" name="delete_reason" rows="4" required placeholder="e.g., Inappropriate content, misleading information, spam, violates community guidelines, etc."></textarea>
                            <small class="text-muted">Please provide a clear reason for removing this review to maintain system integrity.</small>
                        </div>
                        <div class="card bg-light">
                            <div class="card-body">
                                <strong>Review Summary:</strong><br>
                                <small>
                                    Patient: {{ $feedback->patient->user->name }}<br>
                                    Psychologist: {{ $feedback->psychologist->user->name }}<br>
                                    Rating: {{ $feedback->rating }}/5 Stars<br>
                                    Submitted: {{ $feedback->created_at->format('M d, Y h:i A') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Delete Modal -->
@endsection

