<?php $page = 'transactions-list'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Transactions</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Transactions</li>
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
                                            <th>Payment ID</th>
                                            <th>Patient Name</th>
                                            <th>Psychologist</th>
                                            <th>Amount</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments ?? [] as $payment)
                                        <tr>
                                            <td>#PAY{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{URL::asset('/assets_admin/img/patients/patient.jpg')}}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $payment->appointment->patient->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $payment->appointment->psychologist->user->name }}</td>
                                            <td>${{ number_format($payment->amount, 2) }}</td>
                                            <td class="text-center">
                                                @if($payment->status == 'verified')
                                                    <span class="badge bg-success">Verified</span>
                                                @elseif($payment->status == 'pending')
                                                    <span class="badge bg-warning">Pending Verification</span>
                                                @elseif($payment->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-primary">View</a>
                                                @if($payment->status == 'pending')
                                                <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Verify</button>
                                                </form>
                                                @endif
                                                @if($payment->receipt_file_path)
                                                <a href="{{ route('admin.payments.download', $payment->id) }}" class="btn btn-sm btn-info">Download Receipt</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No transactions found</td>
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

    <!-- Delete Modal -->
    <div class="modal fade" id="delete_modal" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!--	<div class="modal-header">
                   <h5 class="modal-title">Delete</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                   </button>
                  </div>-->
                <div class="modal-body">
                    <div class="form-content p-2">
                        <h4 class="modal-title">Delete</h4>
                        <p class="mb-4">Are you sure want to delete?</p>
                        <button type="button" class="btn btn-primary">Save </button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Modal -->

    </div>
    <!-- /Main Wrapper -->
@endsection
