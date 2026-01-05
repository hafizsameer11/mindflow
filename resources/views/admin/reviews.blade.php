<?php $page = 'reviews'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Reviews</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Reviews</li>
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
                                    <i class="fe fe-star"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['total'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Total Reviews</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-warning">
                                    <i class="fe fe-star"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['average_rating'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Average Rating</h6>
                                <p class="text-muted mb-0">Out of 5.0</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-success">
                                    <i class="fe fe-thumbs-up"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ ($stats['rating_5'] ?? 0) + ($stats['rating_4'] ?? 0) }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Positive Reviews</h6>
                                <p class="text-muted mb-0">4-5 Stars</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-danger">
                                    <i class="fe fe-thumbs-down"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ ($stats['rating_1'] ?? 0) + ($stats['rating_2'] ?? 0) }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Negative Reviews</h6>
                                <p class="text-muted mb-0">1-2 Stars</p>
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
                            <form method="GET" action="{{ route('admin.feedbacks.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" placeholder="Search by patient, psychologist, or comment..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="rating">
                                        <option value="">All Ratings</option>
                                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_from" placeholder="From Date" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_to" placeholder="To Date" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    <a href="{{ route('admin.feedbacks.index') }}" class="btn btn-secondary w-100 mt-2">Clear</a>
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
                        <div class="card-header">
                            <h5 class="card-title">All Patient Reviews</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Patient Name</th>
                                            <th>Doctor Name</th>
                                            <th>Ratings</th>
                                            <th>Description</th>
                                            <th>Date</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($feedbacks ?? [] as $feedback)
                                        <tr>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $feedback->patient->user->profile_image ? asset('storage/' . $feedback->patient->user->profile_image) : asset('assets_admin/img/patients/patient.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $feedback->patient->user->name ?? 'N/A' }}</a>
                                                </h2>
                                            </td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $feedback->psychologist->user->profile_image ? asset('storage/' . $feedback->psychologist->user->profile_image) : asset('assets_admin/img/doctors/doctor-thumb-01.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $feedback->psychologist->user->name ?? 'N/A' }}</a>
                                                </h2>
                                            </td>
                                            <td>
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= ($feedback->rating ?? 0))
                                                        <i class="fe fe-star text-warning"></i>
                                                    @else
                                                        <i class="fe fe-star-o text-secondary"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-1">({{ $feedback->rating ?? 0 }})</span>
                                            </td>
                                            <td>
                                                @if($feedback->comment)
                                                    <div class="comment-preview">
                                                        {{ Str::limit($feedback->comment, 80) }}
                                                        @if(strlen($feedback->comment) > 80)
                                                            <a href="{{ route('admin.feedbacks.show', $feedback->id) }}" class="text-primary">Read more...</a>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">No comment</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $feedback->created_at->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $feedback->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.feedbacks.show', $feedback->id) }}" class="btn btn-sm btn-primary me-1">
                                                    <i class="fe fe-eye"></i> View
                                                </a>
                                                <button type="button" class="btn btn-sm bg-danger-light" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $feedback->id }}">
                                                    <i class="fe fe-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No reviews found</td>
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

    <!-- Delete Modals -->
    @foreach($feedbacks ?? [] as $feedback)
    <div class="modal fade" id="deleteModal{{ $feedback->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $feedback->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.feedbacks.destroy', $feedback->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel{{ $feedback->id }}">Delete Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fe fe-alert-triangle"></i> This action cannot be undone. The review will be permanently removed from the system.
                        </div>
                        <div class="mb-3">
                            <label for="delete_reason{{ $feedback->id }}" class="form-label">Reason for Deletion <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="delete_reason{{ $feedback->id }}" name="delete_reason" rows="3" required placeholder="e.g., Inappropriate content, misleading information, spam, etc."></textarea>
                            <small class="text-muted">Please provide a reason for removing this review to maintain system integrity.</small>
                        </div>
                        <div class="card bg-light">
                            <div class="card-body">
                                <strong>Review Details:</strong><br>
                                <small>
                                    Patient: {{ $feedback->patient->user->name ?? 'N/A' }}<br>
                                    Psychologist: {{ $feedback->psychologist->user->name ?? 'N/A' }}<br>
                                    Rating: {{ $feedback->rating }}/5<br>
                                    Date: {{ $feedback->created_at->format('M d, Y') }}
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
    @endforeach
    <!-- /Delete Modals -->
    </div>
    <!-- /Main Wrapper -->
@endsection
