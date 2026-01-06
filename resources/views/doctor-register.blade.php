<?php $page = 'doctor-register'; ?>
@extends('layout.mainlayout')
@section('content')
<style>
    .login-left {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        width: 0 !important;
        overflow: hidden !important;
    }
    .login-left img,
    img[src*="login-banner"] {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        width: 0 !important;
    }
    .col-md-7.col-lg-6.login-left {
        display: none !important;
    }
</style>
<!-- Page Content -->
<div class="content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-md-8 offset-md-2">
                
                <!-- Register Tab Content -->
                <div class="account-content">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-12 col-lg-6 login-right">
                            <div class="login-header">
                                <h3>Psychologist Register <a href="{{url('psychologist/login')}}">Already have an account?</a></h3>
                            </div>
                            <form method="POST" action="{{ route('psychologist.register.post') }}" enctype="multipart/form-data" id="registrationForm">
                                @csrf
                                
                                <!-- Personal Information Section -->
                                <div class="mb-4">
                                    <h5 class="mb-3"><i class="fa-solid fa-user me-2"></i>Personal Information</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="+1234567890">
                                            @error('phone')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                            @error('date_of_birth')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Gender</label>
                                            <select class="form-control" name="gender">
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('gender')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" class="form-control" name="address" value="{{ old('address') }}" placeholder="Street address, City, State">
                                            @error('address')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Professional Information Section -->
                                <div class="mb-4">
                                    <h5 class="mb-3"><i class="fa-solid fa-briefcase me-2"></i>Professional Information</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Area of Expertise / Specialization <span class="text-danger">*</span></label>
                                        <select class="form-control" name="specialization" required>
                                            <option value="">Select Specialization</option>
                                            <option value="Clinical Psychology" {{ old('specialization') == 'Clinical Psychology' ? 'selected' : '' }}>Clinical Psychology</option>
                                            <option value="Counseling Psychology" {{ old('specialization') == 'Counseling Psychology' ? 'selected' : '' }}>Counseling Psychology</option>
                                            <option value="Child Psychology" {{ old('specialization') == 'Child Psychology' ? 'selected' : '' }}>Child Psychology</option>
                                            <option value="Family Therapy" {{ old('specialization') == 'Family Therapy' ? 'selected' : '' }}>Family Therapy</option>
                                            <option value="Cognitive Behavioral Therapy" {{ old('specialization') == 'Cognitive Behavioral Therapy' ? 'selected' : '' }}>Cognitive Behavioral Therapy</option>
                                            <option value="Trauma Therapy" {{ old('specialization') == 'Trauma Therapy' ? 'selected' : '' }}>Trauma Therapy</option>
                                            <option value="Addiction Counseling" {{ old('specialization') == 'Addiction Counseling' ? 'selected' : '' }}>Addiction Counseling</option>
                                            <option value="Marriage Counseling" {{ old('specialization') == 'Marriage Counseling' ? 'selected' : '' }}>Marriage Counseling</option>
                                            <option value="Anxiety & Depression" {{ old('specialization') == 'Anxiety & Depression' ? 'selected' : '' }}>Anxiety & Depression</option>
                                            <option value="Stress Management" {{ old('specialization') == 'Stress Management' ? 'selected' : '' }}>Stress Management</option>
                                            <option value="Other" {{ old('specialization') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        <small class="form-text text-muted">If "Other", please specify in the bio section below</small>
                                        @error('specialization')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="experience_years" value="{{ old('experience_years') }}" min="0" max="50" required>
                                            @error('experience_years')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Consultation Fee (per session) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" class="form-control" name="consultation_fee" value="{{ old('consultation_fee') }}" min="0" required>
                                            </div>
                                            @error('consultation_fee')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Professional Bio / About Me</label>
                                        <textarea class="form-control" name="bio" rows="5" placeholder="Tell us about your professional background, approach to therapy, and what makes you unique...">{{ old('bio') }}</textarea>
                                        <small class="form-text text-muted">This will be visible to patients. Describe your experience, approach, and specialties.</small>
                                        @error('bio')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Certifications & Qualifications Section -->
                                <div class="mb-4">
                                    <h5 class="mb-3"><i class="fa-solid fa-certificate me-2"></i>Certifications & Qualifications</h5>
                                    <p class="text-muted small mb-3">Upload your professional certifications, licenses, and qualifications. These will be reviewed during verification.</p>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Upload Certifications <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="qualification_files[]" id="qualification_files" multiple accept=".pdf,.jpg,.jpeg,.png" required>
                                        <small class="form-text text-muted">You can upload multiple files. Accepted formats: PDF, JPG, PNG (Max 2MB per file)</small>
                                        @error('qualification_files.*')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                        @error('qualification_files')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div id="filePreview" class="mt-3"></div>
                                </div>

                                <hr class="my-4">
                                <!-- Account Security Section -->
                                <div class="mb-4">
                                    <h5 class="mb-3"><i class="fa-solid fa-lock me-2"></i>Account Security</h5>
                                    
                                    <div class="mb-3">
                                        <div class="form-group-flex">
                                            <label class="form-label">Password <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="pass-group">
                                            <input type="password" class="form-control pass-input" name="password" id="password" required minlength="8">
                                            <span class="feather-eye-off toggle-password"></span>
                                        </div>
                                        <small class="form-text text-muted">Minimum 8 characters</small>
                                        @error('password')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group-flex">
                                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="pass-group">
                                            <input type="password" class="form-control pass-input" name="password_confirmation" id="password_confirmation" required minlength="8">
                                            <span class="feather-eye-off toggle-password"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" target="_blank">Terms and Conditions</a> and <a href="#" target="_blank">Privacy Policy</a> <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button class="btn btn-primary-gradient w-100 btn-lg" type="submit">
                                        <i class="fa-solid fa-user-plus me-2"></i>Create Account & Submit for Verification
                                    </button>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fa-solid fa-info-circle me-2"></i>
                                    <strong>Note:</strong> Your account will be reviewed by our admin team. You'll be notified once your verification is complete.
                                </div>
                                <div class="login-or">
                                    <span class="or-line"></span>
                                    <span class="span-or">or</span>
                                </div>
                                <div class="social-login-btn">
                                    <a href="javascript:void(0);" class="btn w-100">
                                        <img src="{{ asset('assets/img/icons/google-icon.svg') }}" alt="google-icon">Sign up With Google
                                    </a>
                                    <a href="javascript:void(0);" class="btn w-100">
                                        <img src="{{ asset('assets/img/icons/facebook-icon.svg') }}" alt="fb-icon">Sign up With Facebook
                                    </a>
                                </div>
                                <div class="account-signup">
                                    <p>Already have an account? <a href="{{url('psychologist/login')}}">Sign In</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /Register Tab Content -->
                    
            </div>
        </div>

    </div>

</div>		
<!-- /Page Content -->

@endsection

@push('scripts')
<script>
    // Remove login banner image immediately and on load
    (function() {
        function removeLoginBanner() {
            // Remove the image directly
            var images = document.querySelectorAll('img[src*="login-banner"]');
            images.forEach(function(img) {
                img.style.display = 'none';
                img.remove();
            });
            
            // Remove the login-left div
            var loginLeft = document.querySelectorAll('.login-left');
            loginLeft.forEach(function(div) {
                div.style.display = 'none';
                div.remove();
            });
            
            // Also check for col-md-7 col-lg-6 login-left
            var loginLeftCol = document.querySelectorAll('.col-md-7.col-lg-6.login-left');
            loginLeftCol.forEach(function(div) {
                div.style.display = 'none';
                div.remove();
            });
        }
        
        // Run immediately
        removeLoginBanner();
        
        // Run on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', removeLoginBanner);
        } else {
            removeLoginBanner();
        }
        
        // Run after a short delay to catch any dynamically added content
        setTimeout(removeLoginBanner, 100);
        setTimeout(removeLoginBanner, 500);
    })();

    // File preview functionality
    document.getElementById('qualification_files').addEventListener('change', function(e) {
        const preview = document.getElementById('filePreview');
        preview.innerHTML = '';
        
        if (this.files.length > 0) {
            const fileList = document.createElement('div');
            fileList.className = 'list-group';
            
            Array.from(this.files).forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                
                const fileInfo = document.createElement('div');
                const fileIcon = file.type === 'application/pdf' ? 'fa-file-pdf' : 'fa-file-image';
                fileInfo.innerHTML = `
                    <i class="fa-solid ${fileIcon} me-2 text-primary"></i>
                    <strong>${file.name}</strong>
                    <small class="text-muted ms-2">(${(file.size / 1024).toFixed(2)} KB)</small>
                `;
                
                fileItem.appendChild(fileInfo);
                fileList.appendChild(fileItem);
            });
            
            preview.appendChild(fileList);
        }
    });

    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;
        
        if (password !== confirmation && confirmation.length > 0) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });

    // Form validation
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;
        
        if (password !== confirmation) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }
        
        const files = document.getElementById('qualification_files').files;
        if (files.length === 0) {
            e.preventDefault();
            alert('Please upload at least one certification file.');
            return false;
        }
        
        // Check file sizes
        for (let file of files) {
            if (file.size > 2048 * 1024) { // 2MB
                e.preventDefault();
                alert(`File "${file.name}" is too large. Maximum size is 2MB.`);
                return false;
            }
        }
    });
</script>
@endpush

