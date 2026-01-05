<?php $page = 'medical-details'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            Medical Details
        @endslot
        @slot('li_2')
            Vitals
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
                        <h3>Vitals</h3>
                        <p class="text-muted">Track and manage your health vitals including BMI, heart rate, blood pressure, and more.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Add Vitals Button -->
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#add-med-record">
                                <i class="fa-solid fa-plus me-2"></i>Add Vitals
                            </button>
                        </div>
                    </div>

                    <!-- Vitals Display Cards -->
                    <div class="row">
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="health-records icon-red">
                                        <span><i class="fa-solid fa-syringe"></i>Blood Pressure</span>
                                        <h3>{{ $latestVital && $latestVital->blood_pressure ? $latestVital->blood_pressure : '—' }}</h3>
                                        <p class="text-muted small mb-0">{{ $latestVital && $latestVital->blood_pressure ? 'Last recorded: ' . $latestVital->recorded_date->format('M d, Y') : 'Not recorded' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="health-records icon-orange">
                                        <span><i class="fa-solid fa-heart"></i>Heart Rate</span>
                                        <h3>{{ $latestVital && $latestVital->heart_rate ? $latestVital->heart_rate : '—' }}</h3>
                                        <p class="text-muted small mb-0">{{ $latestVital && $latestVital->heart_rate ? 'Last recorded: ' . $latestVital->recorded_date->format('M d, Y') : 'Not recorded' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="health-records icon-dark-blue">
                                        <span><i class="fa-solid fa-notes-medical"></i>Glucose Level</span>
                                        <h3>{{ $latestVital && $latestVital->glucose_level ? $latestVital->glucose_level : '—' }}</h3>
                                        <p class="text-muted small mb-0">{{ $latestVital && $latestVital->glucose_level ? 'Last recorded: ' . $latestVital->recorded_date->format('M d, Y') : 'Not recorded' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="health-records icon-amber">
                                        <span><i class="fa-solid fa-temperature-high"></i>Body Temperature</span>
                                        <h3>{{ $latestVital && $latestVital->body_temperature ? $latestVital->body_temperature : '—' }}</h3>
                                        <p class="text-muted small mb-0">{{ $latestVital && $latestVital->body_temperature ? 'Last recorded: ' . $latestVital->recorded_date->format('M d, Y') : 'Not recorded' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="health-records icon-purple">
                                        <span><i class="fa-solid fa-user-pen"></i>BMI</span>
                                        <h3>{{ $latestVital && $latestVital->bmi ? $latestVital->bmi : '—' }}</h3>
                                        <p class="text-muted small mb-0">{{ $latestVital && $latestVital->bmi ? 'Last recorded: ' . $latestVital->recorded_date->format('M d, Y') : 'Not recorded' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="health-records icon-green">
                                        <span><i class="fa-solid fa-weight"></i>Weight</span>
                                        <h3>{{ $latestVital && $latestVital->weight ? $latestVital->weight : '—' }}</h3>
                                        <p class="text-muted small mb-0">{{ $latestVital && $latestVital->weight ? 'Last recorded: ' . $latestVital->recorded_date->format('M d, Y') : 'Not recorded' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vitals History -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-clock-rotate-left me-2"></i>Vitals History
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($vitals && $vitals->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>BMI</th>
                                                <th>Heart Rate</th>
                                                <th>Weight</th>
                                                <th>Blood Pressure</th>
                                                <th>Glucose</th>
                                                <th>Temperature</th>
                                                <th>FBC</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($vitals as $vital)
                                            <tr>
                                                <td>{{ $vital->recorded_date->format('M d, Y') }}</td>
                                                <td>{{ $vital->bmi ?? '—' }}</td>
                                                <td>{{ $vital->heart_rate ?? '—' }}</td>
                                                <td>{{ $vital->weight ?? '—' }}</td>
                                                <td>{{ $vital->blood_pressure ?? '—' }}</td>
                                                <td>{{ $vital->glucose_level ?? '—' }}</td>
                                                <td>{{ $vital->body_temperature ?? '—' }}</td>
                                                <td>{{ $vital->fbc ?? '—' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-clipboard-list fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No vitals recorded yet. Click "Add Vitals" to start tracking your health metrics.</p>
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
