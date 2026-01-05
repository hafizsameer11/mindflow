<?php $page = 'feedback'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            Feedback Review
        @endslot
        @slot('li_2')
            Reviews & Ratings
        @endslot
    @endcomponent
   
   	<!-- Page Content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-xl-3 theiaStickySidebar">
                    @component('components.sidebar_patient')
                    @endcomponent 
                </div>
                
                <div class="col-lg-8 col-xl-9">
                    <div class="dashboard-header">
                        <h3>Feedback Review</h3>
                        <p class="text-muted">Review ratings and comments from patients. Use feedback to enhance consultation quality.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="dashboard-widget-box">
                                <div class="dashboard-content-info">
                                    <h6>Total Reviews</h6>
                                    <h4>{{ $stats['total'] ?? 0 }}</h4>
                                </div>
                                <div class="dashboard-widget-icon">
                                    <span class="dash-icon-box"><i class="fa-solid fa-star"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="dashboard-widget-box">
                                <div class="dashboard-content-info">
                                    <h6>Average Rating</h6>
                                    <h4>{{ number_format($stats['average_rating'] ?? 0, 1) }}/5.0</h4>
                                </div>
                                <div class="dashboard-widget-icon">
                                    <span class="dash-icon-box"><i class="fa-solid fa-star-half-stroke"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="dashboard-widget-box">
                                <div class="dashboard-content-info">
                                    <h6>Positive Reviews</h6>
                                    <h4>{{ ($stats['rating_5'] ?? 0) + ($stats['rating_4'] ?? 0) }}</h4>
                                </div>
                                <div class="dashboard-widget-icon">
                                    <span class="dash-icon-box"><i class="fa-solid fa-thumbs-up"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="dashboard-widget-box">
                                <div class="dashboard-content-info">
                                    <h6>Negative Reviews</h6>
                                    <h4>{{ ($stats['rating_1'] ?? 0) + ($stats['rating_2'] ?? 0) }}</h4>
                                </div>
                                <div class="dashboard-widget-icon">
                                    <span class="dash-icon-box"><i class="fa-solid fa-thumbs-down"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" action="{{ route('patient.feedback.index') }}" class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="search" placeholder="Search by psychologist name or comment..." value="{{ request('search') }}">
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
                                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Feedback List -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Your Reviews</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Psychologist</th>
                                            <th>Rating</th>
                                            <th>Comment</th>
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
                                                        <img class="avatar-img rounded-circle" src="{{ $feedback->psychologist->user->profile_image ? asset('storage/' . $feedback->psychologist->user->profile_image) : asset('assets/index/doctor-profile-img.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $feedback->psychologist->user->name ?? 'N/A' }}</a>
                                                </h2>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= ($feedback->rating ?? 0) ? 'filled' : '' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="ms-1">({{ $feedback->rating ?? 0 }}/5)</span>
                                            </td>
                                            <td>
                                                @if($feedback->comment)
                                                    <div class="comment-preview">
                                                        {{ Str::limit($feedback->comment, 80) }}
                                                        @if(strlen($feedback->comment) > 80)
                                                            <a href="{{ route('patient.feedback.show', $feedback->id) }}" class="text-primary">Read more...</a>
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
                                                <a href="{{ route('patient.feedback.show', $feedback->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fa-solid fa-eye"></i> View
                                                </a>
                                                <a href="{{ route('patient.feedback.edit', $feedback->id) }}" class="btn btn-sm btn-secondary">
                                                    <i class="fa-solid fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fa-solid fa-star text-muted" style="font-size: 48px;"></i>
                                                <p class="text-muted mt-3 mb-0">No reviews yet</p>
                                            </td>
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
    <!-- /Page Content -->
@endsection

