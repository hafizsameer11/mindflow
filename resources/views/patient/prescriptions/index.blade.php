<?php $page = 'prescriptions'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            Prescriptions
        @endslot
        @slot('li_2')
            My Prescriptions
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
                        <h3>My Prescriptions</h3>
                        <p class="text-muted">View all prescriptions and therapy notes assigned by your psychologists.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Prescriptions List -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Prescriptions & Therapy Notes</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Psychologist</th>
                                            <th>Appointment</th>
                                            <th>Type</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($prescriptions ?? [] as $prescription)
                                        <tr>
                                            <td>
                                                {{ $prescription->created_at->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $prescription->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $prescription->appointment->psychologist->user->profile_image ? asset('storage/' . $prescription->appointment->psychologist->user->profile_image) : asset('assets/img/doctors/doctor-thumb-01.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $prescription->appointment->psychologist->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>
                                                <span>#APT{{ str_pad($prescription->appointment->id, 4, '0', STR_PAD_LEFT) }}</span><br>
                                                <small class="text-muted">{{ $prescription->appointment->appointment_date->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                @if($prescription->therapy_plan)
                                                    <span class="badge bg-info">Therapy Plan</span>
                                                @endif
                                                @if($prescription->notes)
                                                    <span class="badge bg-primary">Notes</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('patient.prescriptions.show', $prescription) }}" class="btn btn-sm btn-primary">
                                                    <i class="fa-solid fa-eye me-1"></i>View Details
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fa-solid fa-prescription-bottle text-muted" style="font-size: 48px;"></i>
                                                <p class="text-muted mt-3 mb-0">No prescriptions found</p>
                                                <p class="text-muted small">Prescriptions will appear here after your appointments are completed.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->
@endsection

