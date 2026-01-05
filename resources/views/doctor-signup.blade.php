<?php $page = 'doctor-signup'; ?>
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
                                <h3>Psychologist Login <a href="{{url('psychologist/register')}}">Are you a new Psychologist?</a></h3>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('psychologist.login.post') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="text-danger pt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-group-flex">
                                        <label class="form-label">Password</label>
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
                                    <button class="btn btn-primary-gradient w-100" type="submit">Login</button>
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
                                    <p>Don't have an account? <a href="{{url('psychologist/register')}}">Register</a></p>
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

