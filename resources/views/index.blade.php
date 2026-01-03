<?php $page = 'index'; ?>
@extends('layout.mainlayout')
@section('content')
   
	<!-- Home Banner -->
    <section class="banner-section banner-sec-one">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="banner-content aos" data-aos="fade-up">
                       
                        <h1 class="display-5">Discover Health: Find Your Trusted <span class="banner-icon"><img src="{{URL::asset('assets/img/icons/video.svg')}}" alt="img"></span> <span class="text-gradient">Doctors</span> Today</h1>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="banner-img aos" data-aos="fade-up">
                        <img src="{{URL::asset('assets/img/banner/banner-doctor.svg')}}" class="img-fluid" alt="patient-image">
                       
                       
                    </div>
                </div>
            </div>
        </div>
        <div class="banner-bg">
            <img src="{{URL::asset('assets/img/bg/banner-bg-02.png')}}" alt="img" class="banner-bg-01">
            <img src="{{URL::asset('assets/img/bg/banner-bg-03.png')}}" alt="img" class="banner-bg-02">
            <img src="{{URL::asset('assets/img/bg/banner-bg-04.png')}}" alt="img" class="banner-bg-03">
            <img src="{{URL::asset('assets/img/bg/banner-bg-05.png')}}" alt="img" class="banner-bg-04">
            <img src="{{URL::asset('assets/img/bg/banner-icon-01.svg')}}" alt="img" class="banner-bg-05">
            <img src="{{URL::asset('assets/img/bg/banner-icon-01.svg')}}" alt="img" class="banner-bg-06">
        </div>
    </section>
    <!-- /Home Banner -->

   
    <!-- /List -->

    <!-- Speciality Section -->
    <section class="speciality-section">
        <style>
            .speciality-section .spaciality-img {
                background: transparent !important;
            }
        </style>
        <div class="container">
            <div class="section-header sec-header-one text-center aos" data-aos="fade-up">
                <span class="badge badge-primary">Top Specialties</span>
                <h2>We are Ready to Help You.</h2>
            </div>
            <div class="row g-4 aos" data-aos="fade-up">
                <div class="col-lg-3 col-md-6">
                    <div class="spaciality-item">
                        <div class="spaciality-img" style="border: none; overflow: hidden; background: transparent;">
                            <img src="{{ asset('assets/index/speciality-02.jpg') }}" class="img-fluid" alt="Career Counseling" style="max-width: 100%; height: 200px; object-fit: cover; border: none;">
                        </div>
                        <h6><a href="{{url('doctor-grid')}}">Career Counseling</a></h6>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="spaciality-item">
                        <div class="spaciality-img" style="border: none; overflow: hidden; background: transparent;">
                            <img src="{{ asset('assets/index/speciality-01.jpg') }}" class="img-fluid" alt="Relationship Issues" style="max-width: 100%; height: 200px; object-fit: cover; border: none;">
                        </div>
                        <h6><a href="{{url('doctor-grid')}}">Relationship Issues</a></h6>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="spaciality-item">
                        <div class="spaciality-img" style="border: none; overflow: hidden; background: transparent;">
                            <img src="{{ asset('assets/index/speciality-01.jpg') }}" class="img-fluid" alt="Women's Issues" style="max-width: 100%; height: 200px; object-fit: cover; border: none;">
                        </div>
                        <h6><a href="{{url('doctor-grid')}}">Women's Issues</a></h6>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="spaciality-item">
                        <div class="spaciality-img" style="border: none; overflow: hidden; background: transparent;">
                            <img src="{{ asset('assets/index/speciality-01.jpg') }}" class="img-fluid" alt="Self-Esteem Issues" style="max-width: 100%; height: 200px; object-fit: cover; border: none;">
                        </div>
                        <h6><a href="{{url('doctor-grid')}}">Self-Esteem Issues</a></h6>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Speciality Section -->

    <!-- Doctor Section -->
    <section class="doctor-section">
        <div class="container">
            <div class="section-header sec-header-one text-center aos" data-aos="fade-up">
                <span class="badge badge-primary">Featured Psychologist</span>
                <h2>Our Best Psychologist</h2>
            </div>

            <div class="doctors-slider owl-carousel aos" data-aos="fade-up">
                <div class="card">
                    <div class="card-img card-img-hover">
                        <a href="#"><img src="{{ asset('assets/index/doctor-grid-01.jpg') }}" alt=""></a>
                        <div class="grid-overlay-item d-flex align-items-center justify-content-between">
                            <span class="badge bg-orange"><i class="fa-solid fa-star me-1"></i>5.0</span>
                            <a href="javascript:void(0)" class="fav-icon">
                                <i class="fa fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="d-flex active-bar align-items-center justify-content-between p-3">
                            <a href="#" class="text-indigo fw-medium fs-14">Career Counseling</a>
                            <span class="badge bg-success-light d-inline-flex align-items-center">
                                <i class="fa-solid fa-circle fs-5 me-1"></i>
                                Available
                            </span>
                        </div>
                        <div class="p-3 pt-0">
                            <div class="doctor-info-detail mb-3 pb-3">
                                <h3 class="mb-1"><a href="#">Dr. Michael Brown</a></h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1">Consultation Fees</p>
                                    <h3 class="text-orange">$650</h3>
                                </div>
                                <a href="#" class="btn btn-md btn-dark inline-flex align-items-center rounded-pill">
                                    <i class="isax isax-calendar-1 me-2"></i>
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img card-img-hover">
                        <a href="#"><img src="{{ asset('assets/index/doctor-grid-02.jpg') }}" alt=""></a>
                        <div class="grid-overlay-item d-flex align-items-center justify-content-between">
                            <span class="badge bg-orange"><i class="fa-solid fa-star me-1"></i>4.6</span>
                            <a href="javascript:void(0)" class="fav-icon">
                                <i class="fa fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="d-flex active-bar active-bar-pink align-items-center justify-content-between p-3">
                            <a href="#" class="text-pink fw-medium fs-14">Relationship Issues</a>
                            <span class="badge bg-success-light d-inline-flex align-items-center">
                                <i class="fa-solid fa-circle fs-5 me-1"></i>
                                Available
                            </span>
                        </div>
                        <div class="p-3 pt-0">
                            <div class="doctor-info-detail mb-3 pb-3">
                                <h3 class="mb-1"><a href="#">Dr. Nicholas Tello</a></h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1">Consultation Fees</p>
                                    <h3 class="text-orange">$400</h3>
                                </div>
                                <a href="#" class="btn btn-md btn-dark inline-flex align-items-center rounded-pill">
                                    <i class="isax isax-calendar-1 me-2"></i>
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img card-img-hover">
                        <a href="#"><img src="{{ asset('assets/index/doctor-grid-01.jpg') }}" alt=""></a>
                        <div class="grid-overlay-item d-flex align-items-center justify-content-between">
                            <span class="badge bg-orange"><i class="fa-solid fa-star me-1"></i>4.8</span>
                            <a href="javascript:void(0)" class="fav-icon">
                                <i class="fa fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="d-flex active-bar active-bar-teal align-items-center justify-content-between p-3">
                            <a href="#" class="text-teal fw-medium fs-14">Women's Issues</a>
                            <span class="badge bg-success-light d-inline-flex align-items-center">
                                <i class="fa-solid fa-circle fs-5 me-1"></i>
                                Available
                            </span>
                        </div>
                        <div class="p-3 pt-0">
                            <div class="doctor-info-detail mb-3 pb-3">
                                <h3 class="mb-1"><a href="#">Dr. Harold Bryant</a></h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1">Consultation Fees</p>
                                    <h3 class="text-orange">$500</h3>
                                </div>
                                <a href="#" class="btn btn-md btn-dark inline-flex align-items-center rounded-pill">
                                    <i class="isax isax-calendar-1 me-2"></i>
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img card-img-hover">
                        <a href="#"><img src="{{ asset('assets/index/doctor-grid-04.jpg') }}" alt=""></a>
                        <div class="grid-overlay-item d-flex align-items-center justify-content-between">
                            <span class="badge bg-orange"><i class="fa-solid fa-star me-1"></i>4.8</span>
                            <a href="javascript:void(0)" class="fav-icon">
                                <i class="fa fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="d-flex active-bar active-bar-info align-items-center justify-content-between p-3">
                            <a href="#" class="text-info fw-medium fs-14">Self-Esteem Issues</a>
                            <span class="badge bg-success-light d-inline-flex align-items-center">
                                <i class="fa-solid fa-circle fs-5 me-1"></i>
                                Available
                            </span>
                        </div>
                        <div class="p-3 pt-0">
                            <div class="doctor-info-detail mb-3 pb-3">
                                <h3 class="mb-1"><a href="#">Dr. Sandra Jones</a></h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1">Consultation Fees</p>
                                    <h3 class="text-orange">$550</h3>
                                </div>
                                <a href="#" class="btn btn-md btn-dark inline-flex align-items-center rounded-pill">
                                    <i class="isax isax-calendar-1 me-2"></i>
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img card-img-hover">
                        <a href="#"><img src="{{ asset('assets/index/doctor-grid-01.jpg') }}" alt=""></a>
                        <div class="grid-overlay-item d-flex align-items-center justify-content-between">
                            <span class="badge bg-orange"><i class="fa-solid fa-star me-1"></i>4.2</span>
                            <a href="javascript:void(0)" class="fav-icon">
                                <i class="fa fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="d-flex active-bar active-bar-teal align-items-center justify-content-between p-3">
                            <a href="#" class="text-teal fw-medium fs-14">Career Counseling</a>
                            <span class="badge bg-success-light d-inline-flex align-items-center">
                                <i class="fa-solid fa-circle fs-5 me-1"></i>
                                Available
                            </span>
                        </div>
                        <div class="p-3 pt-0">
                            <div class="doctor-info-detail mb-3 pb-3">
                                <h3 class="mb-1"><a href="#">Dr. Charles Scott</a></h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1">Consultation Fees</p>
                                    <h3 class="text-orange">$600</h3>
                                </div>
                                <a href="#" class="btn btn-md btn-dark inline-flex align-items-center rounded-pill">
                                    <i class="isax isax-calendar-1 me-2"></i>
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img card-img-hover">
                        <a href="#"><img src="{{ asset('assets/index/doctor-grid-02.jpg') }}" alt=""></a>
                        <div class="grid-overlay-item d-flex align-items-center justify-content-between">
                            <span class="badge bg-orange"><i class="fa-solid fa-star me-1"></i>4.9</span>
                            <a href="javascript:void(0)" class="fav-icon">
                                <i class="fa fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="d-flex active-bar active-bar-pink align-items-center justify-content-between p-3">
                            <a href="#" class="text-pink fw-medium fs-14">Relationship Issues</a>
                            <span class="badge bg-success-light d-inline-flex align-items-center">
                                <i class="fa-solid fa-circle fs-5 me-1"></i>
                                Available
                            </span>
                        </div>
                        <div class="p-3 pt-0">
                            <div class="doctor-info-detail mb-3 pb-3">
                                <h3 class="mb-1"><a href="#">Dr. Sarah Williams</a></h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1">Consultation Fees</p>
                                    <h3 class="text-orange">$450</h3>
                                </div>
                                <a href="#" class="btn btn-md btn-dark inline-flex align-items-center rounded-pill">
                                    <i class="isax isax-calendar-1 me-2"></i>
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img card-img-hover">
                        <a href="#"><img src="{{ asset('assets/index/doctor-grid-03.jpg') }}" alt=""></a>
                        <div class="grid-overlay-item d-flex align-items-center justify-content-between">
                            <span class="badge bg-orange"><i class="fa-solid fa-star me-1"></i>4.7</span>
                            <a href="javascript:void(0)" class="fav-icon">
                                <i class="fa fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="d-flex active-bar active-bar-teal align-items-center justify-content-between p-3">
                            <a href="#" class="text-teal fw-medium fs-14">Women's Issues</a>
                            <span class="badge bg-success-light d-inline-flex align-items-center">
                                <i class="fa-solid fa-circle fs-5 me-1"></i>
                                Available
                            </span>
                        </div>
                        <div class="p-3 pt-0">
                            <div class="doctor-info-detail mb-3 pb-3">
                                <h3 class="mb-1"><a href="#">Dr. Emily Davis</a></h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1">Consultation Fees</p>
                                    <h3 class="text-orange">$520</h3>
                                </div>
                                <a href="#" class="btn btn-md btn-dark inline-flex align-items-center rounded-pill">
                                    <i class="isax isax-calendar-1 me-2"></i>
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img card-img-hover">
                        <a href="#"><img src="{{ asset('assets/index/doctor-grid-01.jpg') }}" alt=""></a>
                        <div class="grid-overlay-item d-flex align-items-center justify-content-between">
                            <span class="badge bg-orange"><i class="fa-solid fa-star me-1"></i>4.5</span>
                            <a href="javascript:void(0)" class="fav-icon">
                                <i class="fa fa-heart"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="d-flex active-bar active-bar-info align-items-center justify-content-between p-3">
                            <a href="#" class="text-info fw-medium fs-14">Self-Esteem Issues</a>
                            <span class="badge bg-success-light d-inline-flex align-items-center">
                                <i class="fa-solid fa-circle fs-5 me-1"></i>
                                Available
                            </span>
                        </div>
                        <div class="p-3 pt-0">
                            <div class="doctor-info-detail mb-3 pb-3">
                                <h3 class="mb-1"><a href="#">Dr. James Wilson</a></h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1">Consultation Fees</p>
                                    <h3 class="text-orange">$480</h3>
                                </div>
                                <a href="#" class="btn btn-md btn-dark inline-flex align-items-center rounded-pill">
                                    <i class="isax isax-calendar-1 me-2"></i>
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>					
            <div class="doctor-nav nav-bottom owl-nav"></div>
        </div>
    </section>
    <!-- /Doctor Section -->

    <!-- Services Section -->
   
    <!-- /Services Section -->

    <!-- Reasons Section -->
    <section class="reason-section">
        <div class="container">
            <div class="section-header sec-header-one text-center aos" data-aos="fade-up">
                <span class="badge badge-primary">Why Book With Us</span>
                <h2>Compelling Reasons to Choose</h2>
            </div>
            <div class="row row-gap-4 justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="reason-item aos" data-aos="fade-up">
                        <h6 class="mb-2"><i class="isax isax-tag-user5 text-orange me-2"></i>Follow-Up Care</h6>
                        <p class="fs-14 mb-0">We ensure continuity of care through regular follow-ups and communication, helping you stay on track with health goals.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="reason-item aos" data-aos="fade-up">
                        <h6 class="mb-2"><i class="isax isax-voice-cricle text-purple me-2"></i>Patient-Centered Approach</h6>
                        <p class="fs-14 mb-0">We prioritize your comfort and preferences, tailoring our services to meet your individual needs and Care from Our Experts</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="reason-item aos" data-aos="fade-up">
                        <h6 class="mb-2"><i class="isax isax-wallet-add-15 text-cyan me-2"></i>Convenient Access</h6>
                        <p class="fs-14 mb-0">Easily book appointments online or through our dedicated customer service team, with flexible hours to fit your schedule.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Reasons Section -->

    <!-- Bookus Section -->
    <section class="bookus-section" style="background-color:rgb(1, 90, 186);">
        <div class="container">
            <div class="row align-items-center row-gap-4">
              
               
            </div>
            <div class="bookus-sec">
                <div class="row g-4">
                    <div class="col-lg-3">
                        <div class="book-item">
                            <div class="book-icon bg-primary">
                                <i class="isax isax-search-normal5"></i>
                            </div>
                            <div class="book-info">
                                <h6 class="text-white mb-2">Search For Doctors</h6>
                                <p class="fs-14 text-light">Search for a doctor based on specialization, location, or availability for your Treatements</p>
                            </div>
                            <div class="way-icon">
                                <img src="{{URL::asset('assets/img/icons/way-icon.svg')}}" alt="img">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="book-item">
                            <div class="book-icon bg-orange">
                                <i class="isax isax-security-user5"></i>
                            </div>
                            <div class="book-info">
                                <h6 class="text-white mb-2">Check Doctor Profile</h6>
                                <p class="fs-14 text-light">Explore detailed doctor profiles on our platform to make informed healthcare decisions.</p>
                            </div>
                            <div class="way-icon">
                                <img src="{{URL::asset('assets/img/icons/way-icon.svg')}}" alt="img">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="book-item">
                            <div class="book-icon bg-cyan">
                                <i class="isax isax-calendar5"></i>
                            </div>
                            <div class="book-info">
                                <h6 class="text-white mb-2">Schedule Appointment</h6>
                                <p class="fs-14 text-light">After choose your preferred doctor, select a convenient time slot, & confirm your appointment.</p>
                            </div>
                            <div class="way-icon">
                                <img src="{{URL::asset('assets/img/icons/way-icon.svg')}}" alt="img">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="book-item">
                            <div class="book-icon bg-indigo">
                                <i class="isax isax-blend5"></i>
                            </div>
                            <div class="book-info">
                                <h6 class="text-white mb-2">Get Your Solution</h6>
                                <p class="fs-14 text-light">Discuss your health concerns with the doctor and receive the personalized advice & with solution.</p>
                            </div>
                        </div>
                    </div>
                </div>		
            </div>		
        </div>
    </section>
    <!-- /Bookus Section -->
    
    <!-- Testimonial Section -->
    <section class="testimonial-section-one">
        <div class="container">
            <div class="section-header sec-header-one text-center aos" data-aos="fade-up">
                <span class="badge badge-primary">Testimonials</span>
                <h2>Reviews of Our Patients</h2>
            </div>

            <!-- Testimonial Slider -->
            <div class="owl-carousel testimonials-slider aos" data-aos="fade-up">
                <div class="card shadow-none mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rating d-flex">
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled"></i>
                            </div>
                            <span>
                                <img src="{{URL::asset('assets/img/icons/quote-icon.svg')}}" alt="img">
                            </span>
                        </div>
                        <h6 class="fs-16 fw-medium mb-2">Nice Treatment</h6>
                        <p>I had a wonderful experience the staff was friendly and attentive, and Dr. Smith took the time to explain everything clearly.</p>
                        <div class="d-flex align-items-center">
                            <a href="javascript:void(0);" class="avatar avatar-lg">
                                <img src="{{ asset('assets/index/patient22.jpg') }}" class="rounded-circle" alt="img">
                            </a>
                            <div class="ms-2">
                                <h6 class="mb-1"><a href="javascript:void(0);">Deny Hendrawan</a></h6>
                                <p class="fs-14 mb-0">United States</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-none mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rating d-flex">
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled"></i>
                            </div>
                            <span>
                                <img src="{{URL::asset('assets/img/icons/quote-icon.svg')}}" alt="img">
                            </span>
                        </div>
                        <h6 class="fs-16 fw-medium mb-2">Good Hospitability</h6>
                        <p>Genuinely cares about his patients. He helped me understand my condition and worked with me to create a plan.</p>
                        <div class="d-flex align-items-center">
                            <a href="javascript:void(0);" class="avatar avatar-lg">
                                <img src="{{ asset('assets/index/patient21.jpg') }}" class="rounded-circle" alt="img">
                            </a>
                            <div class="ms-2">
                                <h6 class="mb-1"><a href="javascript:void(0);">Johnson DWayne</a></h6>
                                <p class="fs-14 mb-0">United States</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-none mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rating d-flex">
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled"></i>
                            </div>
                            <span>
                                <img src="{{URL::asset('assets/img/icons/quote-icon.svg')}}" alt="img">
                            </span>
                        </div>
                        <h6 class="fs-16 fw-medium mb-2">Nice Treatment</h6>
                        <p>I had a great experience with Dr. Chen. She was not only professional but also made me feel comfortable discussing.</p>
                        <div class="d-flex align-items-center">
                            <a href="javascript:void(0);" class="avatar avatar-lg">
                                <img src="{{ asset('assets/index/patient.jpg') }}" class="rounded-circle" alt="img">
                            </a>
                            <div class="ms-2">
                                <h6 class="mb-1"><a href="javascript:void(0);">Rayan Smith</a></h6>
                                <p class="fs-14 mb-0">United States</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-none mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rating d-flex">
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled me-1"></i>
                                <i class="fa-solid fa-star filled"></i>
                            </div>
                            <span>
                                <img src="{{URL::asset('assets/img/icons/quote-icon.svg')}}" alt="img">
                            </span>
                        </div>
                        <h6 class="fs-16 fw-medium mb-2">Excellent Service</h6>
                        <p>I had a wonderful experience the staff was friendly and attentive, and Dr. Smith took the time to explain everything clearly.</p>
                        <div class="d-flex align-items-center">
                            <a href="javascript:void(0);" class="avatar avatar-lg">
                                <img src="{{URL::asset('assets/img/patients/patient23.jpg')}}" class="rounded-circle" alt="img">
                            </a>
                            <div class="ms-2">
                                <h6 class="mb-1"><a href="javascript:void(0);">Sofia Doe</a></h6>
                                <p class="fs-14 mb-0">United States</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Testimonial Slider -->

            <!-- Counter -->
           
            <!-- /Counter -->

        </div>
    </section>
    <!-- /Testimonial Section -->

   

    <section class="faq-section-one">
        <div class="container">
            <div class="section-header sec-header-one text-center aos" data-aos="fade-up">
                <span class="badge badge-primary">FAQ’S</span>
                <h2>Your Questions are Answered</h2>
            </div>
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <div class="faq-info aos" data-aos="fade-up">
                        <div class="accordion" id="faq-details">

                            <!-- FAQ Item -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <a href="javascript:void(0);" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        How do I book an appointment with a doctor?
                                    </a>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faq-details">
                                    <div class="accordion-body">
                                        <div class="accordion-content">
                                            <p>Yes, simply visit our website and log in or create an account. Search for a doctor based on specialization, location, or availability & confirm your booking.</p>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <!-- /FAQ Item -->

                            <!-- FAQ Item -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <a href="javascript:void(0);" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Can I request a specific doctor when booking my appointment? 
                                    </a>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faq-details">
                                    <div class="accordion-body">
                                        <div class="accordion-content">
                                            <p>Yes, you can usually request a specific doctor when booking your appointment, though availability may vary based on their schedule.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /FAQ Item -->

                            <!-- FAQ Item -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <a href="javascript:void(0);" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        What should I do if I need to cancel or reschedule my appointment?
                                    </a>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faq-details">
                                    <div class="accordion-body">
                                        <div class="accordion-content">
                                            <p>If you need to cancel or reschedule your appointment, contact the doctor as soon as possible to inform them and to reschedule for another available time slot.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /FAQ Item -->

                            <!-- FAQ Item -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <a href="javascript:void(0);" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        What if I'm running late for my appointment?
                                    </a>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faq-details">
                                    <div class="accordion-body">
                                        <div class="accordion-content">
                                            <p>If you know you will be late, it's courteous to call the doctor's office and inform them. Depending on their policy and schedule, they may be able to accommodate you or reschedule your appointment.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /FAQ Item -->

                            <!-- FAQ Item -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFive">
                                    <a href="javascript:void(0);" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        Can I book appointments for family members or dependents?
                                    </a>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faq-details">
                                    <div class="accordion-body">
                                        <div class="accordion-content">
                                            <p>Yes, in many cases, you can book appointments for family members or dependents. However, you may need to provide their personal information and consent to do so.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /FAQ Item -->
                                        
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- App Section -->
  
    <!-- /App Section -->

    <!-- Article Section -->
   
    <!-- /Article Section -->

    <!-- Info Section -->
    
    <!-- /Info Section -->

    @component('components.scrolltotop')
    @endcomponent
@endsection
