<?php $page = 'payment-details'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Payment Details</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.transactions-list') }}">Transactions</a></li>
                            <li class="breadcrumb-item active">Payment Details</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Payment Information</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th>Payment ID:</th>
                                                <td>#PAY{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Amount:</th>
                                                <td><strong class="text-primary">${{ number_format($payment->amount, 2) }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Status:</th>
                                                <td>
                                                    @if($payment->status == 'verified')
                                                        <span class="badge bg-success">Verified</span>
                                                    @elseif($payment->status == 'pending_verification')
                                                        <span class="badge bg-warning">Pending Verification</span>
                                                    @elseif($payment->status == 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $payment->status)) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Bank Name:</th>
                                                <td>{{ $payment->bank_name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Transaction ID:</th>
                                                <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Uploaded At:</th>
                                                <td>{{ $payment->uploaded_at ? \Carbon\Carbon::parse($payment->uploaded_at)->format('M d, Y h:i A') : 'N/A' }}</td>
                                            </tr>
                                            @if($payment->verified_at)
                                            <tr>
                                                <th>Verified At:</th>
                                                <td>{{ \Carbon\Carbon::parse($payment->verified_at)->format('M d, Y h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Verified By:</th>
                                                <td>{{ $payment->verifier->name ?? 'N/A' }}</td>
                                            </tr>
                                            @endif
                                            @if($payment->rejection_reason)
                                            <tr>
                                                <th>Rejection Reason:</th>
                                                <td class="text-danger">{{ $payment->rejection_reason }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3">Appointment Information</h5>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th>Appointment ID:</th>
                                                <td>#APT{{ str_pad($payment->appointment->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Patient:</th>
                                                <td>
                                                    <h2 class="table-avatar mb-0">
                                                        <a href="#" class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle" src="{{ asset('assets_admin/img/patients/patient.jpg') }}" alt="User Image">
                                                        </a>
                                                        <a href="#">{{ $payment->appointment->patient->user->name }}</a>
                                                    </h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Psychologist:</th>
                                                <td>
                                                    <h2 class="table-avatar mb-0">
                                                        <a href="#" class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle" src="{{ asset('assets_admin/img/doctors/doctor-thumb-01.jpg') }}" alt="User Image">
                                                        </a>
                                                        <a href="#">{{ $payment->appointment->psychologist->user->name }}</a>
                                                    </h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Specialization:</th>
                                                <td>{{ $payment->appointment->psychologist->specialization }}</td>
                                            </tr>
                                            <tr>
                                                <th>Appointment Date:</th>
                                                <td>{{ $payment->appointment->appointment_date->format('M d, Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Appointment Time:</th>
                                                <td>{{ \Carbon\Carbon::parse($payment->appointment->appointment_time)->format('h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Consultation Type:</th>
                                                <td>{{ ucfirst($payment->appointment->consultation_type) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Appointment Status:</th>
                                                <td>
                                                    <span class="badge bg-{{ $payment->appointment->status === 'confirmed' ? 'success' : ($payment->appointment->status === 'completed' ? 'info' : ($payment->appointment->status === 'cancelled' ? 'danger' : 'warning')) }}-light">
                                                        {{ ucfirst($payment->appointment->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if($payment->receipt_file_path)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3">Receipt</h5>
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <p class="mb-3">Payment Receipt</p>
                                            <a href="{{ route('admin.payments.receipt', $payment->id) }}" class="btn btn-primary" target="_blank">
                                                <i class="fe fe-download"></i> Download Receipt
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Dispute Information -->
                            @if($payment->dispute_status != 'none')
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3">Dispute Information</h5>
                                    <div class="card {{ $payment->dispute_status == 'disputed' ? 'border-warning' : 'border-info' }}">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th>Dispute Status:</th>
                                                                <td>
                                                                    @if($payment->dispute_status == 'disputed')
                                                                        <span class="badge bg-warning">Disputed</span>
                                                                    @else
                                                                        <span class="badge bg-info">Resolved</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Dispute Reason:</th>
                                                                <td>{{ $payment->dispute_reason }}</td>
                                                            </tr>
                                                            @if($payment->disputed_at)
                                                            <tr>
                                                                <th>Disputed At:</th>
                                                                <td>{{ $payment->disputed_at->format('M d, Y h:i A') }}</td>
                                                            </tr>
                                                            @endif
                                                            @if($payment->disputer)
                                                            <tr>
                                                                <th>Disputed By:</th>
                                                                <td>{{ $payment->disputer->name }}</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @if($payment->dispute_status == 'resolved')
                                                <div class="col-md-6">
                                                    <table class="table table-borderless mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th>Resolution:</th>
                                                                <td>{{ $payment->dispute_resolution }}</td>
                                                            </tr>
                                                            @if($payment->dispute_resolved_at)
                                                            <tr>
                                                                <th>Resolved At:</th>
                                                                <td>{{ $payment->dispute_resolved_at->format('M d, Y h:i A') }}</td>
                                                            </tr>
                                                            @endif
                                                            @if($payment->disputeResolver)
                                                            <tr>
                                                                <th>Resolved By:</th>
                                                                <td>{{ $payment->disputeResolver->name }}</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @endif
                                            </div>
                                            @if($payment->dispute_status == 'disputed')
                                            <div class="mt-3">
                                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#resolveDisputeModal">
                                                    <i class="fe fe-check-circle"></i> Resolve Dispute
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Refund Information -->
                            @if($payment->refund_status != 'none')
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="mb-3">Refund Information</h5>
                                    <div class="card {{ $payment->refund_status == 'requested' ? 'border-warning' : ($payment->refund_status == 'processed' ? 'border-success' : 'border-info') }}">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th>Refund Status:</th>
                                                                <td>
                                                                    @if($payment->refund_status == 'requested')
                                                                        <span class="badge bg-warning">Requested</span>
                                                                    @elseif($payment->refund_status == 'approved')
                                                                        <span class="badge bg-info">Approved</span>
                                                                    @elseif($payment->refund_status == 'processed')
                                                                        <span class="badge bg-success">Processed</span>
                                                                    @elseif($payment->refund_status == 'rejected')
                                                                        <span class="badge bg-danger">Rejected</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Refund Amount:</th>
                                                                <td><strong class="text-primary">${{ number_format($payment->refund_amount ?? 0, 2) }}</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Refund Reason:</th>
                                                                <td>{{ $payment->refund_reason }}</td>
                                                            </tr>
                                                            @if($payment->refund_requested_at)
                                                            <tr>
                                                                <th>Requested At:</th>
                                                                <td>{{ $payment->refund_requested_at->format('M d, Y h:i A') }}</td>
                                                            </tr>
                                                            @endif
                                                            @if($payment->refundRequester)
                                                            <tr>
                                                                <th>Requested By:</th>
                                                                <td>{{ $payment->refundRequester->name }}</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @if($payment->refund_status == 'processed' || $payment->refund_status == 'rejected')
                                                <div class="col-md-6">
                                                    <table class="table table-borderless mb-0">
                                                        <tbody>
                                                            @if($payment->refund_notes)
                                                            <tr>
                                                                <th>Notes:</th>
                                                                <td>{{ $payment->refund_notes }}</td>
                                                            </tr>
                                                            @endif
                                                            @if($payment->refund_processed_at)
                                                            <tr>
                                                                <th>Processed At:</th>
                                                                <td>{{ $payment->refund_processed_at->format('M d, Y h:i A') }}</td>
                                                            </tr>
                                                            @endif
                                                            @if($payment->refundProcessor)
                                                            <tr>
                                                                <th>Processed By:</th>
                                                                <td>{{ $payment->refundProcessor->name }}</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @endif
                                            </div>
                                            @if($payment->refund_status == 'requested')
                                            <div class="mt-3">
                                                <form action="{{ route('admin.payments.approve-refund', $payment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to approve this refund?')">
                                                        <i class="fe fe-check"></i> Approve Refund
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectRefundModal">
                                                    <i class="fe fe-x"></i> Reject Refund
                                                </button>
                                            </div>
                                            @elseif($payment->refund_status == 'approved')
                                            <div class="mt-3">
                                                <form action="{{ route('admin.payments.process-refund', $payment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Mark this refund as processed?')">
                                                        <i class="fe fe-check-circle"></i> Mark as Processed
                                                    </button>
                                                </form>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2 flex-wrap">
                                        @if($payment->status == 'pending_verification')
                                        <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to verify this payment?')">
                                                <i class="fe fe-check"></i> Verify Payment
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                            <i class="fe fe-x"></i> Reject Payment
                                        </button>
                                        @endif
                                        
                                        @if($payment->dispute_status == 'none' && $payment->status == 'verified')
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#disputeModal">
                                            <i class="fe fe-alert-circle"></i> Record Dispute
                                        </button>
                                        @endif
                                        
                                        @if($payment->refund_status == 'none' && $payment->status == 'verified')
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#refundModal">
                                            <i class="fe fe-dollar-sign"></i> Request Refund
                                        </button>
                                        @endif
                                        
                                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                                            <i class="fe fe-arrow-left"></i> Back to Transactions
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->

    <!-- Reject Payment Modal -->
    @if($payment->status == 'pending_verification')
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Reject Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required placeholder="Please provide a reason for rejecting this payment..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    <!-- /Reject Payment Modal -->

    <!-- Dispute Modal -->
    @if($payment->dispute_status == 'none' && $payment->status == 'verified')
    <div class="modal fade" id="disputeModal" tabindex="-1" aria-labelledby="disputeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.payments.dispute', $payment->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="disputeModalLabel">Record Payment Dispute</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fe fe-alert-triangle"></i> Record a dispute for this payment. This will flag the payment for review.
                        </div>
                        <div class="mb-3">
                            <label for="dispute_reason" class="form-label">Dispute Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="dispute_reason" name="dispute_reason" rows="4" required placeholder="Please provide details about the dispute..."></textarea>
                            <small class="text-muted">Minimum 10 characters required.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Record Dispute</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    <!-- /Dispute Modal -->

    <!-- Resolve Dispute Modal -->
    @if($payment->dispute_status == 'disputed')
    <div class="modal fade" id="resolveDisputeModal" tabindex="-1" aria-labelledby="resolveDisputeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.payments.resolve-dispute', $payment->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="resolveDisputeModalLabel">Resolve Dispute</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Original Dispute:</label>
                            <div class="alert alert-light">{{ $payment->dispute_reason }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="dispute_resolution" class="form-label">Resolution Details <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="dispute_resolution" name="dispute_resolution" rows="4" required placeholder="Please provide details about how the dispute was resolved..."></textarea>
                            <small class="text-muted">Minimum 10 characters required.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Resolve Dispute</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    <!-- /Resolve Dispute Modal -->

    <!-- Refund Request Modal -->
    @if($payment->refund_status == 'none' && $payment->status == 'verified')
    <div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.payments.request-refund', $payment->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="refundModalLabel">Request Refund</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fe fe-info"></i> Request a refund for this payment. The refund will need to be approved before processing.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Original Payment Amount:</label>
                            <div class="form-control-plaintext"><strong>${{ number_format($payment->amount, 2) }}</strong></div>
                        </div>
                        <div class="mb-3">
                            <label for="refund_amount" class="form-label">Refund Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="refund_amount" name="refund_amount" step="0.01" min="0" max="{{ $payment->amount }}" value="{{ $payment->amount }}" required>
                            <small class="text-muted">Maximum refund amount: ${{ number_format($payment->amount, 2) }}</small>
                        </div>
                        <div class="mb-3">
                            <label for="refund_reason" class="form-label">Refund Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="refund_reason" name="refund_reason" rows="4" required placeholder="Please provide a reason for the refund request..."></textarea>
                            <small class="text-muted">Minimum 10 characters required.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Request Refund</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    <!-- /Refund Request Modal -->

    <!-- Reject Refund Modal -->
    @if($payment->refund_status == 'requested')
    <div class="modal fade" id="rejectRefundModal" tabindex="-1" aria-labelledby="rejectRefundModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.payments.reject-refund', $payment->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectRefundModalLabel">Reject Refund Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="refund_notes" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="refund_notes" name="refund_notes" rows="4" required placeholder="Please provide a reason for rejecting this refund request..."></textarea>
                            <small class="text-muted">Minimum 10 characters required.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Refund</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    <!-- /Reject Refund Modal -->
@endsection

