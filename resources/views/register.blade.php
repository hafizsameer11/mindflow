<?php $page = 'register'; ?>
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
                
                <!-- Login Tab Content -->
                <div class="account-content">
                    <div class="row align-items-center justify-content-center">
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
                                        <img src="{{ asset('assets/img/icons/google-icon.svg') }}" alt="google-icon">Sign in With Google
                                    </a>
                                    <a href="javascript:void(0);" class="btn w-100">
                                        <img src="{{ asset('assets/img/icons/facebook-icon.svg') }}" alt="fb-icon">Sign in With Facebook
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
</script>
@endpush
