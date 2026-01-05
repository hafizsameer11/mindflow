<?php $page = 'reports'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Report Generation</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Reports</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <!-- Appointment Reports -->
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fe fe-calendar" style="font-size: 48px; color: #007bff;"></i>
                            </div>
                            <h5 class="card-title">Appointment Reports</h5>
                            <p class="text-muted">Generate detailed reports on appointments with trend analysis and performance metrics.</p>
                            <a href="{{ route('admin.reports.appointments') }}" class="btn btn-primary">
                                <i class="fe fe-file-text"></i> View Report
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Payment Reports -->
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fe fe-dollar-sign" style="font-size: 48px; color: #28a745;"></i>
                            </div>
                            <h5 class="card-title">Payment Reports</h5>
                            <p class="text-muted">Financial reports with revenue analysis, daily trends, and payment statistics.</p>
                            <a href="{{ route('admin.reports.payments') }}" class="btn btn-success">
                                <i class="fe fe-file-text"></i> View Report
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Activity Reports -->
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fe fe-users" style="font-size: 48px; color: #ffc107;"></i>
                            </div>
                            <h5 class="card-title">User Activity Reports</h5>
                            <p class="text-muted">Track user registrations, activity patterns, and engagement metrics.</p>
                            <a href="{{ route('admin.reports.users') }}" class="btn btn-warning">
                                <i class="fe fe-file-text"></i> View Report
                            </a>
                        </div>
                    </div>
                </div>

                <!-- System Trends -->
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fe fe-trending-up" style="font-size: 48px; color: #dc3545;"></i>
                            </div>
                            <h5 class="card-title">System Trends</h5>
                            <p class="text-muted">Analyze system trends, growth rates, and key insights for decision making.</p>
                            <a href="{{ route('admin.reports.trends') }}" class="btn btn-danger">
                                <i class="fe fe-bar-chart"></i> View Trends
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Invoice Report -->
                <div class="col-md-6 col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fe fe-file" style="font-size: 48px; color: #17a2b8;"></i>
                            </div>
                            <h5 class="card-title">Invoice Report</h5>
                            <p class="text-muted">View all invoices and payment records with filtering options.</p>
                            <a href="{{ route('admin.invoice-report') }}" class="btn btn-info">
                                <i class="fe fe-file-text"></i> View Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Report Options -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Quick Report Periods</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($dateRanges ?? [] as $key => $range)
                                <div class="col-md-3 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ ucfirst(str_replace('_', ' ', $key)) }}</h6>
                                            <p class="text-muted mb-2">
                                                {{ $range['from']->format('M d, Y') }} - {{ $range['to']->format('M d, Y') }}
                                            </p>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.reports.appointments', ['date_from' => $range['from']->format('Y-m-d'), 'date_to' => $range['to']->format('Y-m-d')]) }}" class="btn btn-primary btn-sm">Appointments</a>
                                                <a href="{{ route('admin.reports.payments', ['date_from' => $range['from']->format('Y-m-d'), 'date_to' => $range['to']->format('Y-m-d')]) }}" class="btn btn-success btn-sm">Payments</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->
@endsection

