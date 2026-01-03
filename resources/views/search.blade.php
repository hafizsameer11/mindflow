<?php $page = 'search'; ?>
@extends('layout.mainlayout')
@section('content')
   	<!-- Breadcrumb -->
		<div class="breadcrumb-bar overflow-visible">
			<div class="container">
				<div class="row align-items-center inner-banner">
					<div class="col-md-12 col-12 text-center">
						<nav aria-label="breadcrumb" class="page-breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="{{url('index')}}"><i class="isax isax-home-15"></i></a></li>
								<li class="breadcrumb-item">Doctor</li>
								<li class="breadcrumb-item active">Doctor Grid Full Width</li>
							</ol>
							<h2 class="breadcrumb-title">Doctor Grid Full Width</h2>
						</nav>
					</div>
				</div>
				<div class="bg-primary-gradient rounded-pill doctors-search-box">
					<div class="search-box-one rounded-pill">
						<form action="{{ route('patient.search') }}" method="GET"> 
							<div class="search-input search-line">
								<i class="isax isax-hospital5 bficon"></i>
								<div class=" mb-0">
									<input type="text" name="search" class="form-control" placeholder="Search for Psychologists" value="{{ request('search') }}">
								</div>
							</div>
							<div class="search-input search-map-line">
								<i class="isax isax-briefcase"></i>
								<div class=" mb-0">
									<select name="specialization" class="form-control">
										<option value="">All Specializations</option>
										@foreach($specializations ?? [] as $spec)
										<option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>{{ $spec }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="search-input search-calendar-line">
								<i class="isax isax-dollar-circle"></i>
								<div class=" mb-0">
									<input type="number" name="max_fee" class="form-control" placeholder="Max Fee" value="{{ request('max_fee') }}">
								</div>
							</div>
							<div class="form-search-btn">
								<button class="btn btn-primary d-inline-flex align-items-center rounded-pill" type="submit"><i class="isax isax-search-normal-15 me-2"></i>Search</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="breadcrumb-bg">
				<img src="{{URL::asset('assets/img/bg/breadcrumb-bg-01.png')}}" alt="img" class="breadcrumb-bg-01">
				<img src="{{URL::asset('assets/img/bg/breadcrumb-bg-02.png')}}" alt="img" class="breadcrumb-bg-02">
				<img src="{{URL::asset('assets/img/bg/breadcrumb-icon.png')}}" alt="img" class="breadcrumb-bg-03">
				<img src="{{URL::asset('assets/img/bg/breadcrumb-icon.png')}}" alt="img" class="breadcrumb-bg-04">
			</div>
		</div>
		<!-- /Breadcrumb -->


    <!-- Page Content -->
    <div class="content mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-4">
                        <h3>Showing <span class="text-secondary">{{ $psychologists->total() ?? 0 }}</span> Psychologists For You</h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-end mb-4">
                        <div class="doctor-filter-availability me-3">
                            <p>Availability</p>
                            <div class="status-toggle status-tog">
                                <input type="checkbox" id="status_6" class="check">
                                <label for="status_6" class="checktoggle">checkbox</label>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-sm head-icon me-3" id="filter_search"><i class="isax isax-sort"></i></a>
                        <div class="dropdown header-dropdown">
                            <a class="dropdown-toggle sort-dropdown" data-bs-toggle="dropdown" href="javascript:void(0);" aria-expanded="false">
                                <span>Sort By</span>Price (Low to High)
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item">
                                    Price (Low to High)
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    Price (High to Low)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="filter_inputs">
                <div class="row align-items-center gy-3">
                    <div class="col-lg-9 mb-4">
                        <div class="row gx-3">
                            <div class="col-md col-sm-4 col-6">
                                <select class="select form-control">
                                    <option>Specialities</option>
                                    <option>Urology</option>
                                    <option>Psychiatry</option>
                                    <option>Psychiatry</option>
                                    <option>Cardiology</option>
                                </select>
                            </div>
                            <div class="col-md col-sm-4 col-6">
                                <select class="select form-control">
                                    <option>Hospitals</option>
                                    <option>Cleveland Clinic</option>
                                    <option>Apollo Hospital</option>
                                    <option>Apollo Hospital</option>
                                </select>
                            </div>
                            <div class="col-md col-sm-4 col-6">
                                <select class="select form-control">
                                    <option>Doctors</option>
                                    <option>Dr. Michael Brown</option>
                                    <option>Dr. Nicholas Tello</option>
                                    <option>Dr. Harold Bryant</option>
                                </select>
                            </div>
                            <div class="col-md col-sm-4 col-6">
                                <select class="select form-control">
                                    <option>Reviews</option>
                                    <option>5 Star</option>
                                    <option>4 Star</option>
                                    <option>3 Star</option>
                                </select>
                            </div>
                            <div class="col-md col-sm-4 col-6">
                                <select class="select form-control">
                                    <option>Clinic</option>
                                    <option>Bright Smiles Dental Clinic</option>
                                    <option>Family Care Clinic</option>
                                    <option>Express Health Clinic</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <div class="text-end">
                            <a href="#" class="fw-medium text-secondary text-decoration-underline">Clear All</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @forelse($psychologists ?? [] as $psychologist)
                <div class="col-xxl-3 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-img card-img-hover">
                            <a href="{{ route('patient.psychologist.show', $psychologist->id) }}">
                                <img src="{{ asset('assets/img/doctor-grid/doctor-grid-01.jpg') }}" alt="">
                            </a>
                            <div class="grid-overlay-item d-flex align-items-center justify-content-between">
                                @php
                                    $avgRating = $psychologist->feedbacks()->avg('rating') ?? 0;
                                @endphp
                                <span class="badge bg-orange"><i class="fa-solid fa-star me-1"></i>{{ number_format($avgRating, 1) }}</span>
                                <a href="javascript:void(0)" class="fav-icon">
                                    <i class="fa fa-heart"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="d-flex active-bar align-items-center justify-content-between p-3">
                                <a href="#" class="text-indigo fw-medium fs-14">{{ $psychologist->specialization }}</a>
                                <span class="badge bg-success-light d-inline-flex align-items-center">
                                    <i class="fa-solid fa-circle fs-5 me-1"></i>
                                    Available
                                </span>
                            </div>
                            <div class="p-3 pt-0">
                                <div class="doctor-info-detail mb-3 pb-3">
                                    <h3 class="mb-1"><a href="{{ route('patient.psychologist.show', $psychologist->id) }}">{{ $psychologist->user->name }}</a></h3>
                                    <div class="d-flex align-items-center">
                                        <p class="d-flex align-items-center mb-0 fs-14"><i class="isax isax-briefcase me-2"></i>{{ $psychologist->experience_years }} Years Experience</p>
                                        <i class="fa-solid fa-circle fs-5 text-primary mx-2 me-1"></i>
                                        <span class="fs-14 fw-medium">60 Min</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-1">Consultation Fees</p>
                                        <h3 class="text-orange">${{ number_format($psychologist->consultation_fee, 2) }}</h3>
                                    </div>
                                    <a href="{{ route('patient.appointments.create', $psychologist->id) }}" class="btn btn-md btn-dark inline-flex align-items-center rounded-pill">
                                        <i class="isax isax-calendar-1 me-2"></i>
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <p class="text-muted">No psychologists found. Please try different search criteria.</p>
                    </div>
                </div>
                @endforelse
                @if(isset($psychologists) && $psychologists->hasPages())
                <div class="col-md-12">
                    <div class="text-center mb-4">
                        {{ $psychologists->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection
