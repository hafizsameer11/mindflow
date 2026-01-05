<?php $page = 'dependent'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            Dependants
        @endslot
        @slot('li_2')
            Manage Dependants
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
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3>Manage Dependants</h3>
                                <p class="text-muted">Add and manage family members or dependants for easy appointment booking and medical record access.</p>
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_dependent">
                                <i class="fa-solid fa-plus me-2"></i>Add Dependant
                            </button>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Dependents List -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">My Dependants</h5>
                        </div>
                        <div class="card-body">
                            @if(isset($dependents) && $dependents->count() > 0)
                                @foreach($dependents as $dependent)
                                <div class="dependent-wrap">
                                    <div class="dependent-info">
                                        <div class="patinet-information">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $dependent->profile_image ? asset('storage/' . $dependent->profile_image) : asset('assets/img/doctors/doctor-thumb-01.jpg') }}" alt="Dependent Image" class="rounded-circle">
                                                <div class="patient-info">
                                                    <h5>{{ $dependent->name }}</h5>
                                                    <ul>
                                                        <li>{{ $dependent->relationship }}</li>
                                                        <li>{{ \Carbon\Carbon::parse($dependent->date_of_birth)->format('M d, Y') }}</li>
                                                        <li>{{ ucfirst($dependent->gender) }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="blood-info">
                                            <p>Blood Group</p>
                                            <h6>{{ $dependent->blood_group ?? 'N/A' }}</h6>
                                        </div>
                                    </div>
                                    <div class="dependent-status">
                                        <form action="{{ route('dependent.toggle-status', $dependent) }}" method="POST" class="d-inline">
                                            @csrf
                                            <div class="status-toggle">
                                                <input type="checkbox" class="check" id="status{{ $dependent->id }}" {{ $dependent->is_active ? 'checked' : '' }} onchange="this.form.submit()">
                                                <label for="status{{ $dependent->id }}" class="checktoggle"></label>
                                                <span class="{{ $dependent->is_active ? 'active' : 'deactive' }}">{{ $dependent->is_active ? 'Active' : 'Inactive' }}</span>
                                            </div>
                                        </form>
                                        <a href="#" class="edit-icon" data-bs-toggle="modal" data-bs-target="#edit_dependent{{ $dependent->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('dependent.destroy', $dependent) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this dependant?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="edit-icon text-danger" style="border-color: #dc3545;">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-user-octagon text-muted" style="font-size: 64px;"></i>
                                    <h5 class="mt-3 mb-2">No Dependants Added</h5>
                                    <p class="text-muted mb-4">Add family members or dependants to manage their appointments and medical records.</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_dependent">
                                        <i class="fa-solid fa-plus me-2"></i>Add Your First Dependant
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Information Card -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <i class="fa-solid fa-info-circle text-info me-3" style="font-size: 24px; margin-top: 4px;"></i>
                                <div>
                                    <h6 class="mb-2">About Dependants</h6>
                                    <p class="text-muted mb-0">
                                        Dependants are family members or individuals you can manage on your account. Once added, you can book appointments, view medical records, and manage their healthcare information. This feature allows you to easily manage healthcare for your entire family from one account.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->

    <!-- Add Dependent Modal -->
    <div class="modal fade custom-modals" id="add_dependent">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Dependant</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form action="{{ route('dependent.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($errors->any())
                        <div class="alert alert-danger mx-3 mt-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="add-dependent">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="form-wrap pb-0">
                                        <label class="form-label">Profile Photo</label>
                                        <div class="change-avatar img-upload">
                                            <div class="profile-img">
                                                <i class="fa-solid fa-file-image"></i>
                                            </div>
                                            <div class="upload-img">
                                                <h5>Profile Image</h5>
                                                <div class="imgs-load d-flex align-items-center">
                                                    <div class="change-photo">
                                                        Upload New 
                                                        <input type="file" name="profile_image" class="upload" accept="image/jpeg,image/jpg,image/png,image/gif">
                                                    </div>
                                                    <a href="#" class="upload-remove">Remove</a>
                                                </div>
                                                <p>Your Image should Below 4 MB, Accepted format jpg,png,svg</p>
                                            </div>			
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-wrap">
                                        <label class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-wrap">
                                        <label class="form-label">Relationship <span class="text-danger">*</span></label>
                                        <select name="relationship" class="form-control" required>
                                            <option value="">Select Relationship</option>
                                            <option value="Spouse">Spouse</option>
                                            <option value="Child">Child</option>
                                            <option value="Parent">Parent</option>
                                            <option value="Sibling">Sibling</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-wrap">
                                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                        <div class="form-icon">
                                            <input type="date" name="date_of_birth" class="form-control" required>
                                            <span class="icon"><i class="isax isax-calendar-1"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-wrap">
                                        <label class="form-label">Select Gender <span class="text-danger">*</span></label>
                                        <div class="radio-selection d-flex border">
                                            <div class="flex-grow-1">
                                                <input type="radio" class="btn-check" name="gender" id="gender_male" value="male" autocomplete="off" required>
                                                <label class="btn btn-white" for="gender_male">Male</label>	
                                            </div>
                                            <div class="flex-grow-1">
                                                <input type="radio" class="btn-check" name="gender" id="gender_female" value="female" autocomplete="off">
                                                <label class="btn btn-white" for="gender_female">Female</label>												
                                            </div>
                                            <div class="flex-grow-1">
                                                <input type="radio" class="btn-check" name="gender" id="gender_other" value="other" autocomplete="off">
                                                <label class="btn btn-white" for="gender_other">Others</label>												
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-wrap">
                                        <label class="form-label">Blood Group</label>
                                        <select name="blood_group" class="form-control">
                                            <option value="">Select Blood Group</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-wrap">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">					
                        <div class="modal-btn text-end">
                            <a href="#" class="btn btn-md btn-dark rounded-pill" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary-gradient rounded-pill">Add Dependant</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Add Dependent Modal -->

    <!-- Edit Dependent Modals -->
    @if(isset($dependents) && $dependents->count() > 0)
        @foreach($dependents as $dependent)
        <div class="modal fade custom-modals" id="edit_dependent{{ $dependent->id }}">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Edit Dependant</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <form action="{{ route('dependent.update', $dependent) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="add-dependent">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <div class="form-wrap pb-0">
                                            <label class="form-label">Profile Photo</label>
                                            <div class="change-avatar img-upload">
                                                <div class="profile-img">
                                                    @if($dependent->profile_image)
                                                        <img src="{{ asset('storage/' . $dependent->profile_image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                                    @else
                                                        <i class="fa-solid fa-file-image"></i>
                                                    @endif
                                                </div>
                                                <div class="upload-img">
                                                    <h5>Profile Image</h5>
                                                    <div class="imgs-load d-flex align-items-center">
                                                        <div class="change-photo">
                                                            Upload New 
                                                            <input type="file" name="profile_image" class="upload" accept="image/jpeg,image/jpg,image/png,image/gif">
                                                        </div>
                                                        <a href="#" class="upload-remove">Remove</a>
                                                    </div>
                                                    <p>Your Image should Below 4 MB, Accepted format jpg,png,svg</p>
                                                </div>			
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-wrap">
                                            <label class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{ $dependent->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-wrap">
                                            <label class="form-label">Relationship <span class="text-danger">*</span></label>
                                            <select name="relationship" class="form-control" required>
                                                <option value="">Select Relationship</option>
                                                <option value="Spouse" {{ $dependent->relationship == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                                <option value="Child" {{ $dependent->relationship == 'Child' ? 'selected' : '' }}>Child</option>
                                                <option value="Parent" {{ $dependent->relationship == 'Parent' ? 'selected' : '' }}>Parent</option>
                                                <option value="Sibling" {{ $dependent->relationship == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                                <option value="Other" {{ $dependent->relationship == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-wrap">
                                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                            <div class="form-icon">
                                                <input type="date" name="date_of_birth" class="form-control" value="{{ $dependent->date_of_birth->format('Y-m-d') }}" required>
                                                <span class="icon"><i class="isax isax-calendar-1"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-wrap">
                                            <label class="form-label">Select Gender <span class="text-danger">*</span></label>
                                            <div class="radio-selection d-flex border">
                                                <div class="flex-grow-1">
                                                    <input type="radio" class="btn-check" name="gender" id="edit_gender_male{{ $dependent->id }}" value="male" {{ $dependent->gender == 'male' ? 'checked' : '' }} required>
                                                    <label class="btn btn-white" for="edit_gender_male{{ $dependent->id }}">Male</label>	
                                                </div>
                                                <div class="flex-grow-1">
                                                    <input type="radio" class="btn-check" name="gender" id="edit_gender_female{{ $dependent->id }}" value="female" {{ $dependent->gender == 'female' ? 'checked' : '' }}>
                                                    <label class="btn btn-white" for="edit_gender_female{{ $dependent->id }}">Female</label>												
                                                </div>
                                                <div class="flex-grow-1">
                                                    <input type="radio" class="btn-check" name="gender" id="edit_gender_other{{ $dependent->id }}" value="other" {{ $dependent->gender == 'other' ? 'checked' : '' }}>
                                                    <label class="btn btn-white" for="edit_gender_other{{ $dependent->id }}">Others</label>												
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-wrap">
                                            <label class="form-label">Blood Group</label>
                                            <select name="blood_group" class="form-control">
                                                <option value="">Select Blood Group</option>
                                                <option value="A+" {{ $dependent->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-" {{ $dependent->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ $dependent->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-" {{ $dependent->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="AB+" {{ $dependent->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-" {{ $dependent->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                <option value="O+" {{ $dependent->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-" {{ $dependent->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-wrap">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" name="phone" class="form-control" value="{{ $dependent->phone }}" placeholder="Enter phone number">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">					
                            <div class="modal-btn text-end">
                                <a href="#" class="btn btn-md btn-dark rounded-pill" data-bs-dismiss="modal">Cancel</a>
                                <button type="submit" class="btn btn-md btn-primary-gradient rounded-pill">Update Dependant</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endif
    <!-- /Edit Dependent Modals -->
@endsection

