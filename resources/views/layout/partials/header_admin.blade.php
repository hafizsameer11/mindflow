 <!-- Main Wrapper -->
 @if (!Route::is(['error-404', 'error-500', 'forgot-password', 'lock-screen', 'login', 'register']))
     <div class="main-wrapper">
 @endif
 <!-- Header -->
 <div class="header">
			
    <!-- Logo -->
    <div class="header-left">
        <a href="{{url('admin/index_admin')}}" class="logo">
            <img src="{{ asset('assets/img/logo.png') }}" class="img-fluid" alt="Logo" style="max-height: 47px;">
        </a>
        <a href="{{url('admin/index_admin')}}" class="logo logo-small">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" width="30" height="30">
        </a>
    </div>
    <!-- /Logo -->
    
    <a href="javascript:void(0);" id="toggle_btn">
        <i class="fe fe-text-align-left"></i>
    </a>
    
    <div class="top-nav-search">
        <form>
            <input type="text" class="form-control" placeholder="Search here">
            <button class="btn" type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
    
    <!-- Mobile Menu Toggle -->
    <a class="mobile_btn" id="mobile_btn">
        <i class="fa fa-bars"></i>
    </a>
    <!-- /Mobile Menu Toggle -->
    
    <!-- Header Right Menu -->
    <ul class="nav user-menu">
        
        <!-- User Menu -->
        @php
            $adminUser = Auth::user();
            $adminProfileImage = $adminUser && $adminUser->profile_image 
                ? asset('storage/' . $adminUser->profile_image) 
                : URL::asset('/assets_admin/img/profiles/avatar-01.jpg');
            $adminName = $adminUser ? $adminUser->name : 'Admin User';
        @endphp
        <li class="nav-item dropdown has-arrow">
            <a href="javascript:;" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <span class="user-img"><img class="rounded-circle" src="{{ $adminProfileImage }}" width="31" alt="{{ $adminName }}"></span>
            </a>
            <div class="dropdown-menu">
                <div class="user-header">
                    <div class="avatar avatar-sm">
                        <img src="{{ $adminProfileImage }}" alt="User Image" class="avatar-img rounded-circle">
                    </div>
                    <div class="user-text">
                        <h6>{{ $adminName }}</h6>
                        <p class="text-muted mb-0">Administrator</p>
                    </div>
                </div>
                <a class="dropdown-item" href="{{url('admin/profile')}}">My Profile</a>
                <a class="dropdown-item" href="{{route('admin.signout')}}">Logout</a>
            </div>
        </li>
        <!-- /User Menu -->
        
    </ul>
    <!-- /Header Right Menu -->
    
</div>
<!-- /Header -->
