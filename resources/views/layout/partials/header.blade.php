@if (Route::is(['pharmacy-index']))
    <!-- Top Header -->
    <div class="top-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="special-offer-content">
                        <p>Special offer! Get -20% off for first order with minimum <span>$200.00</span> in cart.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="top-header-right">
                        <ul class="nav">
                            <li class="header-theme me-0 pe-0">
                                <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                                    <i class="isax isax-sun-1"></i>
                                </a>
                                <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                                    <i class="isax isax-moon"></i>
                                </a>
                            </li>
                            <li>
                                <div class="dropdown lang-dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle nav-link"
                                        data-bs-toggle="dropdown">
                                        English
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);">French</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Spanish</a>
                                        <a class="dropdown-item" href="javascript:void(0);">German</a>
                                    </div>
                                </div>
                                <div class="dropdown lang-dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle nav-link"
                                        data-bs-toggle="dropdown">
                                        USD
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);">Euro</a>
                                        <a class="dropdown-item" href="javascript:void(0);">INR</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Dinar</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="btn log-register">
                                    <a href="#" class="me-1">
                                        <span><i class="feather-user"></i></span> Sign In
                                    </a> /
                                    <a href="{{ url('register') }}" class="ms-1">Sign Up</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Top Header -->

    <!-- Cart Section -->
    <div class="cart-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <div class="cart-logo">
                        <a href="{{ url('index') }}">
                            <img src="{{ URL::asset('assets/img/logo.svg') }}" class="img-fluid" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="cart-search">
                        <form action="{{ url('pharmacy-search') }}">
                            <div class="enter-pincode">
                                <i class="feather-map-pin"></i>
                                <div class="enter-pincode-input">
                                    <input type="text" class="form-control" placeholder="Enter Pincode">
                                </div>
                            </div>
                            <div class="cart-search-input">
                                <input type="text" class="form-control"
                                    placeholder="Search for medicines, health products and more">
                            </div>
                            <div class="cart-search-btn">
                                <button type="submit" class="btn">
                                    <i class="feather-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="shopping-cart-list">
                        <ul class="nav">
                            <li>
                                <a href="javascript:void(0);">
                                    <img src="{{ URL::asset('assets/img/icons/cart-favourite.svg') }}" alt="Img">
                                </a>
                            </li>
                            <li>
                                <div class="shopping-cart-amount">
                                    <div class="shopping-cart-icon">
                                        <img src="{{ URL::asset('assets/img/icons/bag-2.svg') }}" alt="Img">
                                        <span>2</span>
                                    </div>
                                    <div class="shopping-cart-content">
                                        <p>Shopping cart</p>
                                        <h6>$57.00</h6>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Cart Section -->
@endif

<!-- Header -->
@if (
    !Route::is([
        'index-2',
        'index-3',
        'index-5',
        'index-4',
        'index-6',
        'index-7',
        'index-8',
        'index-9',
        'index-10',
        'index-11',
        'index-12',
        'pharmacy-index',
        'index-13',
        'index-14',
    ]))
    <header class="header header-custom header-fixed inner-header relative">
@endif


@if (Route::is(['index-2']))
    <header class="header header-trans header-two">
@endif

@if (Route::is(['index-3']))
    <header class="header header-trans header-three header-eight">
@endif
@if (Route::is(['index-5']))
    <header class="header header-custom header-fixed header-ten">
@endif
@if (Route::is(['index-4']))
    <header class="header header-custom header-fixed header-one home-head-one">
@endif
@if (Route::is(['index-6']))
    <header class="header header-trans header-eleven">
@endif
@if (Route::is(['index-7']))
    <header class="header header-fixed header-fourteen header-twelve veterinary-header">
@endif
@if (Route::is(['index-8']))
    <header class="header header-fixed header-fourteen header-twelve header-thirteen">
@endif
@if (Route::is(['index-9']))
    <header class="header header-fixed header-fourteen">
@endif
@if (Route::is(['index-10']))
    <header class="header header-fixed header-fourteen header-fifteen ent-header">
@endif
@if (Route::is(['index-11']))
    <header class="header header-fixed header-fourteen header-sixteen">
@endif
@if (Route::is(['index-12']))
    <header class="header header-fixed header-fourteen header-twelve header-thirteen">
@endif
@if (Route::is(['pharmacy-index']))
    <header class="header">
@endif
@if (Route::is(['index-13']))
    <header class="header header-custom header-fixed header-ten home-care-header">
@endif
@if (Route::is(['index-14']))
    <header class="header header-custom header-fixed header-ten home-care-header dentist-header">
