<?php $page = 'patient-register-step1'; ?>
@extends('layout.mainlayout')
@section('content')
  
<!-- Page Content -->
<div class="content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-md-8 offset-md-2">
                
                <!-- Register Tab Content -->
                <div class="account-content">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-7 col-lg-6 login-left">
                            <img src="{{URL::asset('assets/img/login-banner.png')}}" class="img-fluid" alt="Doccure Register">	
                        </div>
                        <div class="col-md-12 col-lg-6 login-right">
                            <div class="login-header">
                                <h3>Patient Register <a href="{{url('psychologist/register')}}">Are you a Psychologist?</a></h3>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('patient.register.post') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="text-danger pt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="text-danger pt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input class="form-control form-control-lg group_formcontrol form-control-phone" id="phone" name="phone" type="text" value="{{ old('phone', request('phone')) }}">
                                    @error('phone')
                                        <div class="text-danger pt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                    @error('date_of_birth')
                                        <div class="text-danger pt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Gender</label>
                                    <select class="form-control" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="text-danger pt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-group-flex">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="pass-group">
                                        <input type="password" class="form-control pass-input" name="password" required>
                                        <span class="feather-eye-off toggle-password"></span>
                                    </div>
                                    @error('password')
                                        <div class="text-danger pt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-group-flex">
                                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="pass-group">
                                        <input type="password" class="form-control pass-input" name="password_confirmation" required>
                                        <span class="feather-eye-off toggle-password"></span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary-gradient w-100" type="submit">Sign Up</button>
                                </div>
                                <div class="login-or">
                                    <span class="or-line"></span>
                                    <span class="span-or">or</span>
                                </div>
                                <div class="social-login-btn">
                                    <a href="javascript:void(0);" class="btn w-100">
                                        <img src="{{URL::asset('assets/img/icons/google-icon.svg')}}" alt="google-icon">Sign up With Google
                                    </a>
                                    <a href="javascript:void(0);" class="btn w-100">
                                        <img src="{{URL::asset('assets/img/icons/facebook-icon.svg')}}" alt="fb-icon">Sign up With Facebook
                                    </a>
                                </div>
                                <div class="account-signup">
                                    <p>Already have an account? <a href="{{url('patient/login')}}">Sign In</a></p>
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

