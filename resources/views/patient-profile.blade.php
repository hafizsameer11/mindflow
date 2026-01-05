<?php $page = 'patient-profile'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            Profile
        @endslot
        @slot('li_2')
        Profile Settings
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
                                    <button class="nav-link" id="medical-tab" data-bs-toggle="tab" data-bs-target="#medical" type="button" role="tab">
                                        <i class="fa-solid fa-heart-pulse me-2"></i>Medical History
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">
                                        <i class="fa-solid fa-lock me-2"></i>Account Settings
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab">
                                        <i class="fa-solid fa-sliders me-2"></i>Preferences
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content mt-4" id="profileTabsContent">
                                <!-- Personal Information Tab -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                    <form action="{{ route('patient.profile.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                
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
                                                                $profileImage = Auth::user()->profile_image 
                                                                    ? asset('storage/' . Auth::user()->profile_image) 
                                                                    : asset('assets/index/doctor-profile-img.jpg');
                                                            @endphp
                                                            <img src="{{ $profileImage }}" alt="Profile" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #e0e0e0;">
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <input type="file" name="profile_image" class="form-control" accept="image/jpeg,image/jpg,image/png,image/gif">
                                                            <small class="form-text text-muted">Upload a profile picture (JPG, PNG, or GIF. Max size: 2MB)</small>
                                                            @if(Auth::user()->profile_image)
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
                                                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                                                    <small class="form-text text-muted">Name cannot be changed</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                                                    <small class="form-text text-muted">Email cannot be changed</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                                    <input type="text" name="phone" class="form-control" value="{{ Auth::user()->phone ?? '' }}" placeholder="Enter phone number" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Date of Birth</label>
                                                    <input type="date" name="date_of_birth" class="form-control" value="{{ Auth::user()->date_of_birth ?? '' }}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Gender</label>
                                                    <select name="gender" class="form-control">
                                                        <option value="">Select Gender</option>
                                                        <option value="male" {{ Auth::user()->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                        <option value="female" {{ Auth::user()->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                        <option value="other" {{ Auth::user()->gender == 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Address</label>
                                                    <input type="text" name="address" class="form-control" value="{{ Auth::user()->address ?? '' }}" placeholder="Enter address">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa-solid fa-save me-2"></i>Update Personal Information
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Medical History Tab -->
                                <div class="tab-pane fade" id="medical" role="tabpanel">
                                    <form action="{{ route('patient.profile.update') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <h5>Medical Information</h5>
                                                <p class="text-muted">Keep your medical history and emergency contact information up to date</p>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Medical History</label>
                                                    <textarea name="medical_history" class="form-control" rows="8" placeholder="Enter your medical history, allergies, medications, or any relevant health conditions">{{ $patient ? $patient->medical_history : '' }}</textarea>
                                                    <small class="form-text text-muted">Please provide any relevant medical history, allergies, current medications, or conditions that your psychologist should be aware of</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Emergency Contact</label>
                                                    <input type="text" name="emergency_contact" class="form-control" value="{{ $patient ? $patient->emergency_contact : '' }}" placeholder="Enter emergency contact name and phone number">
                                                    <small class="form-text text-muted">Name and phone number of emergency contact person (e.g., John Doe - +1234567890)</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa-solid fa-save me-2"></i>Update Medical Information
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Account Settings Tab -->
                                <div class="tab-pane fade" id="account" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <h5>Account Settings</h5>
                                            <p class="text-muted">Manage your account security and password</p>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <h6 class="mb-3"><i class="fa-solid fa-key me-2"></i>Change Password</h6>
                                                    <form action="{{ route('patient.profile.update-password') }}" method="POST" id="passwordForm">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Current Password</label>
                                                                    <input type="password" name="current_password" class="form-control" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">New Password</label>
                                                                    <input type="password" name="password" class="form-control" minlength="8" required>
                                                                    <small class="form-text text-muted">Minimum 8 characters</small>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Confirm New Password</label>
                                                                    <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa-solid fa-key me-2"></i>Update Password
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preferences Tab -->
                                <div class="tab-pane fade" id="preferences" role="tabpanel">
                                    <form action="{{ route('patient.profile.update-preferences') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <h5>Notification Preferences</h5>
                                                <p class="text-muted">Choose how you want to receive notifications</p>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <div class="form-check form-switch mb-3">
                                                            <input class="form-check-input" type="checkbox" name="email_notifications" id="emailNotifications" value="1" checked>
                                                            <label class="form-check-label" for="emailNotifications">
                                                                <strong>Email Notifications</strong>
                                                                <p class="text-muted mb-0 small">Receive appointment reminders and updates via email</p>
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="form-check form-switch mb-3">
                                                            <input class="form-check-input" type="checkbox" name="sms_notifications" id="smsNotifications" value="1">
                                                            <label class="form-check-label" for="smsNotifications">
                                                                <strong>SMS Notifications</strong>
                                                                <p class="text-muted mb-0 small">Receive appointment reminders via SMS</p>
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="form-check form-switch mb-3">
                                                            <input class="form-check-input" type="checkbox" name="appointment_reminders" id="appointmentReminders" value="1" checked>
                                                            <label class="form-check-label" for="appointmentReminders">
                                                                <strong>Appointment Reminders</strong>
                                                                <p class="text-muted mb-0 small">Get reminded 24 hours before your appointment</p>
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="form-check form-switch mb-3">
                                                            <input class="form-check-input" type="checkbox" name="payment_notifications" id="paymentNotifications" value="1" checked>
                                                            <label class="form-check-label" for="paymentNotifications">
                                                                <strong>Payment Notifications</strong>
                                                                <p class="text-muted mb-0 small">Receive notifications about payment status</p>
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="marketing_emails" id="marketingEmails" value="1">
                                                            <label class="form-check-label" for="marketingEmails">
                                                                <strong>Marketing Emails</strong>
                                                                <p class="text-muted mb-0 small">Receive updates about new features and services</p>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h5 class="mb-3">Privacy Settings</h5>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <div class="form-check form-switch mb-3">
                                                            <input class="form-check-input" type="checkbox" name="profile_visibility" id="profileVisibility" value="1" checked>
                                                            <label class="form-check-label" for="profileVisibility">
                                                                <strong>Profile Visibility</strong>
                                                                <p class="text-muted mb-0 small">Allow psychologists to view your basic profile information</p>
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="data_sharing" id="dataSharing" value="1">
                                                            <label class="form-check-label" for="dataSharing">
                                                                <strong>Data Sharing for Research</strong>
                                                                <p class="text-muted mb-0 small">Allow anonymized data to be used for research purposes</p>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa-solid fa-save me-2"></i>Save Preferences
                                                </button>
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
@endsection

