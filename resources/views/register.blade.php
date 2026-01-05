<?php $page = 'register'; ?>
@extends('layout.mainlayout')
@section('content')
  
<!-- Page Content -->
<div class="content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-md-8 offset-md-2">
                
                <!-- Login Tab Content -->
                <div class="account-content">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-7 col-lg-6 login-left">
                            <img src="{{URL::asset('assets/img/login-banner.png')}}" class="img-fluid" alt="Doccure Login">	
                        </div>
                        <div class="col-md-12 col-lg-6 login-right">
                            <div class="login-header">
                                <h3>Patient Register <a href="{{url('doctor-register')}}">Are you a Doctor?</a></h3>
                            </div>
                            <form method="GET" action="{{url('patient-register-step1')}}">
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input class="form-control form-control-lg group_formcontrol form-control-phone" id="phone" name="phone" type="text" value="{{ old('phone') }}">
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary-gradient w-100" type="submit">Continue Registration</button>
                                </div>
                                <div class="login-or">
                                    <span class="or-line"></span>
                                    <span class="span-or">or</span>
                                </div>
                                <div class="social-login-btn">
                                    <a href="javascript:void(0);" class="btn w-100">
                                        <img src="{{URL::asset('assets/img/icons/google-icon.svg')}}" alt="google-icon">Sign in With Google
                                    </a>
                                    <a href="javascript:void(0);" class="btn w-100">
                                        <img src="{{URL::asset('assets/img/icons/facebook-icon.svg')}}" alt="fb-icon">Sign in With Facebook
                                    </a>
                                </div>
                                <div class="account-signup">
                                    <p>Already have account? <a href="{{url('login')}}">Sign In</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /Login Tab Content -->
                    
            </div>
        </div>

    </div>

</div>		
<!-- /Page Content -->

@endsection
