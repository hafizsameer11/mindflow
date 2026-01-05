<?php $page = 'login'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Main Wrapper -->
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <div class="loginbox">
                    <div class="login-left">
                        <img class="img-fluid" src="{{ URL::asset('/assets_admin/img/logo-white.png') }}" alt="Logo">
                    </div>
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Login</h1>
                            <p class="account-subtitle">Access to our dashboard</p>

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
                            <!-- Form -->
                            <form method="post" action="{{ route('admin.login.post') }}">
                                @csrf
                                <div class="mb-3">
                                    <input class="form-control" type="text" placeholder="Email" value="{{ old('email') }}"
                                        name="email" id="email" required>
                                    <div class="text-danger pt-2">
                                        @error('email')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="pass-group">
                                        <input class="form-control pass-input" type="password" placeholder="Password"
                                            name="password" id="password" required>
                                        <span class="feather-eye-off toggle-password"></span>
                                        <div class="text-danger pt-2">
                                            @error('password')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary w-100" type="submit">Login</button>
                                </div>
                            </form>
                            <!-- /Form -->


                            <div class="text-center forgotpass"><a href="{{ url('admin/forgot-password') }}">Forgot
                                    Password?</a></div>
                            <div class="login-or">
                                <span class="or-line"></span>
                                <span class="span-or">or</span>
                            </div>

                            <!-- Social Login -->
								<div class="social-login">
									<span>Login with</span>
									<a href="javascript:;" class="facebook"><i class="fa-brands fa-facebook-f"></i></a><a href="javascript:;" class="google"><i class="fa-brands fa-google"></i></a>
								</div>
							<!-- /Social Login -->

                            <div class="text-center dont-have">Don’t have an account? <a
                                    href="{{ url('admin/register') }}">Register</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->
@endsection
