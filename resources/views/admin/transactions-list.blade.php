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

            <!-- Financial Statistics -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-primary">
                                    <i class="fe fe-dollar-sign"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>${{ number_format($stats['total_amount'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Total Revenue</h6>
                                <p class="text-muted mb-0">{{ $stats['total_payments'] ?? 0 }} payments</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-success">
                                    <i class="fe fe-check-circle"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>${{ number_format($stats['verified_amount'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Verified Payments</h6>
                                <p class="text-muted mb-0">{{ $stats['verified_count'] ?? 0 }} verified</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-warning">
                                    <i class="fe fe-clock"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>${{ number_format($stats['pending_amount'] ?? 0, 2) }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Pending Verification</h6>
                                <p class="text-muted mb-0">{{ $stats['pending_count'] ?? 0 }} pending</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-danger">
                                    <i class="fe fe-alert-circle"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['disputed_count'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Disputed Payments</h6>
                                <p class="text-muted mb-0">{{ $stats['refund_requested_count'] ?? 0 }} refund requests</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Financial Statistics -->

            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.payments.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" placeholder="Search by ID, transaction ID, patient, or psychologist..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="status">
                                        <option value="">All Payment Status</option>
                                        <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="dispute_status">
                                        <option value="">All Dispute Status</option>
                                        <option value="none" {{ request('dispute_status') == 'none' ? 'selected' : '' }}>No Dispute</option>
                                        <option value="disputed" {{ request('dispute_status') == 'disputed' ? 'selected' : '' }}>Disputed</option>
                                        <option value="resolved" {{ request('dispute_status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control" name="refund_status">
                                        <option value="">All Refund Status</option>
                                        <option value="none" {{ request('refund_status') == 'none' ? 'selected' : '' }}>No Refund</option>
                                        <option value="requested" {{ request('refund_status') == 'requested' ? 'selected' : '' }}>Requested</option>
                                        <option value="approved" {{ request('refund_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="processed" {{ request('refund_status') == 'processed' ? 'selected' : '' }}>Processed</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary w-100 mt-2">Clear</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Search and Filter -->

            @if(isset($stats['disputed_count']) && $stats['disputed_count'] > 0)
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <i class="fe fe-alert-triangle"></i> 
                        <strong>Dispute Alert:</strong> {{ $stats['disputed_count'] }} payment(s) have active disputes that need attention.
                    </div>
                </div>
            </div>
            @endif

            @if(isset($stats['refund_requested_count']) && $stats['refund_requested_count'] > 0)
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <i class="fe fe-info"></i> 
                        <strong>Refund Requests:</strong> {{ $stats['refund_requested_count'] }} refund request(s) are pending approval.
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Complete Transaction History</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Payment ID</th>
                                            <th>Patient Name</th>
                                            <th>Psychologist</th>
                                            <th>Amount</th>
                                            <th class="text-center">Payment Status</th>
                                            <th class="text-center">Dispute/Refund</th>
                                            <th>Date</th>
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
                                                        <img class="avatar-img rounded-circle" src="{{ $payment->appointment->patient->user->profile_image ? asset('storage/' . $payment->appointment->patient->user->profile_image) : asset('assets_admin/img/patients/patient.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $payment->appointment->patient->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ $payment->appointment->psychologist->user->profile_image ? asset('storage/' . $payment->appointment->psychologist->user->profile_image) : asset('assets_admin/img/doctors/doctor-thumb-01.jpg') }}" alt="User Image">
                                                    </a>
                                                    <a href="#">{{ $payment->appointment->psychologist->user->name }}</a>
                                                </h2>
                                            </td>
                                            <td>${{ number_format($payment->amount, 2) }}</td>
                                            <td class="text-center">
                                                @if($payment->status == 'verified')
                                                    <span class="badge bg-success">Verified</span>
                                                @elseif($payment->status == 'pending_verification')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($payment->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $payment->status)) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $disputeStatus = $payment->dispute_status ?? 'none';
                                                    $refundStatus = $payment->refund_status ?? 'none';
                                                    $hasDispute = $disputeStatus != 'none';
                                                    $hasRefund = $refundStatus != 'none';
                                                @endphp
                                                
                                                @if($hasDispute)
                                                    @if($disputeStatus == 'disputed')
                                                        <span class="badge bg-danger mb-1 d-block">Disputed</span>
                                                    @elseif($disputeStatus == 'resolved')
                                                        <span class="badge bg-info mb-1 d-block">Resolved</span>
                                                    @endif
                                                @endif
                                                
                                                @if($hasRefund)
                                                    @if($refundStatus == 'requested')
                                                        <span class="badge bg-warning d-block">Refund Requested</span>
                                                    @elseif($refundStatus == 'approved')
                                                        <span class="badge bg-info d-block">Refund Approved</span>
                                                    @elseif($refundStatus == 'processed')
                                                        <span class="badge bg-success d-block">Refund Processed</span>
                                                    @elseif($refundStatus == 'rejected')
                                                        <span class="badge bg-danger d-block">Refund Rejected</span>
                                                    @endif
                                                @endif
                                                
                                                @if(!$hasDispute && !$hasRefund)
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $payment->created_at->format('M d, Y') }}<br>
                                                <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-primary">View</a>
                                                @if($payment->status == 'pending_verification')
                                                <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Verify</button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No transactions found</td>
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
