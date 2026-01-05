<?php $page = 'psychologist-profile'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Psychologist
        @endslot
        @slot('li_1')
            Profile
        @endslot
        @slot('li_2')
            Profile Management
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
                        <h3>Profile Management</h3>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Profile Tabs -->
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                                        <i class="fa-solid fa-user me-2"></i>Personal Information
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="professional-tab" data-bs-toggle="tab" data-bs-target="#professional" type="button" role="tab">
                                        <i class="fa-solid fa-briefcase me-2"></i>Professional Information
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="qualifications-tab" data-bs-toggle="tab" data-bs-target="#qualifications" type="button" role="tab">
                                        <i class="fa-solid fa-certificate me-2"></i>Qualifications
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="availability-tab" data-bs-toggle="tab" data-bs-target="#availability" type="button" role="tab">
                                        <i class="fa-solid fa-calendar-check me-2"></i>Availability
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content mt-4" id="profileTabsContent">
                                <!-- Personal Information Tab -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <form action="{{ route('psychologist.profile.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="section" value="personal">
                                
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <h5>Personal Information</h5>
                                                <p class="text-muted">Update your personal details and contact information</p>
                                            </div>
                                            
                                            <!-- Profile Image Upload -->
                                            <div class="col-md-12 mb-4">
                                                <div class="form-group">
                                                    <label class="form-label">Profile Picture</label>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            @php
                                                                $profileImage = $user->profile_image 
                                                                    ? asset('storage/' . $user->profile_image) 
                                                                    : asset('assets/img/doctors/doc-profile-02.jpg');
                                                            @endphp
                                                            <img src="{{ $profileImage }}" alt="Profile" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #e0e0e0;">
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <input type="file" name="profile_image" class="form-control" accept="image/jpeg,image/jpg,image/png,image/gif">
                                                            <small class="form-text text-muted">Upload a profile picture (JPG, PNG, or GIF. Max size: 2MB)</small>
                                                            @if($user->profile_image)
                                                                <div class="mt-2">
                                                                    <button type="button" class="btn btn-sm btn-danger" onclick="document.getElementById('remove_image').value='1'; this.closest('form').submit();">
                                                                        <i class="fa-solid fa-trash me-1"></i>Remove Current Image
                                                                    </button>
                                                                    <input type="hidden" name="remove_image" id="remove_image" value="0">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Full Name</label>
                                                    <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                                                    <small class="form-text text-muted">Name cannot be changed</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                                    <small class="form-text text-muted">Email cannot be changed</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Phone Number</label>
                                                    <input type="text" name="phone" class="form-control" value="{{ $user->phone ?? '' }}" placeholder="Enter phone number">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Date of Birth</label>
                                                    <input type="date" name="date_of_birth" class="form-control" value="{{ $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '' }}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Gender</label>
                                                    <select name="gender" class="form-control">
                                                        <option value="">Select Gender</option>
                                                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                        <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Address</label>
                                                    <textarea name="address" class="form-control" rows="3" placeholder="Enter your address">{{ $user->address ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa-solid fa-save me-2"></i>Save Changes
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Professional Information Tab -->
                                <div class="tab-pane fade" id="professional" role="tabpanel">
                                    <form action="{{ route('psychologist.profile.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="section" value="professional">
                                
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <h5>Professional Information</h5>
                                                <p class="text-muted">Update your professional details, experience, and consultation fee</p>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Specialization <span class="text-danger">*</span></label>
                                                    <select name="specialization" class="form-control" required>
                                                        <option value="">Select Specialization</option>
                                                        <option value="Anxiety Disorders" {{ $psychologist->specialization == 'Anxiety Disorders' ? 'selected' : '' }}>Anxiety Disorders</option>
                                                        <option value="Depression" {{ $psychologist->specialization == 'Depression' ? 'selected' : '' }}>Depression</option>
                                                        <option value="Trauma and PTSD" {{ $psychologist->specialization == 'Trauma and PTSD' ? 'selected' : '' }}>Trauma and PTSD</option>
                                                        <option value="Relationship Counseling" {{ $psychologist->specialization == 'Relationship Counseling' ? 'selected' : '' }}>Relationship Counseling</option>
                                                        <option value="Addiction Counseling" {{ $psychologist->specialization == 'Addiction Counseling' ? 'selected' : '' }}>Addiction Counseling</option>
                                                        <option value="Child and Adolescent Psychology" {{ $psychologist->specialization == 'Child and Adolescent Psychology' ? 'selected' : '' }}>Child and Adolescent Psychology</option>
                                                        <option value="Eating Disorders" {{ $psychologist->specialization == 'Eating Disorders' ? 'selected' : '' }}>Eating Disorders</option>
                                                        <option value="Self-Esteem Issues" {{ $psychologist->specialization == 'Self-Esteem Issues' ? 'selected' : '' }}>Self-Esteem Issues</option>
                                                        <option value="Stress Management" {{ $psychologist->specialization == 'Stress Management' ? 'selected' : '' }}>Stress Management</option>
                                                        <option value="Grief Counseling" {{ $psychologist->specialization == 'Grief Counseling' ? 'selected' : '' }}>Grief Counseling</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                                    <input type="number" name="experience_years" class="form-control" value="{{ $psychologist->experience_years ?? '' }}" min="0" max="100" required placeholder="Enter years of experience">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Consultation Fee ($) <span class="text-danger">*</span></label>
                                                    <input type="number" name="consultation_fee" class="form-control" value="{{ $psychologist->consultation_fee ?? '' }}" min="0" step="0.01" required placeholder="Enter consultation fee">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Bio / Professional Summary</label>
                                                    <textarea name="bio" class="form-control" rows="6" placeholder="Write a brief bio about yourself, your approach to therapy, and your professional background...">{{ $psychologist->bio ?? '' }}</textarea>
                                                    <small class="form-text text-muted">This will be visible to patients on your profile</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa-solid fa-save me-2"></i>Save Changes
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Qualifications Tab -->
                                <div class="tab-pane fade" id="qualifications" role="tabpanel">
                                    <form action="{{ route('psychologist.profile.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="section" value="qualifications">
                                
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <h5>Certifications & Qualifications</h5>
                                                <p class="text-muted">Upload your professional certifications and qualifications</p>
                                            </div>
                                            
                                            <!-- Existing Qualifications -->
                                            @if($psychologist->qualifications && count($psychologist->qualifications) > 0)
                                                <div class="col-md-12 mb-4">
                                                    <label class="form-label">Current Qualifications</label>
                                                    <div class="row">
                                                        @foreach($psychologist->qualifications as $index => $qualification)
                                                            <div class="col-md-4 mb-3">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                                            <h6 class="mb-0">Qualification {{ $index + 1 }}</h6>
                                                                            <form action="{{ route('psychologist.profile.update') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this qualification?');">
                                                                                @csrf
                                                                                <input type="hidden" name="section" value="qualifications">
                                                                                <input type="hidden" name="remove_qualification" value="{{ $index }}">
                                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                                    <i class="fa-solid fa-trash"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                        <a href="{{ route('psychologist.qualification.view', $index) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                            <i class="fa-solid fa-eye me-1"></i>View
                                                                        </a>
                                                                        <a href="{{ route('psychologist.qualification.download', $index) }}" class="btn btn-sm btn-secondary">
                                                                            <i class="fa-solid fa-download me-1"></i>Download
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-12 mb-4">
                                                    <div class="alert alert-info">
                                                        <i class="fa-solid fa-info-circle me-2"></i>No qualifications uploaded yet.
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- Upload New Qualifications -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Upload New Qualifications</label>
                                                    <input type="file" name="qualification_files[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" multiple>
                                                    <small class="form-text text-muted">You can upload multiple files. Accepted formats: PDF, JPG, PNG. Max size per file: 2MB</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa-solid fa-upload me-2"></i>Upload Qualifications
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Availability Tab -->
                                <div class="tab-pane fade" id="availability" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <h5>Weekly Availability</h5>
                                            <p class="text-muted">Manage your weekly schedule and available time slots</p>
                                        </div>
                                        
                                        @php
                                            $days = [
                                                0 => 'Sunday',
                                                1 => 'Monday',
                                                2 => 'Tuesday',
                                                3 => 'Wednesday',
                                                4 => 'Thursday',
                                                5 => 'Friday',
                                                6 => 'Saturday'
                                            ];
                                        @endphp
                                        
                                        @foreach($days as $dayNum => $dayName)
                                            <div class="col-md-12 mb-4">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h6 class="mb-0">{{ $dayName }}</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        @php
                                                            $dayAvailabilities = $availabilities->get($dayNum, collect());
                                                        @endphp
                                                        
                                                        @if($dayAvailabilities->count() > 0)
                                                            <div class="table-responsive">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Start Time</th>
                                                                            <th>End Time</th>
                                                                            <th>Status</th>
                                                                            <th>Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($dayAvailabilities as $availability)
                                                                            <tr>
                                                                                <td>{{ \Carbon\Carbon::parse($availability->start_time)->format('h:i A') }}</td>
                                                                                <td>{{ \Carbon\Carbon::parse($availability->end_time)->format('h:i A') }}</td>
                                                                                <td>
                                                                                    @if($availability->is_available)
                                                                                        <span class="badge bg-success">Available</span>
                                                                                    @else
                                                                                        <span class="badge bg-danger">Unavailable</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editAvailabilityModal{{ $availability->id }}">
                                                                                        <i class="fa-solid fa-edit"></i>
                                                                                    </button>
                                                                                    <form action="{{ route('psychologist.availability.destroy', $availability) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this availability?');">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                                                            <i class="fa-solid fa-trash"></i>
                                                                                        </button>
                                                                                    </form>
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            <!-- Edit Modal -->
                                                                            <div class="modal fade" id="editAvailabilityModal{{ $availability->id }}" tabindex="-1">
                                                                                <div class="modal-dialog">
                                                                                    <div class="modal-content">
                                                                                        <form action="{{ route('psychologist.availability.update', $availability) }}" method="POST">
                                                                                            @csrf
                                                                                            @method('PUT')
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title">Edit Availability - {{ $dayName }}</h5>
                                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <div class="form-group mb-3">
                                                                                                    <label class="form-label">Start Time</label>
                                                                                                    <input type="time" name="start_time" class="form-control" value="{{ $availability->start_time }}" required>
                                                                                                </div>
                                                                                                <div class="form-group mb-3">
                                                                                                    <label class="form-label">End Time</label>
                                                                                                    <input type="time" name="end_time" class="form-control" value="{{ $availability->end_time }}" required>
                                                                                                </div>
                                                                                                <div class="form-group mb-3">
                                                                                                    <div class="form-check">
                                                                                                        <input type="checkbox" name="is_available" class="form-check-input" id="is_available{{ $availability->id }}" value="1" {{ $availability->is_available ? 'checked' : '' }}>
                                                                                                        <label class="form-check-label" for="is_available{{ $availability->id }}">
                                                                                                            Available
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @else
                                                            <p class="text-muted mb-0">No availability set for this day.</p>
                                                        @endif
                                                        
                                                        <div class="mt-3">
                                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAvailabilityModal{{ $dayNum }}">
                                                                <i class="fa-solid fa-plus me-1"></i>Add Time Slot
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Add Modal -->
                                                <div class="modal fade" id="addAvailabilityModal{{ $dayNum }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('psychologist.availability.store') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="day_of_week" value="{{ $dayNum }}">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Add Availability - {{ $dayName }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label">Start Time</label>
                                                                        <input type="time" name="start_time" class="form-control" required>
                                                                    </div>
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label">End Time</label>
                                                                        <input type="time" name="end_time" class="form-control" required>
                                                                    </div>
                                                                    <div class="form-group mb-3">
                                                                        <div class="form-check">
                                                                            <input type="checkbox" name="is_available" class="form-check-input" id="is_available_new{{ $dayNum }}" value="1" checked>
                                                                            <label class="form-check-label" for="is_available_new{{ $dayNum }}">
                                                                                Available
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Add Availability</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

