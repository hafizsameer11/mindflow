<?php $page = 'appointments'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Doctor
        @endslot
        @slot('li_1')
            Appointments
        @endslot
        @slot('li_2')
        Appointments
    @endslot
    @endcomponent
   
   	<!-- Page Content -->
       <div class="content">
        <div class="container">

            <div class="row">
                <div class="col-lg-4 col-xl-3 theiaStickySidebar">
                    
                    @component('components.sidebar_doctor')
                    @endcomponent 
                    
                </div>
                
                <div class="col-lg-8 col-xl-9">
                    <div class="dashboard-header">
                        <h3>Appointments</h3>
                        <ul class="header-list-btns">
                            <li>
                                <div class="input-block dash-search-input">
                                    <input type="text" class="form-control" placeholder="Search">
                                    <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="appointment-tab-head">
                        <div class="appointment-tabs">
                            <ul class="nav nav-pills inner-tab " id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-upcoming-tab" data-bs-toggle="pill" data-bs-target="#pills-upcoming" type="button" role="tab" aria-controls="pills-upcoming" aria-selected="false">Upcoming<span>21</span></button>
                                </li>	
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-cancel-tab" data-bs-toggle="pill" data-bs-target="#pills-cancel" type="button" role="tab" aria-controls="pills-cancel" aria-selected="true">Cancelled<span>16</span></button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-complete-tab" data-bs-toggle="pill" data-bs-target="#pills-complete" type="button" role="tab" aria-controls="pills-complete" aria-selected="true">Completed<span>214</span></button>
                                </li>
                            </ul>
                        </div>
                        <div class="filter-head">
                            <div class="position-relative daterange-wraper me-2">
                                <div class="input-groupicon calender-input">
                                    <input type="text" class="form-control  date-range bookingrange" placeholder="From Date - To Date ">
                                </div>
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content appointment-tab-content">
                        <div class="tab-pane fade show active" id="pills-upcoming" role="tabpanel" aria-labelledby="pills-upcoming-tab">
                            @forelse($appointments ?? [] as $appointment)
                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{ route('psychologist.appointments.show', $appointment->id) }}">
                                                <img src="{{ asset('assets/index/patient.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                <h6><a href="{{ route('psychologist.appointments.show', $appointment->id) }}">{{ $appointment->patient->user->name }}</a>
                                                    @if($appointment->status === 'pending')
                                                    <span class="badge new-tag">New</span>
                                                    @endif
                                                </h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>{{ $appointment->appointment_date->format('d M Y') }} {{ $appointment->appointment_time }}</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>{{ $appointment->psychologist->specialization }}</li>
                                            <li>{{ ucfirst($appointment->consultation_type) }} Call</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="mail-info-patient">
                                        <ul>
                                            <li><i class="fa-solid fa-envelope"></i>{{ $appointment->patient->user->email }}</li>
                                            <li><i class="fa-solid fa-phone"></i>{{ $appointment->patient->user->phone ?? 'N/A' }}</li>
                                        </ul>
                                    </li>
                                    <li class="appointment-action">
                                        <ul>
                                            <li>
                                                <a href="{{ route('psychologist.appointments.show', $appointment->id) }}"><i class="fa-solid fa-eye"></i></a>
                                            </li>
                                            @if($appointment->status === 'pending')
                                            <li>
                                                <form action="{{ route('psychologist.appointments.confirm', $appointment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="border-0 bg-transparent text-primary"><i class="fa-solid fa-check"></i></button>
                                                </form>
                                            </li>
                                            @endif
                                            <li>
                                                <form action="{{ route('psychologist.appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="border-0 bg-transparent text-danger"><i class="fa-solid fa-xmark"></i></button>
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                    @if($appointment->status === 'confirmed')
                                    <li class="appointment-start">
                                        <a href="{{ route('psychologist.session.start', $appointment->id) }}" class="start-link">Start Now</a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <!-- /Appointment List -->
                            @empty
                            <div class="text-center py-5">
                                <p>No appointments found</p>
                            </div>
                            @endforelse
                            
                            @if(isset($appointments) && $appointments->hasPages())
                            <div class="mt-4">
                                {{ $appointments->links() }}
                            </div>
                            @endif
                        </div>

                            <!-- Appointment List -->
                            
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                          
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                          
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                         
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            
                            <!-- /Appointment List -->

                            <!-- Pagination -->
                          
                            <!-- /Pagination -->

                        </div>
                        <div class="tab-pane fade {{ request('status') == 'cancelled' ? 'show active' : '' }}" id="pills-cancel" role="tabpanel" aria-labelledby="pills-cancel-tab">
                            @forelse($appointments ?? [] as $appointment)
                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{ route('psychologist.appointments.show', $appointment->id) }}">
                                                <img src="{{ asset('assets/index/patient.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                <h6><a href="{{ route('psychologist.appointments.show', $appointment->id) }}">{{ $appointment->patient->user->name }}</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>{{ $appointment->appointment_date->format('d M Y') }} {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>{{ $appointment->psychologist->specialization }}</li>
                                            <li>Video Meeting</li>
                                        </ul>
                                    </li>
                                    <li class="mail-info-patient">
                                        <ul>
                                            <li><i class="fa-solid fa-envelope"></i>{{ $appointment->patient->user->email }}</li>
                                            <li><i class="fa-solid fa-phone"></i>{{ $appointment->patient->user->phone ?? 'N/A' }}</li>
                                        </ul>
                                    </li>
                                    <li class="appointment-action">
                                        <ul>
                                            <li>
                                                <a href="{{ route('psychologist.appointments.show', $appointment->id) }}"><i class="fa-solid fa-eye"></i></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{ route('psychologist.appointments.show', $appointment->id) }}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->
                            @empty
                            <div class="text-center py-5">
                                <p>No cancelled appointments found</p>
                            </div>
                            @endforelse
                            
                            @if(isset($appointments) && $appointments->hasPages())
                            <div class="mt-4">
                                {{ $appointments->links() }}
                            </div>
                            @endif
                        </div>
                        <div class="tab-pane fade {{ request('status') == 'completed' ? 'show active' : '' }}" id="pills-complete" role="tabpanel" aria-labelledby="pills-complete-tab">
                            @forelse($appointments ?? [] as $appointment)
                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{ route('psychologist.appointments.show', $appointment->id) }}">
                                                <img src="{{ asset('assets/index/patient.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#APT{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</p>
                                                <h6><a href="{{ route('psychologist.appointments.show', $appointment->id) }}">{{ $appointment->patient->user->name }}</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>{{ $appointment->appointment_date->format('d M Y') }} {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>{{ $appointment->psychologist->specialization }}</li>
                                            <li>Video Meeting</li>
                                        </ul>
                                    </li>
                                    <li class="mail-info-patient">
                                        <ul>
                                            <li><i class="fa-solid fa-envelope"></i>{{ $appointment->patient->user->email }}</li>
                                            <li><i class="fa-solid fa-phone"></i>{{ $appointment->patient->user->phone ?? 'N/A' }}</li>
                                        </ul>
                                    </li>
                                    <li class="appointment-action">
                                        <ul>
                                            <li>
                                                <a href="{{ route('psychologist.appointments.show', $appointment->id) }}"><i class="fa-solid fa-eye"></i></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{ route('psychologist.appointments.show', $appointment->id) }}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->
                            @empty
                            <div class="text-center py-5">
                                <p>No completed appointments found</p>
                            </div>
                            @endforelse
                            
                            @if(isset($appointments) && $appointments->hasPages())
                            <div class="mt-4">
                                {{ $appointments->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-cancelled-appointment')}}">
                                                <img src="{{ asset('assets/index/patient21.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0002</p>
                                                <h6><a href="{{url('doctor-cancelled-appointment')}}">Kelly</a><span class="badge new-tag">New</span></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>05 Nov 2024 11.50 AM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Audio Call</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-cancelled-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-cancelled-appointment')}}">
                                                <img src="{{ asset('assets/index/patient22.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0003</p>
                                                <h6><a href="{{url('doctor-cancelled-appointment')}}">Samuel</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>27 Oct 2024 09.30 AM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Video Call</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-cancelled-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-cancelled-appointment')}}">
                                                <img src="{{ asset('assets/index/patient.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0004</p>
                                                <h6><a href="{{url('doctor-cancelled-appointment')}}">Catherine</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>18 Oct 2024 12.20 PM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Direct Visit</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-cancelled-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-cancelled-appointment')}}">
                                                <img src="{{ asset('assets/index/patient21.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0005</p>
                                                <h6><a href="{{url('doctor-cancelled-appointment')}}">Robert</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>10 Oct 2024 11.30 AM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Chat</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-cancelled-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-cancelled-appointment')}}">
                                                <img src="{{ asset('assets/index/patient22.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0006</p>
                                                <h6><a href="{{url('doctor-cancelled-appointment')}}">Anderea</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>26 Sep 2024 10.20 AM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Chat</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-cancelled-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-cancelled-appointment')}}">
                                                <img src="{{ asset('assets/index/patient.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0007</p>
                                                <h6><a href="{{url('doctor-cancelled-appointment')}}">Peter</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>14 Sep 2024 08.10 AM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Chat</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-cancelled-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-cancelled-appointment')}}">
                                                <img src="{{ asset('assets/index/patient21.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0008</p>
                                                <h6><a href="{{url('doctor-cancelled-appointment')}}">Emily</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>03 Sep 2024 06.00 PM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Video Call</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-cancelled-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Pagination -->
                            <div class="pagination dashboard-pagination">
                                <ul>
                                    <li>
                                        <a href="#" class="page-link"><i class="fa-solid fa-chevron-left"></i></a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link">1</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link active">2</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link">3</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link">4</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link">...</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link"><i class="fa-solid fa-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Pagination -->
                        </div>
                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-completed-appointment')}}">
                                                <img src="{{ asset('assets/index/patient.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0001</p>
                                                <h6><a href="{{url('doctor-completed-appointment')}}">Adrian</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>11 Nov 2024 10.45 AM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Video Call</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-completed-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-completed-appointment')}}">
                                                <img src="{{ asset('assets/index/patient21.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0002</p>
                                                <h6><a href="{{url('doctor-completed-appointment')}}">Kelly</a><span class="badge new-tag">New</span></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>05 Nov 2024 11.50 AM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Audio Call</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-completed-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-completed-appointment')}}">
                                                <img src="{{ asset('assets/index/patient22.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0003</p>
                                                <h6><a href="{{url('doctor-completed-appointment')}}">Samuel</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>27 Oct 2024 09.30 AM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Video Call</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-completed-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-completed-appointment')}}">
                                                <img src="{{ asset('assets/index/patient.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0004</p>
                                                <h6><a href="{{url('doctor-completed-appointment')}}">Catherine</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>18 Oct 2024 12.20 PM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Direct Visit</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-completed-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-completed-appointment')}}">
                                                <img src="{{ asset('assets/index/patient21.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0005</p>
                                                <h6><a href="{{url('doctor-completed-appointment')}}">Robert</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>10 Oct 2024 11.30 AM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Chat</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-completed-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-completed-appointment')}}">
                                                <img src="{{ asset('assets/index/patient22.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0006</p>
                                                <h6><a href="{{url('doctor-completed-appointment')}}">Anderea</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>26 Sep 2024 10.20 AM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Chat</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-completed-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-completed-appointment')}}">
                                                <img src="{{ asset('assets/index/patient.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0007</p>
                                                <h6><a href="{{url('doctor-completed-appointment')}}">Peter</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>14 Sep 2024 08.10 AM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Chat</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-completed-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Appointment List -->
                            <div class="appointment-wrap">
                                <ul>
                                    <li>
                                        <div class="patinet-information">
                                            <a href="{{url('doctor-completed-appointment')}}">
                                                <img src="{{ asset('assets/index/patient21.jpg') }}" alt="User Image">
                                            </a>
                                            <div class="patient-info">
                                                <p>#Apt0008</p>
                                                <h6><a href="{{url('doctor-completed-appointment')}}">Emily</a></h6>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="appointment-info">
                                        <p><i class="fa-solid fa-clock"></i>03 Sep 2024 06.00 PM</p>
                                        <ul class="d-flex apponitment-types">
                                            <li>General Visit</li>
                                            <li>Video Call</li>
                                        </ul>
                                        
                                    </li>
                                    <li class="appointment-detail-btn">
                                        <a href="{{url('doctor-completed-appointment')}}" class="start-link">View Details</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Appointment List -->

                            <!-- Pagination -->
                            <div class="pagination dashboard-pagination">
                                <ul>
                                    <li>
                                        <a href="#" class="page-link"><i class="fa-solid fa-chevron-left"></i></a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link">1</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link active">2</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link">3</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link">4</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link">...</a>
                                    </li>
                                    <li>
                                        <a href="#" class="page-link"><i class="fa-solid fa-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Pagination -->
                        </div>
                    </div> -->
                </div>
            </div>

        </div>

    </div>		
    <!-- /Page Content -->
@endsection
