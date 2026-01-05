<?php $page = 'profile'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Profile</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="profile-header">
                        <div class="row align-items-center">
                            <div class="col-auto profile-image">
                                <a href="javascript:;">
                                    @if($user->profile_image)
                                        <img class="rounded-circle" alt="User Image" src="{{ asset('storage/' . $user->profile_image) }}">
                                    @else
                                        <img class="rounded-circle" alt="User Image" src="{{ URL::asset('/assets_admin/img/profiles/avatar-01.jpg') }}">
                                    @endif
                                </a>
                            </div>
                            <div class="col ml-md-n2 profile-user-info">
                                <h4 class="user-name mb-0">{{ $user->name }}</h4>
                                <h6 class="text-muted">{{ $user->email }}</h6>
                                <div class="user-Location"><i class="fa-solid fa-location-dot"></i> {{ $user->address ?? 'Not specified' }}
                                </div>
                                <div class="about-text">Administrator account for managing the platform.</div>
                            </div>
                            <div class="col-auto profile-btn">
                                <a href="#edit_personal_details" class="btn btn-primary" data-bs-toggle="modal">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="profile-menu">
                        <ul class="nav nav-tabs nav-tabs-solid">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#per_details_tab">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#password_tab">Password</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content profile-tab-cont">

                        <!-- Personal Details Tab -->
                        <div class="tab-pane fade show active" id="per_details_tab">

                            <!-- Personal Details -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title d-flex justify-content-between">
                                                <span>Personal Details</span>
                                                <a class="edit-link" data-bs-toggle="modal" href="#edit_personal_details"><i
                                                        class="fa fa-edit me-1"></i>Edit</a>
                                            </h5>
                                            <div class="row">
                                                <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Name</p>
                                                <p class="col-sm-10">{{ $user->name }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Date of Birth</p>
                                                <p class="col-sm-10">{{ $user->date_of_birth ? $user->date_of_birth->format('d M Y') : 'Not specified' }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Email ID</p>
                                                <p class="col-sm-10">{{ $user->email }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Mobile</p>
                                                <p class="col-sm-10">{{ $user->phone ?? 'Not specified' }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-2 text-muted text-sm-right mb-0 mb-sm-3">Gender</p>
                                                <p class="col-sm-10">{{ ucfirst($user->gender ?? 'Not specified') }}</p>
                                            </div>
                                            <div class="row">
                                                <p class="col-sm-2 text-muted text-sm-right mb-0">Address</p>
                                                <p class="col-sm-10 mb-0">{{ $user->address ?? 'Not specified' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit Details Modal -->
                                    <div class="modal fade" id="edit_personal_details" aria-hidden="true" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Personal Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                    </button>
                                                </div>
                                                <div class="modal-body">
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
                                                    <form action="{{ route('admin.profile.update') }}" method="POST" id="profileForm" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="mb-2">Profile Image</label>
                                                                    <div class="d-flex align-items-center mb-2">
                                                                        @if($user->profile_image)
                                                                            <img id="profileImagePreview" src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                                                        @else
                                                                            <img id="profileImagePreview" src="{{ URL::asset('/assets_admin/img/profiles/avatar-01.jpg') }}" alt="Profile Image" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                                                        @endif
                                                                        <div class="flex-grow-1">
                                                                            <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*" onchange="previewProfileImage(this)">
                                                                            <small class="form-text text-muted">Max size: 2MB. Allowed: jpeg, jpg, png, gif</small>
                                                                            @if($user->profile_image)
                                                                                <div class="mt-2">
                                                                                    <label class="form-check-label">
                                                                                        <input type="checkbox" name="remove_profile_image" value="1" class="form-check-input">
                                                                                        Remove current image
                                                                                    </label>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    @error('profile_image')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="mb-2">Full Name <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="mb-2">Date of Birth</label>
                                                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-6">
                                                                <div class="mb-3">
                                                                    <label class="mb-2">Email ID <span class="text-danger">*</span></label>
                                                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-6">
                                                                <div class="mb-3">
                                                                    <label class="mb-2">Mobile</label>
                                                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-6">
                                                                <div class="mb-3">
                                                                    <label class="mb-2">Gender</label>
                                                                    <select name="gender" class="form-control">
                                                                        <option value="">Select Gender</option>
                                                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="mb-2">Address</label>
                                                                    <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Edit Details Modal -->
                                    
                                    <script>
                                        function previewProfileImage(input) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();
                                                reader.onload = function(e) {
                                                    document.getElementById('profileImagePreview').src = e.target.result;
                                                };
                                                reader.readAsDataURL(input.files[0]);
                                            }
                                        }
                                    </script>

                                </div>


                            </div>
                            <!-- /Personal Details -->

                        </div>
                        <!-- /Personal Details Tab -->

                        <!-- Change Password Tab -->
                        <div id="password_tab" class="tab-pane fade">

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Change Password</h5>
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
                                    <div class="row">
                                        <div class="col-md-10 col-lg-6">
                                            <form action="{{ route('admin.profile.update-password') }}" method="POST" id="passwordForm">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="mb-2">Current Password <span class="text-danger">*</span></label>
                                                    <input type="password" name="current_password" class="form-control" required>
                                                    @error('current_password')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="mb-2">New Password <span class="text-danger">*</span></label>
                                                    <input type="password" name="password" class="form-control" minlength="8" required>
                                                    <small class="form-text text-muted">Minimum 8 characters</small>
                                                    @error('password')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="mb-2">Confirm Password <span class="text-danger">*</span></label>
                                                    <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
                                                </div>
                                                <button class="btn btn-primary" type="submit">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Change Password Tab -->

                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->
@endsection
