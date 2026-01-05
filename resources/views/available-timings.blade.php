<?php $page = 'available-timings'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Psychologist
        @endslot
        @slot('li_1')
            Availability Scheduling
        @endslot
        @slot('li_2')
            Manage Schedule
        @endslot
    @endcomponent
   
  <!-- Page Content -->
  <div class="content doctor-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-3 theiaStickySidebar">
                @component('components.sidebar_doctor')
                @endcomponent
            </div>
            
            <div class="col-lg-8 col-xl-9">
                <div class="dashboard-header d-flex justify-content-between align-items-center">
                    <h3>Availability Scheduling</h3>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#quickActionsModal">
                            <i class="fa-solid fa-bolt me-2"></i>Quick Actions
                        </button>
                    </div>
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

                @php
                    $days = [
                        0 => ['name' => 'Sunday', 'short' => 'Sun'],
                        1 => ['name' => 'Monday', 'short' => 'Mon'],
                        2 => ['name' => 'Tuesday', 'short' => 'Tue'],
                        3 => ['name' => 'Wednesday', 'short' => 'Wed'],
                        4 => ['name' => 'Thursday', 'short' => 'Thu'],
                        5 => ['name' => 'Friday', 'short' => 'Fri'],
                        6 => ['name' => 'Saturday', 'short' => 'Sat']
                    ];
                @endphp

                <!-- Weekly Schedule View -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @foreach($days as $dayNum => $dayInfo)
                                @php
                                    $dayAvailabilities = isset($availabilities) ? $availabilities->get($dayNum, collect()) : collect();
                                @endphp
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $dayInfo['name'] }}</h6>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSlotModal{{ $dayNum }}" title="Add Slot">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#bulkAddModal{{ $dayNum }}" title="Bulk Add">
                                                    <i class="fa-solid fa-layer-group"></i>
                                                </button>
                                                @if($dayAvailabilities->count() > 0)
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteDay({{ $dayNum }})" title="Delete All">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card-body" style="min-height: 200px; max-height: 300px; overflow-y: auto;">
                                            @if($dayAvailabilities->count() > 0)
                                                <div class="list-group list-group-flush">
                                                    @foreach($dayAvailabilities as $availability)
                                                        <div class="list-group-item d-flex justify-content-between align-items-center p-2">
                                                            <div>
                                                                <small class="d-block">
                                                                    <i class="fa-solid fa-clock me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($availability->start_time)->format('h:i A') }} - 
                                                                    {{ \Carbon\Carbon::parse($availability->end_time)->format('h:i A') }}
                                                                </small>
                                                                @if($availability->is_available)
                                                                    <span class="badge bg-success badge-sm">Available</span>
                                                                @else
                                                                    <span class="badge bg-danger badge-sm">Unavailable</span>
                                                                @endif
                                                            </div>
                                                            <div class="btn-group btn-group-sm">
                                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSlotModal{{ $availability->id }}" title="Edit">
                                                                    <i class="fa-solid fa-edit"></i>
                                                                </button>
                                                                <form action="{{ route('psychologist.availability.destroy', $availability) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this time slot?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                                        <i class="fa-solid fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        <!-- Edit Slot Modal -->
                                                        <div class="modal fade" id="editSlotModal{{ $availability->id }}" tabindex="-1">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <form action="{{ route('psychologist.availability.update', $availability) }}" method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Edit Time Slot - {{ $dayInfo['name'] }}</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label">Start Time</label>
                                                                                <input type="time" name="start_time" class="form-control" value="{{ $availability->start_time }}" required>
                                                                            </div>
                                                                            <div class="form-group mb-3">
                                                                                <label class="form-label">End Time</label>
                                                                                <input type="time" name="end_time" class="form-control" value="{{ $availability->end_time }}" required>
                                                                            </div>
                                                                            <div class="form-group mb-3">
                                                                                <div class="form-check">
                                                                                    <input type="checkbox" name="is_available" class="form-check-input" id="is_available_edit{{ $availability->id }}" value="1" {{ $availability->is_available ? 'checked' : '' }}>
                                                                                    <label class="form-check-label" for="is_available_edit{{ $availability->id }}">
                                                                                        Available for online sessions
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center text-muted py-4">
                                                    <i class="fa-solid fa-calendar-xmark fa-2x mb-2"></i>
                                                    <p class="mb-0">No time slots</p>
                                                    <small>Click + to add slots</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Add Single Slot Modal -->
                                    <div class="modal fade" id="addSlotModal{{ $dayNum }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('psychologist.availability.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="day_of_week" value="{{ $dayNum }}">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Add Time Slot - {{ $dayInfo['name'] }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Start Time</label>
                                                            <input type="time" name="start_time" class="form-control" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">End Time</label>
                                                            <input type="time" name="end_time" class="form-control" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <div class="form-check">
                                                                <input type="checkbox" name="is_available" class="form-check-input" id="is_available_new{{ $dayNum }}" value="1" checked>
                                                                <label class="form-check-label" for="is_available_new{{ $dayNum }}">
                                                                    Available for online sessions
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Add Slot</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bulk Add Modal -->
                                    <div class="modal fade" id="bulkAddModal{{ $dayNum }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="{{ route('psychologist.availability.bulk-store') }}" method="POST" id="bulkAddForm{{ $dayNum }}">
                                                    @csrf
                                                    <input type="hidden" name="day_of_week" value="{{ $dayNum }}">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Bulk Add Time Slots - {{ $dayInfo['name'] }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-info">
                                                            <i class="fa-solid fa-info-circle me-2"></i>
                                                            Add multiple time slots at once. Click "Add Another Slot" to add more.
                                                        </div>
                                                        <div id="timeSlotsContainer{{ $dayNum }}">
                                                            <div class="time-slot-row mb-3 p-3 border rounded">
                                                                <div class="row">
                                                                    <div class="col-md-5">
                                                                        <label class="form-label">Start Time</label>
                                                                        <input type="time" name="time_slots[0][start_time]" class="form-control" required>
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <label class="form-label">End Time</label>
                                                                        <input type="time" name="time_slots[0][end_time]" class="form-control" required>
                                                                    </div>
                                                                    <div class="col-md-2 d-flex align-items-end">
                                                                        <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeSlotRow(this)">
                                                                            <i class="fa-solid fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSlotRow({{ $dayNum }})">
                                                            <i class="fa-solid fa-plus me-1"></i>Add Another Slot
                                                        </button>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Add All Slots</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Modal -->
                <div class="modal fade" id="quickActionsModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Quick Actions</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <h6>Copy Schedule</h6>
                                        <p class="text-muted small">Copy availability from one day to other days</p>
                                        <form action="{{ route('psychologist.availability.copy-day') }}" method="POST">
                                            @csrf
                                            <div class="form-group mb-2">
                                                <label class="form-label">Copy From</label>
                                                <select name="from_day" class="form-control" required>
                                                    @foreach($days as $dayNum => $dayInfo)
                                                        <option value="{{ $dayNum }}">{{ $dayInfo['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="form-label">Copy To</label>
                                                <div class="row">
                                                    @foreach($days as $dayNum => $dayInfo)
                                                        <div class="col-6">
                                                            <div class="form-check">
                                                                <input type="checkbox" name="to_days[]" class="form-check-input" id="copy_to_{{ $dayNum }}" value="{{ $dayNum }}">
                                                                <label class="form-check-label" for="copy_to_{{ $dayNum }}">{{ $dayInfo['name'] }}</label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="form-group mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" name="replace_existing" class="form-check-input" id="replace_existing" value="1">
                                                    <label class="form-check-label" for="replace_existing">Replace existing slots on target days</label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fa-solid fa-copy me-1"></i>Copy Schedule
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <h6>Quick Templates</h6>
                                        <p class="text-muted small">Apply common schedule templates</p>
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="applyTemplate('weekdays', '09:00', '17:00')">
                                                <i class="fa-solid fa-briefcase me-1"></i>Weekdays 9 AM - 5 PM
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="applyTemplate('weekdays', '10:00', '18:00')">
                                                <i class="fa-solid fa-clock me-1"></i>Weekdays 10 AM - 6 PM
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="applyTemplate('all', '09:00', '17:00')">
                                                <i class="fa-solid fa-calendar-week me-1"></i>All Days 9 AM - 5 PM
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Day Form -->
<form id="deleteDayForm" action="{{ route('psychologist.availability.delete-day') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="day_of_week" id="deleteDayInput">
</form>

<script>
let slotRowIndex = 1;

function addSlotRow(dayNum) {
    const container = document.getElementById('timeSlotsContainer' + dayNum);
    const newRow = document.createElement('div');
    newRow.className = 'time-slot-row mb-3 p-3 border rounded';
    newRow.innerHTML = `
        <div class="row">
            <div class="col-md-5">
                <label class="form-label">Start Time</label>
                <input type="time" name="time_slots[${slotRowIndex}][start_time]" class="form-control" required>
            </div>
            <div class="col-md-5">
                <label class="form-label">End Time</label>
                <input type="time" name="time_slots[${slotRowIndex}][end_time]" class="form-control" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeSlotRow(this)">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
    slotRowIndex++;
}

function removeSlotRow(button) {
    if (document.querySelectorAll('.time-slot-row').length > 1) {
        button.closest('.time-slot-row').remove();
    } else {
        alert('You must have at least one time slot.');
    }
}

function deleteDay(dayNum) {
    if (confirm('Are you sure you want to delete all time slots for this day?')) {
        document.getElementById('deleteDayInput').value = dayNum;
        document.getElementById('deleteDayForm').submit();
    }
}

function applyTemplate(type, startTime, endTime) {
    const days = type === 'weekdays' ? [1, 2, 3, 4, 5] : [0, 1, 2, 3, 4, 5, 6];
    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const selectedDays = days.map(d => dayNames[d]).join(', ');
    
    if (confirm(`Apply ${startTime} - ${endTime} schedule to ${selectedDays}?\n\nThis will add one time slot per day. The page will refresh after each addition.`)) {
        // Submit first day, then the page will refresh and we can continue
        const dayNum = days[0];
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("psychologist.availability.store") }}';
        form.style.display = 'none';
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        const dayInput = document.createElement('input');
        dayInput.type = 'hidden';
        dayInput.name = 'day_of_week';
        dayInput.value = dayNum;
        form.appendChild(dayInput);
        
        const startInput = document.createElement('input');
        startInput.type = 'hidden';
        startInput.name = 'start_time';
        startInput.value = startTime;
        form.appendChild(startInput);
        
        const endInput = document.createElement('input');
        endInput.type = 'hidden';
        endInput.name = 'end_time';
        endInput.value = endTime;
        form.appendChild(endInput);
        
        const availableInput = document.createElement('input');
        availableInput.type = 'hidden';
        availableInput.name = 'is_available';
        availableInput.value = '1';
        form.appendChild(availableInput);
        
        // Store remaining days in sessionStorage to continue after refresh
        const remainingDays = days.slice(1);
        if (remainingDays.length > 0) {
            sessionStorage.setItem('templateDays', JSON.stringify(remainingDays));
            sessionStorage.setItem('templateStart', startTime);
            sessionStorage.setItem('templateEnd', endTime);
        }
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Check if we need to continue applying template after page load
document.addEventListener('DOMContentLoaded', function() {
    const remainingDays = sessionStorage.getItem('templateDays');
    if (remainingDays) {
        const days = JSON.parse(remainingDays);
        const startTime = sessionStorage.getItem('templateStart');
        const endTime = sessionStorage.getItem('templateEnd');
        
        if (days.length > 0) {
            const dayNum = days[0];
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("psychologist.availability.store") }}';
            form.style.display = 'none';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            const dayInput = document.createElement('input');
            dayInput.type = 'hidden';
            dayInput.name = 'day_of_week';
            dayInput.value = dayNum;
            form.appendChild(dayInput);
            
            const startInput = document.createElement('input');
            startInput.type = 'hidden';
            startInput.name = 'start_time';
            startInput.value = startTime;
            form.appendChild(startInput);
            
            const endInput = document.createElement('input');
            endInput.type = 'hidden';
            endInput.name = 'end_time';
            endInput.value = endTime;
            form.appendChild(endInput);
            
            const availableInput = document.createElement('input');
            availableInput.type = 'hidden';
            availableInput.name = 'is_available';
            availableInput.value = '1';
            form.appendChild(availableInput);
            
            const remaining = days.slice(1);
            if (remaining.length > 0) {
                sessionStorage.setItem('templateDays', JSON.stringify(remaining));
            } else {
                sessionStorage.removeItem('templateDays');
                sessionStorage.removeItem('templateStart');
                sessionStorage.removeItem('templateEnd');
            }
            
            document.body.appendChild(form);
            setTimeout(() => form.submit(), 500);
        } else {
            sessionStorage.removeItem('templateDays');
            sessionStorage.removeItem('templateStart');
            sessionStorage.removeItem('templateEnd');
        }
    }
});
</script>

<style>
.time-slot-row {
    background-color: #f8f9fa;
}
.badge-sm {
    font-size: 0.7rem;
    padding: 0.25em 0.5em;
}
</style>
@endsection
