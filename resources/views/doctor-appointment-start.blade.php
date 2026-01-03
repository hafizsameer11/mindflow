<?php $page = 'doctor-appointment-start'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
		Doctor
        @endslot
        @slot('li_1')
        Appointment Details   
        @endslot
		@slot('li_2')
        Appointment Details   
        @endslot
    @endcomponent
	
    	<!-- Page Content -->
			<div class="content">
				<div class="container">

					<div class="row">
						<div class="col-lg-4 col-xl-3 theiaStickySidebar">
							
							
							@component('components.sidebar_doctor')
	                        @endcomponent 
						</div>
						
						<div class="col-lg-8 col-xl-9">
							<div class="dashboard-header">
								<div class="header-back">
									<a href="{{url('appointments')}}" class="back-arrow"><i class="fa-solid fa-arrow-left"></i></a>
									<h3>Appointment Details</h3>
								</div>
							</div>
							<div class="appointment-details-wrap">
								
								<!-- Appointment Detail Card -->
								<div class="appointment-wrap appointment-detail-card">
									<ul>
										<li>
											<div class="patinet-information">
												<a href="#">
													<img src="{{URL::asset('/assets/img/doctors-dashboard/profile-02.jpg')}}" alt="User Image">
												</a>
												<div class="patient-info">
													<p>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</p>
													<h6><a href="#">{{ $appointment->patient->user->name }}</a></h6>
													<div class="mail-info-patient">
														<ul>
															<li><i class="fa-solid fa-envelope"></i>{{ $appointment->patient->user->email }}</li>
															<li><i class="fa-solid fa-phone"></i>{{ $appointment->patient->user->phone ?? 'N/A' }}</li>
														</ul>
													</div>
												</div>
											</div>
										</li>
										<li class="appointment-info">
											<div class="person-info">
												<p>Specialization</p>
												<ul class="d-flex apponitment-types">
													<li>{{ $appointment->psychologist->specialization }}</li>
												</ul>
											</div>
											<div class="person-info">
												<p>Type of Appointment</p>
												<ul class="d-flex apponitment-types">
													<li><i class="fa-solid fa-video text-green"></i>Video Meeting</li>
												</ul>
											</div>
										</li>
										<li class="appointment-action">
											<div class="detail-badge-info">
												@if($appointment->status == 'pending')
													<span class="badge bg-yellow">Pending</span>
												@elseif($appointment->status == 'confirmed')
													<span class="badge bg-success">Confirmed</span>
												@elseif($appointment->status == 'completed')
													<span class="badge bg-info">Completed</span>
												@else
													<span class="badge bg-danger">Cancelled</span>
												@endif
											</div>
											<div class="consult-fees">
												<h6>Consultation Fees : ${{ number_format($appointment->psychologist->consultation_fee, 2) }}</h6>
											</div>
											<ul>
												<li>
													@if($appointment->status == 'confirmed' && $appointment->meeting_link)
														<a href="{{ route('psychologist.sessions.start', $appointment) }}" class="btn btn-primary btn-sm">
															<i class="fa-solid fa-video"></i> Start Session
														</a>
													@endif
												</li>
											</ul>
										</li>
									</ul>
									<ul class="detail-card-bottom-info">
										<li>
											<h6>Appointment Date & Time</h6>
											<span>{{ $appointment->appointment_date->format('M d, Y') }} - {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
										</li>
										<li>
											<h6>Duration</h6>
											<span>{{ $appointment->duration }} Minutes</span>
										</li>
										<li>
											<h6>Patient Address</h6>
											<span>{{ $appointment->patient->user->address ?? 'N/A' }}</span>
										</li>
										<li>
											<h6>Consultation Type</h6>
											<span>Video Meeting</span>
										</li>
										@if($appointment->status == 'confirmed' && $appointment->meeting_link)
										<li>
											<div class="start-btn">
												<a href="{{ route('psychologist.sessions.start', $appointment) }}" class="btn btn-primary">Start Video Session</a>
											</div>
										</li>
										@endif
									</ul>
								</div>
								<!-- /Appointment Detail Card -->

								@if($appointment->status == 'confirmed' && $appointment->meeting_link)
								<!-- Video Meeting Section -->
								<div class="card mb-4">
									<div class="card-header">
										<h5>Video Meeting</h5>
									</div>
									<div class="card-body">
										<div id="jitsi-container" style="width: 100%; height: 500px; background: #000; border-radius: 8px;"></div>
										<div class="mt-3">
											<p><strong>Meeting Link:</strong> {{ $appointment->meeting_link }}</p>
											<p><strong>Patient:</strong> {{ $appointment->patient->user->name }}</p>
										</div>
									</div>
								</div>
								@endif

								<div class="create-appointment-details">
									<div class="session-end-head">
										<h6><span>Session Ends in</span>08M:00S</h6>
									</div>
									<h5 class="head-text">Create Appointment Details</h5>
									<div class="create-details-card">
										<div class="create-details-card-head">
											<div class="card-title-text">
												<h5>Patient Information</h5>
											</div>
											<div class="patient-info-box">
												<div class="row">
													<div class="col-xl-3 col-md-6">
														<ul class="info-list">
															<li>Age / Gender</li>
															<li><h6>
																@if($appointment->patient->user->date_of_birth)
																	{{ \Carbon\Carbon::parse($appointment->patient->user->date_of_birth)->age }} Years
																@else
																	N/A
																@endif
																/ {{ ucfirst($appointment->patient->user->gender ?? 'N/A') }}
															</h6></li>
														</ul>
													</div>
													<div class="col-xl-3 col-md-6">
														<ul class="info-list">
															<li>Address</li>
															<li><h6>{{ $appointment->patient->user->address ?? 'N/A' }}</h6></li>
														</ul>
													</div>
													<div class="col-xl-3 col-md-6">
														<ul class="info-list">
															<li>Phone</li>
															<li><h6>{{ $appointment->patient->user->phone ?? 'N/A' }}</h6></li>
														</ul>
													</div>
													<div class="col-xl-3 col-md-6">
														<ul class="info-list">
															<li>No of Visits</li>
															<li><h6>{{ \App\Models\Appointment::where('patient_id', $appointment->patient_id)->where('status', 'completed')->count() }}</h6></li>
														</ul>
													</div>
												</div>
											</div>
										</div>
										<div class="create-details-card-body">
											<form action="{{ route('psychologist.prescriptions.store', $appointment) }}" method="POST">
												@csrf
												<div class="start-appointment-set">
													<div class="form-bg-title">
														<h5>Vitals</h5>
													</div>
													<div class="row">
														<div class="col-xl-3 col-md-6">
															<div class="input-block input-block-new">
																<label class="form-label">Temprature</label>
																<div class="input-text-field">
																	<input type="text" class="form-control" placeholder="Eg : 97.8">
																	<span class="input-group-text">F</span>
																</div>									
															</div>
														</div>
														<div class="col-xl-3 col-md-6">
															<div class="input-block input-block-new">
																<label class="form-label">Pulse</label>
																<div class="input-text-field">
																	<input type="text" class="form-control" placeholder="454">
																	<span class="input-group-text">mmHg</span>
																</div>									
															</div>
														</div>
														<div class="col-xl-3 col-md-6">
															<div class="input-block input-block-new">
																<label class="form-label">Respiratory Rate</label>
																<div class="input-text-field">
																	<input type="text" class="form-control" placeholder="Eg : 97.8">
																	<span class="input-group-text">rpm</span>
																</div>									
															</div>
														</div>
														<div class="col-xl-3 col-md-6">
															<div class="input-block input-block-new">
																<label class="form-label">SPO2</label>
																<div class="input-text-field">
																	<input type="text" class="form-control" placeholder="Eg : 98">
																	<span class="input-group-text">%</span>
																</div>									
															</div>
														</div>
														<div class="col-xl-3 col-md-6">
															<div class="input-block input-block-new">
																<label class="form-label">Height</label>
																<div class="input-text-field">
																	<input type="text" class="form-control" placeholder="Eg : 97.8">
																	<span class="input-group-text">cm</span>
																</div>									
															</div>
														</div>
														<div class="col-xl-3 col-md-6">
															<div class="input-block input-block-new">
																<label class="form-label">Weight</label>
																<div class="input-text-field">
																	<input type="text" class="form-control" placeholder="Eg : 97.8">
																	<span class="input-group-text">Kg</span>
																</div>									
															</div>
														</div>
														<div class="col-xl-3 col-md-6">
															<div class="input-block input-block-new">
																<label class="form-label">Waist</label>
																<div class="input-text-field">
																	<input type="text" class="form-control" placeholder="Eg : 97.8">
																	<span class="input-group-text">cm</span>
																</div>									
															</div>
														</div>
														<div class="col-xl-3 col-md-6">
															<div class="input-block input-block-new">
																<label class="form-label">BSA</label>
																<div class="input-text-field">
																	<input type="text" class="form-control" placeholder="Eg : 54">
																	<span class="input-group-text">M</span>
																</div>									
															</div>
														</div>
														<div class="col-xl-3 col-md-6">
															<div class="input-block input-block-new">
																<label class="form-label">BMI</label>
																<div class="input-text-field">
																	<input type="text" class="form-control" placeholder="Eg : 454">
																	<span class="input-group-text">kg/cm</span>
																</div>									
															</div>
														</div>
													</div>
												</div>
												<div class="start-appointment-set">
													<div class="form-bg-title">
														<h5>Previous Medical History</h5>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="input-block input-block-new">
																<textarea name="medical_history" class="form-control" rows="3" placeholder="Previous Medical History">{{ $appointment->patient->medical_history ?? '' }}</textarea>
															</div>
														</div>
													</div>
												</div>
												<div class="start-appointment-set">
													<div class="form-bg-title">
														<h5>Session Notes</h5>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="input-block input-block-new">
																<textarea name="notes" class="form-control" rows="3" placeholder="Clinical Notes">{{ $appointment->notes ?? '' }}</textarea>
															</div>												
														</div>
													</div>
												</div>
												<div class="start-appointment-set">
													<div class="form-bg-title">
														<h5>Clinical Notes</h5>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="input-block input-block-new">
																<input class="input-tags form-control" id="inputBox" type="text" data-role="tagsinput" placeholder="Type New"  name="Label" value="Skin Allergy" >
																<a href="#" class="input-text save-btn">Save</a>
															</div>												
														</div>
													</div>
												</div>
												<div class="start-appointment-set">
													<div class="form-bg-title">
														<h5>Laboratory Tests</h5>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="input-block input-block-new">
																<input class="input-tags form-control" id="inputBox2" type="text" data-role="tagsinput" placeholder="Type New"  name="Label" value="Hemoglobin A1c (HbA1c),Liver Function Tests (LFTs)" >
																<a href="#" class="input-text save-btn">Save</a>
															</div>												
														</div>
													</div>
												</div>
												<div class="start-appointment-set">
													<div class="form-bg-title">
														<h5>Complaints</h5>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="input-block input-block-new">
																<input class="input-tags form-control" id="inputBox3" type="text" data-role="tagsinput" placeholder="Type New"  name="Label" value="Fever,Headache,Stomach Pain" >
																<a href="#" class="input-text save-btn">Save</a>
															</div>												
														</div>
													</div>
												</div>
												<div class="start-appointment-set">
													<div class="form-bg-title">
														<h5>Diagonosis</h5>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="input-block input-block-new">
																<div class="input-field-set">
																	<label class="form-label">Fever</label>
																	<input type="text" class="form-control" placeholder="Diagnosis">
																</div>										
															</div>										
														</div>
														<div class="col-md-12">
															<div class="input-block input-block-new">
																<div class="input-field-set">
																	<label class="form-label">Headache</label>
																	<input type="text" class="form-control" placeholder="Diagnosis">
																</div>										
															</div>										
														</div>
														<div class="col-md-12">
															<div class="input-block input-block-new">
																<div class="input-field-set">
																	<label class="form-label">Stomach Pain</label>
																	<input type="text" class="form-control" placeholder="Diagnosis">
																</div>										
															</div>										
														</div>
													</div>
												</div>
												<div class="start-appointment-set">
													<div class="form-bg-title">
														<h5>Medications</h5>
													</div>
													<div class="row meditation-row">
														<div class="col-md-12">
															<div class="d-flex flex-wrap medication-wrap align-items-center">
																<div class="input-block input-block-new">
																	<label class="form-label">Name</label>
																	<input type="text" class="form-control">
																</div>	
																<div class="input-block input-block-new">
																	<label class="form-label">Type</label>
																	<select class="select form-control">
																		<option>Select</option>
																		<option>Direct Visit</option>
																		<option>Video Call</option>
																	</select>
																</div>	
																<div class="input-block input-block-new">
																	<label class="form-label">Dosage</label>
																	<input type="text" class="form-control">
																</div>	
																<div class="input-block input-block-new">
																	<label class="form-label">Duration</label>
																	<input type="text" class="form-control" placeholder="1-0-0">
																</div>	
																<div class="input-block input-block-new">
																	<label class="form-label">Duration</label>
																	<select class="select form-control">
																		<option>Select</option>
																		<option>Not Available</option>
																	</select>
																</div>	
																<div class="input-block input-block-new">
																	<label class="form-label">Instruction</label>
																	<input type="text" class="form-control">
																</div>	
																<div class="delete-row">
																	<a href="#" class="delete-btn delete-medication trash text-danger"><i class="fa-solid fa-trash-can"></i></a>
																</div>
															</div>
															<div class="add-new-med text-end mb-4">
																<a href="#" class="add-medical more-item mb-0">Add New</a>
															</div>											
														</div>
													</div>
												</div>
												<div class="start-appointment-set">
													<div class="form-bg-title">
														<h5>Advice</h5>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="input-block input-block-new">
																<textarea name="advice" class="form-control" rows="3" placeholder="Advice for patient"></textarea>
															</div>
														</div>
													</div>
												</div>
												<div class="start-appointment-set">
													<div class="form-bg-title">
														<h5>Follow Up</h5>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="input-block input-block-new">
																<textarea name="follow_up" class="form-control" rows="3" placeholder="Follow up instructions"></textarea>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-set-button">
														<a href="{{ route('psychologist.appointments.index') }}" class="btn btn-light">Cancel</a>
														<button class="btn btn-primary" type="submit">Save Notes</button>
														@if($appointment->status == 'confirmed')
														<a href="{{ route('psychologist.sessions.end', $appointment) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to end this session?')">End Session</a>
														@endif
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>

			</div>		
			<!-- /Page Content -->

@if($appointment->status == 'confirmed' && $appointment->meeting_link)
@push('scripts')
<!-- Jitsi Meet External API -->
<script src="https://8x8.vc/external_api.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const domain = 'meet.jit.si';
        const options = {
            roomName: '{{ $appointment->meeting_link }}',
            width: '100%',
            height: 500,
            parentNode: document.querySelector('#jitsi-container'),
            configOverwrite: {
                startWithAudioMuted: false,
                startWithVideoMuted: false,
                enableNoAudioDetection: true,
                enableNoisyMicDetection: true,
                // Force video to be enabled - no audio-only mode
                disableAudio: false,
                disableVideo: false,
                constraints: {
                    video: {
                        height: { ideal: 720, max: 1080, min: 240 },
                        width: { ideal: 1280, max: 1920, min: 320 }
                    }
                }
            },
            interfaceConfigOverwrite: {
                TOOLBAR_BUTTONS: [
                    'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                    'fodeviceselection', 'hangup', 'chat', 'settings', 'videoquality',
                    'filmstrip', 'feedback', 'stats', 'shortcuts', 'tileview'
                ],
                SETTINGS_SECTIONS: ['devices', 'language', 'moderator', 'profile'],
                DEFAULT_BACKGROUND: '#474747',
                INITIAL_TOOLBAR_TIMEOUT: 20000,
                TOOLBAR_TIMEOUT: 4000
            },
            userInfo: {
                displayName: '{{ Auth::user()->name }}',
                email: '{{ Auth::user()->email }}'
            }
        };

        const api = new JitsiMeetExternalAPI(domain, options);

        api.addEventListener('videoConferenceJoined', function() {
            console.log('Psychologist joined video conference');
        });

        api.addEventListener('readyToClose', function() {
            console.log('Meeting ended');
        });
    });
</script>
@endpush
@endif
   
    @endsection