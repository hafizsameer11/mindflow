<?php $page = 'patient-notifications'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            Dashboard
        @endslot
        @slot('li_2')
            Notifications
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
                    <div class="dashboard-card w-100">
                        <div class="dashboard-card-head">
                            <div class="header-title">
                                <h5>
                                    <i class="fa-solid fa-bell me-2 text-primary"></i>
                                    Notifications
                                    @if($unreadCount > 0)
                                        <span class="badge bg-danger ms-2">{{ $unreadCount }} unread</span>
                                    @endif
                                </h5>
                                <p class="text-muted mb-0 small">Stay updated with important announcements and updates from the platform.</p>
                            </div>
                            @if($unreadCount > 0)
                            <div class="card-view-link">
                                <a href="javascript:void(0)" onclick="markAllAsRead()">Mark All as Read</a>
                            </div>
                            @endif
                        </div>
                        <div class="dashboard-card-body">
                            @if($notifications->count() > 0)
                            <div class="table-responsive">
                                <table class="table dashboard-table">
                                    <tbody>
                                        @foreach($notifications as $notification)
                                            @php
                                                $data = $notification->data;
                                                $isRead = !is_null($notification->read_at);
                                            @endphp
                                            <tr class="{{ !$isRead ? 'table-active' : '' }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <i class="fa-solid fa-bullhorn text-primary" style="font-size: 24px;"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">
                                                                {{ $data['title'] ?? 'Announcement' }}
                                                                @if(!$isRead)
                                                                    <span class="badge bg-danger badge-sm ms-2">New</span>
                                                                @endif
                                                                @if(isset($data['priority']) && in_array($data['priority'], ['urgent', 'high']))
                                                                    <span class="badge bg-{{ $data['priority'] == 'urgent' ? 'danger' : 'warning' }} badge-sm ms-1">{{ ucfirst($data['priority']) }}</span>
                                                                @endif
                                                            </h6>
                                                            <p class="text-muted mb-1">{{ $data['message'] ?? '' }}</p>
                                                            <small class="text-muted">{{ $notification->created_at->format('M d, Y h:i A') }} ({{ $notification->created_at->diffForHumans() }})</small>
                                                        </div>
                                                        @if(!$isRead)
                                                        <div class="ms-3">
                                                            <button class="btn btn-sm btn-outline-primary" onclick="markAsRead('{{ $notification->id }}')">Mark as Read</button>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($notifications->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $notifications->links() }}
                            </div>
                            @endif
                            @else
                            <div class="text-center py-5">
                                <i class="fa-solid fa-bell-slash text-muted" style="font-size: 48px;"></i>
                                <p class="text-muted mt-3 mb-0">No notifications yet</p>
                                <p class="text-muted small">You'll see important announcements and updates here when they're available.</p>
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

@push('scripts')
<script>
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endpush

