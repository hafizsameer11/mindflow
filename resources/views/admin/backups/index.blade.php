<?php $page = 'backups'; ?>
@extends('layout.mainlayout_admin')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Data Security and Backup</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/index_admin') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Backups</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Statistics -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-primary">
                                    <i class="fe fe-database"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['total_backups'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Total Backups</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-success">
                                    <i class="fe fe-hard-drive"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ number_format(($stats['total_size'] ?? 0) / 1024 / 1024, 2) }} MB</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Total Size</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon text-info">
                                    <i class="fe fe-lock"></i>
                                </span>
                                <div class="dash-count">
                                    <h3>{{ $stats['encrypted_backups'] ?? 0 }}</h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Encrypted Backups</h6>
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
                                    <h3>
                                        @if(isset($stats['latest_backup']))
                                            {{ \Carbon\Carbon::parse($stats['latest_backup']['created_at'])->diffForHumans() }}
                                        @else
                                            Never
                                        @endif
                                    </h3>
                                </div>
                            </div>
                            <div class="dash-widget-info">
                                <h6 class="text-muted">Last Backup</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Statistics -->

            <!-- Create Backup Section -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Create New Backup</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.backups.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="encrypt" id="encrypt" value="1">
                                            <label class="form-check-label" for="encrypt">
                                                <strong>Encrypt Backup</strong> (Recommended for security)
                                            </label>
                                            <small class="form-text text-muted d-block">
                                                Encrypted backups are secured with AES-256 encryption for maximum data protection.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fe fe-database"></i> Create Backup Now
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Create Backup Section -->

            <!-- Backups List -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Backup History</h5>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            @if(session('error') || $errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') ?? $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Backup File</th>
                                            <th>Database</th>
                                            <th>Size</th>
                                            <th>Encrypted</th>
                                            <th>Created At</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($backups ?? [] as $backup)
                                        <tr>
                                            <td>
                                                <strong>{{ $backup['filename'] }}</strong>
                                            </td>
                                            <td>{{ $backup['database'] ?? 'N/A' }}</td>
                                            <td>{{ number_format($backup['size'] / 1024 / 1024, 2) }} MB</td>
                                            <td>
                                                @if($backup['encrypted'] ?? false)
                                                    <span class="badge bg-success">
                                                        <i class="fe fe-lock"></i> Encrypted
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fe fe-unlock"></i> Not Encrypted
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($backup['created_at'])->format('M d, Y h:i A') }}
                                                <br>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($backup['created_at'])->diffForHumans() }}</small>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.backups.download', $backup['filename']) }}" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Download">
                                                        <i class="fe fe-download"></i> Download
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-success" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#restoreModal{{ $loop->index }}"
                                                            title="Restore">
                                                        <i class="fe fe-refresh-cw"></i> Restore
                                                    </button>
                                                    <form action="{{ route('admin.backups.destroy', $backup['filename']) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this backup? This action cannot be undone.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fe fe-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Restore Modal -->
                                        <div class="modal fade" id="restoreModal{{ $loop->index }}" tabindex="-1" aria-labelledby="restoreModalLabel{{ $loop->index }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.backups.restore', $backup['filename']) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="restoreModalLabel{{ $loop->index }}">Restore Database</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="alert alert-warning">
                                                                <strong>Warning!</strong> This will replace all current database data with the backup data. This action cannot be undone.
                                                            </div>
                                                            <p><strong>Backup File:</strong> {{ $backup['filename'] }}</p>
                                                            <p><strong>Created:</strong> {{ \Carbon\Carbon::parse($backup['created_at'])->format('M d, Y h:i A') }}</p>
                                                            <p><strong>Size:</strong> {{ number_format($backup['size'] / 1024 / 1024, 2) }} MB</p>
                                                            @if($backup['encrypted'] ?? false)
                                                            <p><strong>Encrypted:</strong> <span class="badge bg-success">Yes</span></p>
                                                            @endif
                                                            <div class="form-check mt-3">
                                                                <input class="form-check-input" type="checkbox" name="confirm" id="confirm{{ $loop->index }}" value="1" required>
                                                                <label class="form-check-label" for="confirm{{ $loop->index }}">
                                                                    I understand that this will replace all current database data
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fe fe-refresh-cw"></i> Restore Database
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Restore Modal -->
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <div class="py-4">
                                                    <i class="fe fe-database" style="font-size: 48px; color: #ccc;"></i>
                                                    <p class="mt-3 text-muted">No backups found. Create your first backup to get started.</p>
                                                </div>
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
            <!-- /Backups List -->

            <!-- Information Card -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fe fe-info"></i> Backup Information</h5>
                            <ul class="mb-0">
                                <li><strong>Backup Location:</strong> <code>storage/app/backups/</code></li>
                                <li><strong>Encryption:</strong> AES-256-CBC encryption for secure backups</li>
                                <li><strong>Recovery:</strong> Quick database restoration in case of system failure</li>
                                <li><strong>Recommendation:</strong> Create encrypted backups regularly (daily/weekly) and store copies off-site</li>
                                <li><strong>Automated Backups:</strong> Set up scheduled backups using Laravel's task scheduler (see documentation)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Information Card -->
        </div>
    </div>
    <!-- /Page Wrapper -->
@endsection

