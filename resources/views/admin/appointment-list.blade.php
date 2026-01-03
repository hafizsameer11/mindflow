<?php $page = 'appointment-list'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Appointments</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Appointments</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Recent Orders -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Psychologist Name</th>
                                            <th>Speciality</th>
                                            <th>Patient Name</th>
                                            <th>Appointment Time</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($appointments ?? [] as $appointment)
                                        <tr>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{URL::asset('/assets_admin/img/doctors/doctor-thumb-01.jpg')}}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $appointment->psychologist->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $appointment->psychologist->specialization }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{URL::asset('/assets_admin/img/patients/patient.jpg')}}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $appointment->patient->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>
                                                {{ $appointment->appointment_date->format('M d, Y') }}<br>
                                                <small>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                @if($appointment->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($appointment->status == 'confirmed')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @elseif($appointment->status == 'completed')
                                                    <span class="badge bg-info">Completed</span>
                                                @else
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>
                                                ${{ number_format($appointment->psychologist->consultation_fee, 2) }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-sm btn-primary">View</a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No appointments found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                @if(isset($appointments) && $appointments->hasPages())
                                <div class="mt-3">
                                    {{ $appointments->links() }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /Recent Orders -->

                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->
@endsection
