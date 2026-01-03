<?php $page = 'my-patients'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
		Doctor
        @endslot
        @slot('li_1')
            My Patients
        @endslot
		@slot('li_2')
		My Patients
	@endslot
    @endcomponent
    
		<!-- Page Content -->
		<div class="content doctor-content">
			<div class="container">

				<div class="row">
					<div class="col-lg-4 col-xl-3 theiaStickySidebar">

						<!-- Profile Sidebar -->
							<div class="profile-sidebar doctor-sidebar profile-sidebar-new">
								<div class="widget-profile pro-widget-content">
									<div class="profile-info-widget">
										<a href="{{url('doctor-profile')}}" class="booking-doc-img">
											<img src="{{URL::asset('assets/img/doctors-dashboard/doctor-profile-img.jpg')}}" alt="User Image">
										</a>
										<div class="profile-det-info">
											<h3><a href="{{ route('psychologist.profile') }}">{{ $psychologist->user->name ?? 'Psychologist' }}</a></h3>
											<div class="patient-details">
												<h5 class="mb-0">{{ $psychologist->specialization ?? 'Specialization' }}</h5>
											</div>
											<span class="badge doctor-role-badge"><i class="fa-solid fa-circle"></i>Psychologist</span>
										</div>
									</div>
								</div>
								<div class="doctor-available-head">
									<div class="input-block input-block-new">
										<label class="form-label">Availability <span class="text-danger">*</span></label>
										<select class="select form-control">
											<option>I am Available Now</option>
											<option>Not Available</option>
										</select>
									</div>
								</div>
								<div class="dashboard-widget">
									<nav class="dashboard-menu">
										<ul>
											<li>
												<a href="{{url('doctor-dashboard')}}">
													<i class="isax isax-category-2"></i>
													<span>Dashboard</span>
												</a>
											</li>
											<li>
												<a href="{{url('doctor-request')}}">
													<i class="isax isax-clipboard-tick"></i>
													<span>Requests</span>
													<small class="unread-msg">2</small>
												</a>
											</li>
											<li>
												<a href="{{url('appointments')}}">
													<i class="isax isax-calendar-1"></i>
													<span>Appointments</span>
												</a>
											</li>
											<li>
												<a href="{{url('available-timings')}}">
													<i class="isax isax-calendar-tick"></i>
													<span>Available Timings</span>
												</a>
											</li>
											<li class="active">
												<a href="{{url('my-patients')}}">
													<i class="fa-solid fa-user-injured"></i>
													<span>My Patients</span>
												</a>
											</li>
										</ul>
									</nav>
								</div>
							</div>
							<!-- /Profile Sidebar -->

					</div>
					<div class="col-lg-8 col-xl-9">

						<div class="dashboard-header">
							<h3>My Patients</h3>
							<ul class="header-list-btns">
								<li>
									<div class="input-block dash-search-input">
											<input type="text" class="form-control" placeholder="Search">
											<span class="search-icon"><i class="isax isax-search-normal"></i></span>
										</div>
								</li>
							</ul>
						</div>
						<div class="appointment-tab-head">
							<div class="appointment-tabs">
								<ul class="nav nav-pills inner-tab " id="pills-tab" role="tablist">
									<li class="nav-item" role="presentation">
										<button class="nav-link active" id="pills-upcoming-tab" data-bs-toggle="pill" data-bs-target="#pills-upcoming" type="button" role="tab" aria-controls="pills-upcoming" aria-selected="false">Active<span>200</span></button>
									</li>	
									<li class="nav-item" role="presentation">
										<button class="nav-link" id="pills-cancel-tab" data-bs-toggle="pill" data-bs-target="#pills-cancel" type="button" role="tab" aria-controls="pills-cancel" aria-selected="true">InActive<span>22</span></button>
									</li>
								</ul>
							</div>
							<div class="filter-head">
								<div class="position-relative daterange-wraper me-2">
									<div class="input-groupicon calender-input">
										<input type="text" class="form-control  date-range bookingrange" placeholder="From Date - To Date ">
									</div>
									<i class="isax isax-calendar-1"></i>
								</div>
							</div>
						</div>

						<div class="tab-content appointment-tab-content grid-patient">
							<div class="tab-pane fade show active" id="pills-upcoming" role="tabpanel" aria-labelledby="pills-upcoming-tab">
								<div class="row">
									@forelse($patients ?? [] as $patient)
									<!-- Appointment Grid -->
									<div class="col-xl-4 col-lg-6 col-md-6 d-flex">
										<div class="appointment-wrap appointment-grid-wrap">
											<ul>
												<li>
													<div class="appointment-grid-head">
														<div class="patinet-information">
															<a href="#">
																<img src="{{ asset('assets/index/patient.jpg') }}" alt="User Image">
															</a>
															<div class="patient-info">
																<p>#PAT{{ str_pad($patient->id, 4, '0', STR_PAD_LEFT) }}</p>
																<h6>{{ $patient->user->name }}</h6>
																<ul>
																	<li>Age : 
																		@if($patient->user->date_of_birth)
																			{{ \Carbon\Carbon::parse($patient->user->date_of_birth)->age }}
																		@else
																			N/A
																		@endif
																	</li>
																	<li>{{ ucfirst($patient->user->gender ?? 'N/A') }}</li>
																	<li>{{ $patient->user->phone ?? 'N/A' }}</li>
																</ul>
															</div>
														</div>
													</div>
												</li>
												<li class="appointment-info">
													<p><i class="isax isax-envelope"></i>{{ $patient->user->email }}</p>
													<p class="mb-0"><i class="isax isax-location5"></i>{{ $patient->user->address ?? 'N/A' }}</p>
												</li>
												<li class="appointment-action">
													<div class="patient-book">
														<p><i class="isax isax-calendar-1"></i>Last Booking 
															<span>
																@if($patient->last_appointment)
																	{{ $patient->last_appointment->appointment_date->format('M d, Y') }}
																@else
																	Never
																@endif
															</span>
														</p>
													</div>
												</li>
											</ul>
										</div>
									</div>
									<!-- /Appointment Grid -->
									@empty
									<div class="col-12">
										<div class="text-center py-5">
											<p>No patients found</p>
										</div>
									</div>
									@endforelse
								</div>
							</div>
						</div>

					</div>
				</div>

			</div>

		</div>
		<!-- /Page Content -->

  @endsection
