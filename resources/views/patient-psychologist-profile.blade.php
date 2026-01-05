<?php $page = 'psychologist-profile'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            <a href="{{ route('patient.search') }}">Search Psychologists</a>
        @endslot
        @slot('li_2')
        Psychologist Profile
        @endslot
    @endcomponent
   
    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <!-- Psychologist Widget -->
            <div class="card doc-profile-card">
                <div class="card-body">
                    <div class="doctor-widget doctor-profile-two">
                        <div class="doc-info-left">
                            <div class="doctor-img">
                                @php
                                    $profileImage = $psychologist->user->profile_image 
                                        ? asset('storage/' . $psychologist->user->profile_image) 
                                        : asset('assets/img/doctors/doc-profile-02.jpg');
                                @endphp
                                <img src="{{ $profileImage }}" class="img-fluid" alt="{{ $psychologist->user->name }}">
                            </div>
                            <div class="doc-info-cont">
                                <span class="badge doc-avail-badge">
                                    <i class="fa-solid fa-circle"></i>Verified & Available
                                </span>
                                <h4 class="doc-name">
                                    {{ $psychologist->user->name }}
                                    <img src="{{ asset('assets/img/icons/badge-check.svg') }}" alt="Verified">
                                    <span class="badge doctor-role-badge">
                                        <i class="fa-solid fa-circle"></i>Psychologist
                                    </span>
                                </h4>
                                <p><strong>Specialization:</strong> {{ $psychologist->specialization }}</p>
                                <p><strong>Experience:</strong> {{ $psychologist->experience_years }} Years</p>
                                @if($psychologist->user->address)
                                <p class="address-detail">
                                    <span class="loc-icon"><i class="feather-map-pin"></i></span>
                                    {{ $psychologist->user->address }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <div class="doc-info-right">
                            <ul class="doctors-activities">
                                <li>
                                    <div class="hospital-info">
                                        <span class="list-icon"><img src="{{ asset('assets/img/icons/watch-icon.svg') }}" alt="Img"></span>
                                        <p>{{ $psychologist->experience_years }} Years Experience</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="hospital-info">
                                        <span class="list-icon"><img src="{{ asset('assets/img/icons/thumb-icon.svg') }}" alt="Img"></span>
                                        <p><b>{{ number_format($avgRating, 1) }}</b> Average Rating</p>
                                        <p class="text-muted small mb-0">{{ $totalReviews }} Reviews</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="hospital-info">
                                        <span class="list-icon"><img src="{{ asset('assets/img/icons/building-icon.svg') }}" alt="Img"></span>
                                        <p>{{ $completedAppointments }} Completed Sessions</p>
                                    </div>
                                    <h5 class="accept-text">
                                        <span><i class="feather-check"></i></span>Accepting New Patients
                                    </h5>
                                </li>
                                <li>
                                    <div class="rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= round($avgRating) ? 'filled' : '' }}"></i>
                                        @endfor
                                        <span>{{ number_format($avgRating, 1) }}</span>
                                        <a href="#reviews" class="d-inline-block average-rating">{{ $totalReviews }} Reviews</a>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('patient.appointments.create', $psychologist->id) }}" class="btn btn-primary">
                                            <i class="isax isax-calendar-1 me-2"></i>Book Appointment
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="doc-profile-card-bottom">
                        <ul>
                            <li>
                                <span class="bg-blue"><img src="{{ asset('assets/img/icons/calendar3.svg') }}" alt="Img"></span>
                                {{ $totalAppointments }} Total Appointments
                            </li>
                            <li>
                                <span class="bg-dark-blue"><img src="{{ asset('assets/img/icons/bullseye.svg') }}" alt="Img"></span>
                                In Practice for {{ $psychologist->experience_years }} Years
                            </li>
                            <li>
                                <span class="bg-green"><img src="{{ asset('assets/img/icons/bookmark-star.svg') }}" alt="Img"></span>
                                {{ $totalReviews }} Reviews
                            </li>
                        </ul>
                        <div class="bottom-book-btn">
                            <p><span>Consultation Fee: ${{ number_format($psychologist->consultation_fee, 2) }}</span> per Session</p>
                            <div class="clinic-booking">
                                <a class="apt-btn" href="{{ route('patient.appointments.create', $psychologist->id) }}">Book Appointment</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Psychologist Widget -->
            
            <div class="doctors-detailed-info">
                <ul class="information-title-list">
                    <li class="active">
                        <a href="#bio">About</a>
                    </li>
                    <li>
                        <a href="#availability">Availability</a>
                    </li>
                    <li>
                        <a href="#reviews">Reviews ({{ $totalReviews }})</a>
                    </li>
                </ul>
                <div class="doc-information-main">
                    <!-- Bio Section -->
                    <div class="doc-information-details bio-detail" id="bio">
                        <div class="detail-title">
                            <h4>About</h4>
                        </div>
                        @if($psychologist->bio)
                            <p>{{ $psychologist->bio }}</p>
                        @else
                            <p class="text-muted">No bio available.</p>
                        @endif
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h5>Specialization</h5>
                                <p>{{ $psychologist->specialization }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Experience</h5>
                                <p>{{ $psychologist->experience_years }} Years</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Consultation Fee</h5>
                                <p>${{ number_format($psychologist->consultation_fee, 2) }} per session</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Contact</h5>
                                <p>
                                    @if($psychologist->user->email)
                                        <i class="feather-mail me-2"></i>{{ $psychologist->user->email }}<br>
                                    @endif
                                    @if($psychologist->user->phone)
                                        <i class="feather-phone me-2"></i>{{ $psychologist->user->phone }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($psychologist->qualifications && count($psychologist->qualifications) > 0)
                        <div class="mt-4">
                            <h5>Qualifications</h5>
                            <ul>
                                @foreach($psychologist->qualifications as $qualification)
                                <li>{{ basename($qualification) }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                    <!-- Availability Section -->
                    <div class="doc-information-details" id="availability">
                        <div class="detail-title">
                            <h4>Availability</h4>
                        </div>
                        @if($psychologist->availabilities && $psychologist->availabilities->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Day</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                        @endphp
                                        @foreach($days as $dayIndex => $dayName)
                                            @php
                                                $availability = $psychologist->availabilities->firstWhere('day_of_week', $dayIndex);
                                            @endphp
                                            <tr>
                                                <td><strong>{{ $dayName }}</strong></td>
                                                <td>
                                                    @if($availability && $availability->is_available)
                                                        {{ \Carbon\Carbon::parse($availability->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('h:i A') }}
                                                    @else
                                                        <span class="text-muted">Not Available</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($availability && $availability->is_available)
                                                        <span class="badge bg-success">Available</span>
                                                    @else
                                                        <span class="badge bg-secondary">Unavailable</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Availability information not available.</p>
                        @endif
                    </div>

                    <!-- Reviews Section -->
                    <div class="doc-information-details" id="reviews">
                        <div class="detail-title">
                            <h4>Reviews & Ratings</h4>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                <h2 class="text-primary">{{ number_format($avgRating, 1) }}</h2>
                                <div class="rating mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= round($avgRating) ? 'filled' : '' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-muted">{{ $totalReviews }} Reviews</p>
                            </div>
                            <div class="col-md-8">
                                @for($rating = 5; $rating >= 1; $rating--)
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="me-2">{{ $rating }} Star</span>
                                        <div class="progress flex-grow-1" style="height: 10px;">
                                            @php
                                                $count = $ratingBreakdown[$rating] ?? 0;
                                                $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="ms-2 small">{{ $count }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <h5 class="mb-3">Recent Reviews</h5>
                        @forelse($recentReviews as $review)
                            <div class="review-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">{{ $review->patient->user->name ?? 'Anonymous' }}</h6>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'filled' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="text-muted small">{{ $review->created_at->format('M d, Y') }}</span>
                                </div>
                                @if($review->comment)
                                    <p class="mb-0">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted">No reviews yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection

