<?php $page = 'patient-list'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">List of Patient</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript:(0);">Users</a></li>
                            <li class="breadcrumb-item active">Patient</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Patient ID</th>
                                            <th>Patient Name</th>
                                            <th>Age</th>
                                            <th>Address</th>
                                            <th>Phone</th>
                                            <th>Last Visit</th>
                                            <th class="text-end">Total Paid</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($patients ?? [] as $patient)
                                        <tr>
                                            <td>#PAT{{ str_pad($patient->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $patient->user->profile_image ? asset('storage/' . $patient->user->profile_image) : asset('assets_admin/img/patients/patient.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $patient->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>
                                                @if($patient->user->date_of_birth)
                                                    {{ \Carbon\Carbon::parse($patient->user->date_of_birth)->age }} Years
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $patient->user->address ?? 'N/A' }}</td>
                                            <td>{{ $patient->user->phone ?? 'N/A' }}</td>
                                            <td>
                                                {{ $patient->last_visit_date ? $patient->last_visit_date->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="text-end">
                                                ${{ number_format($patient->total_paid ?? 0, 2) }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No patients found</td>
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
    <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->
@endsection