@endif
@if (Route::is(['index-13']))
    <div class="header-top-wrap">
        <div class="container">
            <div class="header-top-bar">
                <ul class="header-contact">
                    <li><i class="fa-solid fa-envelope"></i>doccure@example.com</li>
                    <li><i class="fa-solid fa-location-dot"></i>231 madison Street, NewYork, USA</li>
                </ul>
                <ul class="social-icon">
                    <li>
                        <select class="select">
                            <option>English</option>
                            <option>Japanese</option>
                        </select>
                    </li>
                    <li>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endif
@if (Route::is(['index-14']))
    <div class="header-top-wrap">
        <div class="container">
            <div class="header-top-bar">
                <ul class="header-contact">
                    <li><span class="question-mark-icon"><i class="fa-solid fa-question"></i></span>Have any
                        Questions?</li>
                    <li><i class="fa-solid fa-envelope"></i>info@example.com</li>
                    <li><i class="fa-solid fa-phone"></i>+1 123 456 8891</li>
                </ul>
                <ul class="social-icon">
                    <li>
                        <select class="select">
                            <option>English</option>
                            <option>Japanese</option>
                        </select>
                    </li>
                    <li>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endif
<div class="container">
    @if (Route::is(['index-7']))
        <div class="veterinary-top-head">
            <ul>
                <li><i class="fa-solid fa-envelope me-2"></i>Doccure@example.com</li>
                <li><i class="fa-solid fa-location-dot me-2"></i>231 madison Street, NewYork,USA</li>
            </ul>
            <ul>
                <li>Mon-Fri : 10:00 AM - 09:00PM</li>
                <li>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                </li>
            </ul>
        </div>
    @endif
    <nav class="navbar navbar-expand-lg header-nav">
        <div class="navbar-header">
            <a id="mobile_btn" href="javascript:void(0);">
                <span class="bar-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </a>
            <a href="{{ url('index') }}" class="navbar-brand logo">

                @if (!Route::is(['index-2', 'index-6', 'index-7', 'index-11']))
                    <img src="{{ URL::asset('assets/img/logo.png') }}" class="img-fluid" alt="Logo">
                @endif
                @if (Route::is(['index-2']))
                    <img src="{{ URL::asset('assets/img/logo.png') }}" class="img-fluid" alt="Logo">
                @endif
                @if (Route::is(['index-6']))
                    <img src="{{ URL::asset('assets/img/footer-logo.png') }}" class="img-fluid" alt="Logo">
                @endif
                @if (Route::is(['index-7']))
                    <img src="{{ URL::asset('assets/img/veterinary-home-logo.svg') }}" class="img-fluid"
                        alt="Logo">
                @endif
                @if (Route::is(['index-11']))
                    <img src="{{ URL::asset('assets/img/logo-15.png') }}" class="img-fluid" alt="Logo">
                @endif
            </a>
        </div>
        @if (Route::is(['pharmacy-index']))
            <div class="browse-categorie">
                <div class="dropdown categorie-dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="{{ URL::asset('assets/img/icons/browse-categorie.svg') }}" alt="Img"> Browse
                        Categories
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);">Ayush</a>
                        <a class="dropdown-item" href="javascript:void(0);">Covid Essentials</a>
                        <a class="dropdown-item" href="javascript:void(0);">Devices</a>
                        <a class="dropdown-item" href="javascript:void(0);">Glucometers</a>
                    </div>
                </div>
            </div>
        @endif
        @if (
            !Route::is([
                'index-2',
                'index-3',
                'index-5',
                'index-4',
                'index-6',
                'index-7',
                'index-8',
                'index-9',
                'index-10',
                'index-11',
                'index-12',
                'pharmacy-index',
                'index-13',
                'index-14',
            ]))
            <div class="header-menu">
        @endif



        @if (
            !Route::is([
                'index-2',
                'index-3',
                'index-5',
                'index-6',
                'index-7',
                'index-8',
                'index-9',
                'index-10',
                'index-12',
                'index-13',
                'index-14',
                'index-11',
                'doctor-dashboard',
                'appointments',
                'available-timings',
                'my-patients',
                'patient-profile',
                'chat-doctor',
                'invoices',
                'doctor-profile-settings',
                'reviews',
                'doctor-blog',
        
                'doctor-add-blog',
                'patient-dashboard',
                'map-grid',
                'map-list',
                'search',
                'search-2',
                'doctor-profile',
                'doctor-profile-2',
                'checkout',
                'booking-success',
                'favourites',
                'profile-settings',
                'change-password',
                'accounts',
                'chat',
                'doctor-appointment-details',
                'doctor-appointments-grid',
                'doctor-appointment-start',
                'doctor-awards-settings',
                'doctor-business-settings',
                'doctor-cancelled-appointment',
                'doctor-cancelled-appointment-2',
                'doctor-change-password',
                'doctor-clinics-settings',
                'doctor-completed-appointment',
                'doctor-education-settings',
                'doctor-experience-settings',
                'doctor-insurance-settings',
                'doctor-payment',
                'doctor-pending-blog',
                'doctor-profile',
                'doctor-request',
                'doctor-specialities',
                'doctor-upcoming-appointment',
                'edit-blog',
                'invoice-view',
                'medical-details',
                'medical-records',
                'patient-appointment-details',
                'patient-appointments',
                'patient-appointments-grid',
                'patient-cancelled-appointment',
                'patient-completed-appointment',
                'patient-invoices',
                'social-media',
                'video-call',
                'voice-call',
                'two-factor-authentication',
                'terms-condition',
                'patient-accounts',
            ]))
            <ul class="nav header-navbar-rht">


                @if (!Route::is(['index-4']))
                    <li>

                        <a href="{{ url('signup') }}"
                            class="btn btn-md btn-primary-gradient d-inline-flex align-items-center rounded-pill"><i
                                class="isax isax-lock-1 me-1"></i>Sign Up</a>
                    </li>
                    <li>
                        <a href="{{ url('register') }}" class="btn btn-md btn-dark d-inline-flex align-items-center rounded-pill">
                            <i class="isax isax-user-tick me-1"></i>Register
                        </a>
                    </li>
                @endif
            </ul>
        @endif

        @if (Route::is(['index-2']))
            <ul class="nav header-navbar-rht">
                <li class="header-theme">
                    <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                        <i class="isax isax-sun-1"></i>
                    </a>
                    <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                        <i class="isax isax-moon"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link header-login" href="#">login / Signup </a>
                </li>
            </ul>
        @endif

        @if (Route::is(['index-3']))
            <ul class="nav header-navbar-rht">
                <li class="header-theme me-1">
                    <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                        <i class="isax isax-sun-1"></i>
                    </a>
                    <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                        <i class="isax isax-moon"></i>
                    </a>
                </li>
                <li class="contact-item"><i class="isax isax-call"></i><span>Contact :</span>+1 315 369 5943</li>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary header-login d-inline-flex align-items-center"
                        href="#"><i class="isax isax-user-octagon"></i>Login / Sign up </a>
                </li>
            </ul>
        @endif

        @if (Route::is(['index-5']))
            <ul class="nav header-navbar-rht">
                <li class="header-theme">
                    <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                        <i class="isax isax-sun-1"></i>
                    </a>
                    <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                        <i class="isax isax-moon"></i>
                    </a>
                </li>
                <li class="register-btn">
                    <a href="{{ url('login-email') }}" class="btn log-btn"><i class="feather-lock"></i>Login</a>
                </li>
                <li class="register-btn">
                    <a href="#" class="btn reg-btn"><i class="feather-user"></i>Sign Up</a>
                </li>
            </ul>
        @endif
        @if (Route::is(['index-4']))
            <ul class="nav header-navbar-rht">
                <li class="register-btn">
                    <a href="{{ url('register') }}" class="btn btn-dark reg-btn"><i
                            class="isax isax-user"></i>Register</a>
                </li>
                <li class="register-btn">
                    <a href="#" class="btn btn-primary log-btn"><i
                            class="isax isax-lock"></i>Login</a>
                </li>
            </ul>
        @endif
        @if (Route::is(['index-6']))
            <ul class="nav header-navbar-rht align-items-center">
                <li class="header-theme me-3">
                    <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                        <i class="isax isax-sun-1"></i>
                    </a>
                    <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                        <i class="isax isax-moon"></i>
                    </a>
                </li>
                <li class="login-in-fourteen"><a href="{{ url('register') }}"><i
                            class="isax isax-user me-2"></i>Sign Up / </a> <a href="login')}}"> Sign In</a></li>
            </ul>
        @endif

        @if (Route::is(['index-7']))
            <ul class="nav header-navbar-rht">
                <li class="header-theme me-3">
                    <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                        <i class="isax isax-sun-1"></i>
                    </a>
                    <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                        <i class="isax isax-moon"></i>
                    </a>
                </li>
                <li class="login-in-fourteen log-in-vet-head"><a href="{{ url('register') }}"><i
                            class="fa-regular fa-user me-2"></i>Sign Up / </a> <a href="login')}}"> Sign In</a></li>
                <li class="searchbar searchbar-fourteen">
                    <a href="javascript:void(0);"><i class="feather-search"></i></a>
                    <div class="togglesearch">
                        <form action="{{ url('search-2') }}">
                            <div class="input-group">
                                <input type="text" class="form-control">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </div>
                </li>
            </ul>
        @endif
        @if (Route::is(['index-8']))
            <ul class="nav header-navbar-rht">
                <li class="header-theme me-3">
                    <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                        <i class="isax isax-sun-1"></i>
                    </a>
                    <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                        <i class="isax isax-moon"></i>
                    </a>
                </li>
                <li class="flag-nav">
                    <select class="flag-img">
                        <option data-image="{{ URL::asset('assets/img/flags/us.png') }}">English</option>
                        <option data-image="{{ URL::asset('assets/img/flags/jp.png') }}">JPN</option>
                    </select>
                </li>
                <li class="login-in">
                    <a href="{{ url('login-email') }}" class="btn btn-primary sign-btn"><i
                            class="isax isax-lock"></i>Sign In</a>
                </li>
                <li class="login-in">
                    <a href="#" class="btn btn-dark reg-btn">
                        <i class="isax isax-user"></i>Sign Up
                    </a>
                </li>
            </ul>
        @endif
        @if (Route::is(['index-9']))
            <ul class="nav header-navbar-rht">
                <li class="header-theme me-3">
                    <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                        <i class="isax isax-sun-1"></i>
                    </a>
                    <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                        <i class="isax isax-moon"></i>
                    </a>
                </li>
                <li class="login-in-fourteen">
                    <a href="{{ url('login-email') }}" class="btn btn-primary
                    btn-md reg-btn"><i
                            class="isax isax-lock"></i>Log In</a>
                </li>
                <li class="login-in-fourteen">
                    <a href="#" class="btn btn-dark btn-md reg-btn reg-btn-fourteen"><i
                            class="isax isax-user"></i>Sign Up</a>
                </li>
            </ul>
        @endif
        @if (Route::is(['index-10']))
            <ul class="nav header-navbar-rht">
                <li class="searchbar">
                    <a href="javascript:void(0);"><i class="isax isax-search-normal"></i></a>
                    <div class="togglesearch">
                        <form action="{{ url('search') }}">
                            <div class="input-group">
                                <input type="text" class="form-control">
                                <button type="submit" class="btn">Search</button>
                            </div>
                        </form>
                    </div>
                </li>
                <li class="login-in-fourteen">
                    <a href="#" class="btn btn-primary reg-btn"><i
                            class="isax isax-lock me-2"></i>Login</a>
                </li>
                <li class="login-in-fourteen">
                    <a href="{{ url('register') }}" class="btn btn-dark reg-btn reg-btn-fourteen">
                        <i class="isax isax-user me-2"></i>Sign Up
                    </a>
                </li>
            </ul>
        @endif
        @if (Route::is(['index-11']))
            <ul class="nav header-navbar-rht">
                <li class="header-theme me-3">
                    <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                        <i class="isax isax-sun-1"></i>
                    </a>
                    <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                        <i class="isax isax-moon"></i>
                    </a>
                </li>
                <li class="login-in-sixteen">
                    <a href="{{ url('login-email') }}" class="btn reg-btn"><i
                            class="feather-lock me-2"></i>Login<span></span><span></span><span></span><span></span></a>
                </li>
                <li class="login-in-sixteen">
                    <a href="#" class="btn btn-primary reg-btn reg-btn-sixteen"><i
                            class="feather-user me-2"></i>Sign Up</a>
                </li>
            </ul>
        @endif
        @if (Route::is(['index-12']))
            <ul class="nav header-navbar-rht">
                <li class="header-theme me-3">
                    <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                        <i class="isax isax-sun-1"></i>
                    </a>
                    <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                        <i class="isax isax-moon"></i>
                    </a>
                </li>
                <li class="login-in-fourteen">
                    <a href="{{ url('login-email') }}" class="btn reg-btn log-btn-twelve">Log In</a>
                </li>
                <li class="login-in-fourteen">
                    <a href="#" class="reg-btn-thirteen regist-btn"><span>Register</span></a>
                </li>
            </ul>
        @endif
        @if (Route::is(['index-13']))
            <ul class="nav header-navbar-rht">
                <li class="searchbar">
                    <a href="javascript:void(0);"><i class="feather-search"></i></a>
                    <div class="togglesearch">
                        <form action="{{ url('search-2') }}">
                            <div class="input-group">
                                <input type="text" class="form-control">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </div>
                </li>
                <li class="header-theme me-3">
                    <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                        <i class="isax isax-sun-1"></i>
                    </a>
                    <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                        <i class="isax isax-moon"></i>
                    </a>
                </li>
                <li class="register-btn">
                    <a href="{{ url('login-email') }}" class="btn log-btn"><i class="feather-lock"></i>Login</a>
                </li>
                <li class="register-btn">
                    <a href="#" class="btn reg-btn"><i class="feather-user"></i>Sign Up</a>
                </li>
            </ul>
        @endif
        @if (Route::is(['index-14']))
            <ul class="nav header-navbar-rht">
                <li class="searchbar">
                    <a href="javascript:void(0);"><i class="feather-search"></i></a>
                    <div class="togglesearch">
                        <form action="{{ url('search-2') }}">
                            <div class="input-group">
                                <input type="text" class="form-control">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </div>
                </li>
                <li class="header-theme me-3">
                    <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                        <i class="isax isax-sun-1"></i>
                    </a>
                    <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                        <i class="isax isax-moon"></i>
                    </a>
                </li>
                <li class="register-btn">
                    <a href="{{ url('login-email') }}" class="btn log-btn"><i class="feather-lock"></i>Login</a>
                </li>
                <li class="register-btn">
                    <a href="#" class="btn reg-btn"><i class="feather-user"></i>Sign Up</a>
                </li>
            </ul>
        @endif
        @if (Route::is([
                'doctor-dashboard',
                'appointments',
                'available-timings',
                'my-patients',
                'patient-profile',
                'chat-doctor',
                'invoices',
                'doctor-profile-settings',
                'reviews',
                'doctor-blog',
                'doctor-add-blog',
                'accounts',
                'doctor-appointment-details',
                'doctor-appointments-grid',
                'doctor-appointment-start',
                'doctor-awards-settings',
                'doctor-business-settings',
                'doctor-cancelled-appointment',
                'doctor-cancelled-appointment-2',
                'doctor-change-password',
                'doctor-clinics-settings',
                'doctor-completed-appointment',
                'doctor-education-settings',
                'doctor-experience-settings',
                'doctor-insurance-settings',
                'doctor-payment',
                'doctor-pending-blog',
                'doctor-request',
                'doctor-specialities',
                'doctor-upcoming-appointment',
                'edit-blog',
                'social-media',
            ]))
            <ul class="nav header-navbar-rht">
                <li class="searchbar">
                    <a href="javascript:void(0);"><i class="feather-search"></i></a>
                    <div class="togglesearch">
                        <form action="{{ url('search') }}">
                            <div class="input-group">
                                <input type="text" class="form-control">
                                <button type="submit" class="btn">Search</button>
                            </div>
                        </form>
                    </div>
                </li>
                <li class="header-theme noti-nav">
                    <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                        <i class="isax isax-sun-1"></i>
                    </a>
                    <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                        <i class="isax isax-moon"></i>
                    </a>
                </li>

                <!-- Notifications -->
                @auth
                @php
                    $headerNotifications = Auth::user()->notifications()
                        ->where('type', 'App\Notifications\AdminAnnouncementNotification')
                        ->latest()
                        ->take(5)
                        ->get();
                    $headerUnreadCount = Auth::user()->unreadNotifications()
                        ->where('type', 'App\Notifications\AdminAnnouncementNotification')
                        ->count();
                @endphp
                <li class="nav-item dropdown noti-nav me-3 pe-0">
                    <a href="#" class="dropdown-toggle {{ $headerUnreadCount > 0 ? 'active-dot active-dot-danger' : '' }} nav-link p-0"
                        data-bs-toggle="dropdown">
                        <i class="isax isax-notification-bing"></i>
                        @if($headerUnreadCount > 0)
                            <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle" style="font-size: 10px; padding: 2px 5px;">{{ $headerUnreadCount > 9 ? '9+' : $headerUnreadCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu notifications dropdown-menu-end">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title">Notifications</span>
                            @if($headerUnreadCount > 0)
                                <a href="javascript:void(0)" class="clear-noti" onclick="markAllAsRead()">Mark All as Read</a>
                            @endif
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                @forelse($headerNotifications as $notification)
                                    @php
                                        $data = $notification->data;
                                        $isRead = !is_null($notification->read_at);
                                    @endphp
                                    <li class="notification-message {{ !$isRead ? 'unread' : '' }}">
                                        <a href="javascript:void(0)" onclick="markAsRead('{{ $notification->id }}')">
                                            <div class="notify-block d-flex">
                                                <span class="avatar">
                                                    <i class="fa-solid fa-bullhorn" style="font-size: 20px; padding: 8px;"></i>
                                                </span>
                                                <div class="media-body">
                                                    <h6>
                                                        {{ $data['title'] ?? 'Announcement' }}
                                                        <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                                    </h6>
                                                    <p class="noti-details">{{ Str::limit($data['message'] ?? '', 60) }}</p>
                                                    @if(isset($data['priority']) && in_array($data['priority'], ['urgent', 'high']))
                                                        <span class="badge bg-{{ $data['priority'] == 'urgent' ? 'danger' : 'warning' }} badge-sm">{{ ucfirst($data['priority']) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="notification-message">
                                        <div class="notify-block d-flex">
                                            <div class="media-body text-center py-3">
                                                <p class="text-muted mb-0">No notifications</p>
                                            </div>
                                        </div>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                        @if($headerNotifications->count() > 0)
                            <div class="topnav-dropdown-footer text-center">
                                <a href="{{ Auth::user()->isPatient() ? route('patient.dashboard') : (Auth::user()->isPsychologist() ? route('psychologist.dashboard') : '#') }}">View All</a>
                            </div>
                        @endif
                    </div>
                </li>
                @else
                <li class="nav-item dropdown noti-nav me-3 pe-0">
                    <a href="#" class="dropdown-toggle nav-link p-0" data-bs-toggle="dropdown">
                        <i class="isax isax-notification-bing"></i>
                    </a>
                    <div class="dropdown-menu notifications dropdown-menu-end">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title">Notifications</span>
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                <li class="notification-message">
                                    <div class="notify-block d-flex">
                                        <div class="media-body text-center py-3">
                                            <p class="text-muted mb-0">Please login to view notifications</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                @endauth
                <!-- /Notifications -->

                <!-- Messages -->
                <li class="nav-item noti-nav me-3 pe-0">
                    <a href="{{ url('chat-doctor') }}"
                        class="dropdown-toggle nav-link active-dot active-dot-success p-0">
                        <i class="isax isax-message-2"></i>
                    </a>
                </li>
                <!-- /Messages -->

                <!-- Cart -->
                <li class="nav-item dropdown noti-nav view-cart-header me-3 pe-0">
                    <a href="#"
                        class="dropdown-toggle nav-link active-dot active-dot-purple p-0 position-relative"
                        data-bs-toggle="dropdown">
                        <i class="isax isax-shopping-cart"></i>
                    </a>
                    <div class="dropdown-menu notifications dropdown-menu-end">
                        <div class="shopping-cart">
                            <ul class="shopping-cart-items list-unstyled">
                                <li class="clearfix">
                                    <div class="close-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                                    <a href="{{ url('product-description') }}"><img class="avatar-img rounded"
                                            src="{{ URL::asset('assets/img/products/product.jpg') }}"
                                            alt="User Image"></a>
                                    <a href="{{ url('product-description') }}" class="item-name">Benzaxapine
                                        Croplex</a>
                                    <span class="item-price">$849.99</span>
                                    <span class="item-quantity">Quantity: 01</span>
                                </li>

                                <li class="clearfix">
                                    <div class="close-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                                    <a href="{{ url('product-description') }}"><img class="avatar-img rounded"
                                            src="{{ URL::asset('assets/img/products/product1.jpg') }}"
                                            alt="User Image"></a>
                                    <a href="{{ url('product-description') }}" class="item-name">Ombinazol
                                        Bonibamol</a>
                                    <span class="item-price">$1,249.99</span>
                                    <span class="item-quantity">Quantity: 01</span>
                                </li>

                                <li class="clearfix">
                                    <div class="close-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                                    <a href="{{ url('product-description') }}"><img class="avatar-img rounded"
                                            src="{{ URL::asset('assets/img/products/product2.jpg') }}"
                                            alt="User Image"></a>
                                    <a href="{{ url('product-description') }}" class="item-name">Dantotate
                                        Dantodazole</a>
                                    <span class="item-price">$129.99</span>
                                    <span class="item-quantity">Quantity: 01</span>
                                </li>
                            </ul>
                            <div class="booking-summary pt-3">
                                <div class="booking-item-wrap">
                                    <ul class="booking-date">
                                        <li>Subtotal <span>$5,877.00</span></li>
                                        <li>Shipping <span>$25.00</span></li>
                                        <li>Tax <span>$0.00</span></li>
                                        <li>Total <span>$5.2555</span></li>
                                    </ul>
                                    <div class="booking-total">
                                        <ul class="booking-total-list text-align">
                                            <li>
                                                <div class="clinic-booking pt-3">
                                                    <a class="apt-btn" href="{{ url('cart') }}">View Cart</a>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="clinic-booking pt-3">
                                                    <a class="apt-btn"
                                                        href="{{ url('product-checkout') }}">Checkout</a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- /Cart -->

                <!-- User Menu -->
                <li class="nav-item dropdown has-arrow logged-item">
                    <a href="#" class="nav-link ps-0" data-bs-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle"
                                src="{{ URL::asset('assets/img/doctors-dashboard/doctor-profile-img.jpg') }}"
                                width="31" alt="Darren Elder">
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="{{ URL::asset('assets/img/doctors-dashboard/doctor-profile-img.jpg') }}"
                                    alt="User Image" class="avatar-img rounded-circle">
                            </div>
                            <div class="user-text">
                                <h6>Dr Edalin Hendry</h6>
                                <p class="text-muted mb-0">Doctor</p>
                            </div>
                        </div>
                        <a class="dropdown-item" href="{{ url('doctor-dashboard') }}">Dashboard</a>
                        <a class="dropdown-item" href="{{ url('doctor-profile-settings') }}">Profile Settings</a>
                        <form method="POST" action="{{ route('psychologist.logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item border-0 bg-transparent text-start w-100" style="padding: 0.5rem 1rem;">Logout</button>
                        </form>
                    </div>
                </li>
                <!-- /User Menu -->
            </ul>
        @endif
        @if (Route::is([
                'patient-dashboard',
                'map-grid',
                'map-list',
                'search',
                'search-2',
                'doctor-profile',
                'doctor-profile-2',
                'checkout',
                'booking-success',
                'favourites',
                'profile-settings',
                'change-password',
                'chat',
                'doctor-profile',
                'doctor-profile-2',
                'invoice-view',
                'medical-details',
                'medical-records',
                'patient-appointment-details',
                'patient-appointments',
                'patient-appointments-grid',
                'patient-cancelled-appointment',
                'patient-completed-appointment',
                'patient-invoices',
                'video-call',
                'voice-call',
                'two-factor-authentication',
                'terms-condition',
                'patient-accounts',
            ]))
            <ul class="nav header-navbar-rht">
                <li class="searchbar">
                    <a href="javascript:void(0);"><i class="feather-search"></i></a>
                    <div class="togglesearch">
                        <form action="{{ url('search') }}">
                            <div class="input-group">
                                <input type="text" class="form-control">
                                <button type="submit" class="btn">Search</button>
                            </div>
                        </form>
                    </div>
                </li>

                <li class="header-theme noti-nav">
                    <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle">
                        <i class="isax isax-sun-1"></i>
                    </a>
                    <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle activate">
                        <i class="isax isax-moon"></i>
                    </a>
                </li>

                <!-- Notifications -->
                @auth
                @php
                    if (!isset($headerNotifications)) {
                        $headerNotifications = Auth::user()->notifications()
                            ->where('type', 'App\Notifications\AdminAnnouncementNotification')
                            ->latest()
                            ->take(5)
                            ->get();
                        $headerUnreadCount = Auth::user()->unreadNotifications()
                            ->where('type', 'App\Notifications\AdminAnnouncementNotification')
                            ->count();
                    }
                @endphp
                <li class="nav-item dropdown noti-nav me-3 pe-0">
                    <a href="#" class="dropdown-toggle {{ $headerUnreadCount > 0 ? 'active-dot active-dot-danger' : '' }} nav-link p-0"
                        data-bs-toggle="dropdown">
                        <i class="isax isax-notification-bing"></i>
                        @if($headerUnreadCount > 0)
                            <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle" style="font-size: 10px; padding: 2px 5px;">{{ $headerUnreadCount > 9 ? '9+' : $headerUnreadCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu notifications dropdown-menu-end">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title">Notifications</span>
                            @if($headerUnreadCount > 0)
                                <a href="javascript:void(0)" class="clear-noti" onclick="markAllAsRead()">Mark All as Read</a>
                            @endif
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                @forelse($headerNotifications as $notification)
                                    @php
                                        $data = $notification->data;
                                        $isRead = !is_null($notification->read_at);
                                    @endphp
                                    <li class="notification-message {{ !$isRead ? 'unread' : '' }}">
                                        <a href="javascript:void(0)" onclick="markAsRead('{{ $notification->id }}')">
                                            <div class="notify-block d-flex">
                                                <span class="avatar">
                                                    <i class="fa-solid fa-bullhorn" style="font-size: 20px; padding: 8px;"></i>
                                                </span>
                                                <div class="media-body">
                                                    <h6>
                                                        {{ $data['title'] ?? 'Announcement' }}
                                                        <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                                    </h6>
                                                    <p class="noti-details">{{ Str::limit($data['message'] ?? '', 60) }}</p>
                                                    @if(isset($data['priority']) && in_array($data['priority'], ['urgent', 'high']))
                                                        <span class="badge bg-{{ $data['priority'] == 'urgent' ? 'danger' : 'warning' }} badge-sm">{{ ucfirst($data['priority']) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="notification-message">
                                        <div class="notify-block d-flex">
                                            <div class="media-body text-center py-3">
                                                <p class="text-muted mb-0">No notifications</p>
                                            </div>
                                        </div>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                        @if($headerNotifications->count() > 0)
                            <div class="topnav-dropdown-footer text-center">
                                <a href="{{ Auth::user()->isPatient() ? route('patient.dashboard') : (Auth::user()->isPsychologist() ? route('psychologist.dashboard') : '#') }}">View All</a>
                            </div>
                        @endif
                    </div>
                </li>
                @else
                <li class="nav-item dropdown noti-nav me-3 pe-0">
                    <a href="#" class="dropdown-toggle nav-link p-0" data-bs-toggle="dropdown">
                        <i class="isax isax-notification-bing"></i>
                    </a>
                    <div class="dropdown-menu notifications dropdown-menu-end">
                        <div class="topnav-dropdown-header">
                            <span class="notification-title">Notifications</span>
                        </div>
                        <div class="noti-content">
                            <ul class="notification-list">
                                <li class="notification-message">
                                    <div class="notify-block d-flex">
                                        <div class="media-body text-center py-3">
                                            <p class="text-muted mb-0">Please login to view notifications</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                @endauth
                <!-- /Notifications -->

                <!-- Messages -->
                <li class="nav-item noti-nav me-3 pe-0">
                    <a href="{{ url('chat') }}" class="dropdown-toggle nav-link active-dot active-dot-success p-0">
                        <i class="isax isax-message-2"></i>
                    </a>
                </li>
                <!-- /Messages -->

                <!-- Cart -->
                <li class="nav-item dropdown noti-nav view-cart-header me-3 pe-0">
                    <a href="#"
                        class="dropdown-toggle nav-link active-dot active-dot-purple p-0 position-relative"
                        data-bs-toggle="dropdown">
                        <i class="isax isax-shopping-cart"></i>
                    </a>
                    <div class="dropdown-menu notifications dropdown-menu-end">
                        <div class="shopping-cart">
                            <ul class="shopping-cart-items list-unstyled">
                                <li class="clearfix">
                                    <div class="close-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                                    <a href="{{ url('product-description') }}"><img class="avatar-img rounded"
                                            src="{{ URL::asset('assets/img/products/product.jpg') }}"
                                            alt="User Image"></a>
                                    <a href="{{ url('product-description') }}" class="item-name">Benzaxapine
                                        Croplex</a>
                                    <span class="item-price">$849.99</span>
                                    <span class="item-quantity">Quantity: 01</span>
                                </li>

                                <li class="clearfix">
                                    <div class="close-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                                    <a href="{{ url('product-description') }}"><img class="avatar-img rounded"
                                            src="{{ URL::asset('assets/img/products/product1.jpg') }}"
                                            alt="User Image"></a>
                                    <a href="{{ url('product-description') }}" class="item-name">Ombinazol
                                        Bonibamol</a>
                                    <span class="item-price">$1,249.99</span>
                                    <span class="item-quantity">Quantity: 01</span>
                                </li>

                                <li class="clearfix">
                                    <div class="close-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                                    <a href="{{ url('product-description') }}"><img class="avatar-img rounded"
                                            src="{{ URL::asset('assets/img/products/product2.jpg') }}"
                                            alt="User Image"></a>
                                    <a href="{{ url('product-description') }}" class="item-name">Dantotate
                                        Dantodazole</a>
                                    <span class="item-price">$129.99</span>
                                    <span class="item-quantity">Quantity: 01</span>
                                </li>
                            </ul>
                            <div class="booking-summary pt-3">
                                <div class="booking-item-wrap">
                                    <ul class="booking-date">
                                        <li>Subtotal <span>$5,877.00</span></li>
                                        <li>Shipping <span>$25.00</span></li>
                                        <li>Tax <span>$0.00</span></li>
                                        <li>Total <span>$5.2555</span></li>
                                    </ul>
                                    <div class="booking-total">
                                        <ul class="booking-total-list text-align">
                                            <li>
                                                <div class="clinic-booking pt-3">
                                                    <a class="apt-btn" href="{{ url('cart') }}">View Cart</a>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="clinic-booking pt-3">
                                                    <a class="apt-btn"
                                                        href="{{ url('product-checkout') }}">Checkout</a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- /Cart -->

                <!-- User Menu -->
                <li class="nav-item dropdown has-arrow logged-item">
                    <a href="#" class="nav-link ps-0" data-bs-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle"
                                src="{{ URL::asset('assets/img/doctors-dashboard/profile-06.jpg') }}" width="31"
                                alt="Darren Elder">
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="{{ URL::asset('assets/img/doctors-dashboard/profile-06.jpg') }}"
                                    alt="User Image" class="avatar-img rounded-circle">
                            </div>
                            <div class="user-text">
                                <h6>Hendrita Hayes</h6>
                                <p class="text-muted mb-0">Patient</p>
                            </div>
                        </div>
                        <a class="dropdown-item" href="{{ url('patient-dashboard') }}">Dashboard</a>
                        <a class="dropdown-item" href="{{ url('profile-settings') }}">Profile Settings</a>
                        <a class="dropdown-item" href="#">Logout</a>
                    </div>
                </li>
                <!-- /User Menu -->
            </ul>
        @endif
        @if (
            !Route::is([
                'index-2',
                'index-3',
                'index-5',
                'index-4',
                'index-6',
                'index-7',
                'index-8',
                'index-9',
                'index-10',
                'index-11',
                'index-12',
                'pharmacy-index',
                'index-13',
                'index-14',
            ]))
</div>
@endif

</nav>
</div>
</header>
<!-- /Header -->
