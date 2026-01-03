<?php $page = 'doctor-list'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">List of Doctors</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript:(0);">Users</a></li>
                            <li class="breadcrumb-item active">Doctor</li>
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
                                            <th>Doctor Name</th>
                                            <th>Speciality</th>
                                            <th>Member Since</th>
                                            <th>Earned</th>
                                            <th>Verification Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($psychologists ?? [] as $psychologist)
                                        <tr>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{URL::asset('/assets_admin/img/doctors/doctor-thumb-01.jpg')}}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $psychologist->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $psychologist->specialization }}</td>
                                            <td>{{ $psychologist->user->created_at->format('M d, Y') }}</td>
                                            <td>${{ number_format(\App\Models\Payment::whereHas('appointment', function($q) use ($psychologist) {
                                                $q->where('psychologist_id', $psychologist->id);
                                            })->where('status', 'verified')->sum('amount'), 2) }}</td>
                                            <td>
                                                @if($psychologist->verification_status == 'verified')
                                                    <span class="badge bg-success">Verified</span>
                                                @elseif($psychologist->verification_status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($psychologist->verification_status == 'pending')
                                                <form action="{{ route('admin.psychologists.verify', $psychologist->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Verify</button>
                                                </form>
                                                <form action="{{ route('admin.psychologists.reject', $psychologist->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No psychologists found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                @if(isset($psychologists) && $psychologists->hasPages())
                                <div class="mt-3">
                                    {{ $psychologists->links() }}
                                </div>
                                @endif
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
