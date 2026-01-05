<?php $page = 'booking'; ?>
@extends('layout.mainlayout')
@section('content')
    @component('components.breadcrumb')
        @slot('title')
        Patient
        @endslot
        @slot('li_1')
            <a href="{{ route('patient.search') }}">Search Psychologists</a>
        @endslot
        @slot('li_2')
            <a href="{{ route('patient.psychologist.show', $psychologist) }}">Psychologist Profile</a>
        @endslot
        @slot('li_3')
            Book Appointment
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
                        <h3>Book Appointment</h3>
                    </div>

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

                    <!-- Psychologist Info Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    @php
                                        $profileImage = $psychologist->user->profile_image 
                                            ? asset('storage/' . $psychologist->user->profile_image) 
                                            : asset('assets/img/doctors/doc-profile-02.jpg');
                                    @endphp
                                    <img src="{{ $profileImage }}" class="img-fluid rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;" alt="{{ $psychologist->user->name }}">
                                </div>
                                <div class="col-md-9">
                                    <h4>{{ $psychologist->user->name }}</h4>
                                    <p class="text-muted mb-2"><strong>Specialization:</strong> {{ $psychologist->specialization }}</p>
                                    <p class="text-muted mb-2"><strong>Experience:</strong> {{ $psychologist->experience_years }} Years</p>
                                    <p class="text-primary mb-0"><strong>Consultation Fee:</strong> ${{ number_format($psychologist->consultation_fee, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Form -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Select Date & Time</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('patient.appointments.store', $psychologist) }}" method="POST" id="bookingForm">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Appointment Date <span class="text-danger">*</span></label>
                                        <input type="date" name="appointment_date" id="appointment_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                                        <small class="form-text text-muted">Select a date for your appointment</small>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Duration (Minutes) <span class="text-danger">*</span></label>
                                        <select name="duration" class="form-control" required>
                                            <option value="30">30 Minutes</option>
                                            <option value="60" selected>60 Minutes</option>
                                            <option value="90">90 Minutes</option>
                                            <option value="120">120 Minutes</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Available Time Slots -->
                                <div class="mb-3">
                                    <label class="form-label">Available Time Slots <span class="text-danger">*</span></label>
                                    <div id="timeSlotsContainer" class="time-slots-container">
                                        <p class="text-muted">Please select a date to see available time slots</p>
                                    </div>
                                    <input type="hidden" name="appointment_time" id="appointment_time" required>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fa-solid fa-info-circle me-2"></i>
                                    <strong>Note:</strong> After booking, you'll be redirected to upload your payment receipt.
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('patient.psychologist.show', $psychologist) }}" class="btn btn-secondary">
                                        <i class="fa-solid fa-arrow-left me-2"></i>Back
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                        <i class="fa-solid fa-calendar-check me-2"></i>Book Appointment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Weekly Availability Info -->
                    @if($availabilities->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Weekly Availability</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $days = [
                                    0 => 'Sunday',
                                    1 => 'Monday',
                                    2 => 'Tuesday',
                                    3 => 'Wednesday',
                                    4 => 'Thursday',
                                    5 => 'Friday',
                                    6 => 'Saturday'
                                ];
                            @endphp
                            <div class="row">
                                @foreach($days as $dayNum => $dayName)
                                    @if($availabilities->has($dayNum))
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-primary">{{ $dayName }}</h6>
                                            <ul class="list-unstyled">
                                                @foreach($availabilities->get($dayNum) as $availability)
                                                    <li class="text-muted">
                                                        <i class="fa-solid fa-clock me-2"></i>
                                                        {{ \Carbon\Carbon::parse($availability->start_time)->format('h:i A') }} - 
                                                        {{ \Carbon\Carbon::parse($availability->end_time)->format('h:i A') }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning mt-4">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        This psychologist has not set their availability yet. Please contact them directly.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->

<script>
const availabilities = @json($availabilities);
const existingAppointments = @json($existingAppointments);
const days = {
    0: 'Sunday', 1: 'Monday', 2: 'Tuesday', 3: 'Wednesday',
    4: 'Thursday', 5: 'Friday', 6: 'Saturday'
};

document.getElementById('appointment_date').addEventListener('change', function() {
    const selectedDate = this.value;
    const container = document.getElementById('timeSlotsContainer');
    const timeInput = document.getElementById('appointment_time');
    const submitBtn = document.getElementById('submitBtn');
    
    // Reset
    timeInput.value = '';
    submitBtn.disabled = true;
    container.innerHTML = '';
    
    if (!selectedDate) {
        container.innerHTML = '<p class="text-muted">Please select a date to see available time slots</p>';
        return;
    }
    
    const dateObj = new Date(selectedDate);
    const dayOfWeek = dateObj.getDay();
    const dayAvailabilities = availabilities[dayOfWeek] || [];
    
    if (dayAvailabilities.length === 0) {
        container.innerHTML = '<div class="alert alert-warning">No availability for ' + days[dayOfWeek] + '. Please select another date.</div>';
        return;
    }
    
    // Generate time slots (30-minute intervals)
    let slots = [];
    dayAvailabilities.forEach(avail => {
        const start = new Date('2000-01-01 ' + avail.start_time);
        const end = new Date('2000-01-01 ' + avail.end_time);
        
        let current = new Date(start);
        while (current < end) {
            const timeStr = current.toTimeString().substring(0, 5);
            const formattedTime = current.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
            
            // Check if slot is already booked
            const isBooked = existingAppointments.some(apt => 
                apt.date === selectedDate && apt.time === timeStr
            );
            
            if (!isBooked) {
                slots.push({
                    time: timeStr,
                    display: formattedTime
                });
            }
            
            // Add 30 minutes
            current.setMinutes(current.getMinutes() + 30);
        }
    });
    
    if (slots.length === 0) {
        container.innerHTML = '<div class="alert alert-warning">All time slots for this date are already booked. Please select another date.</div>';
        return;
    }
    
    // Display slots
    let html = '<div class="row">';
    slots.forEach(slot => {
        html += `
            <div class="col-md-3 col-sm-4 mb-2">
                <button type="button" class="btn btn-outline-primary w-100 time-slot-btn" 
                        data-time="${slot.time}" 
                        onclick="selectTimeSlot('${slot.time}', this)">
                    ${slot.display}
                </button>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
});

function selectTimeSlot(time, button) {
    // Remove active class from all buttons
    document.querySelectorAll('.time-slot-btn').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-outline-primary');
    });
    
    // Add active class to selected button
    button.classList.remove('btn-outline-primary');
    button.classList.add('btn-primary');
    
    // Set hidden input
    document.getElementById('appointment_time').value = time;
    
    // Enable submit button
    document.getElementById('submitBtn').disabled = false;
}

// Validate form before submit
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const date = document.getElementById('appointment_date').value;
    const time = document.getElementById('appointment_time').value;
    
    if (!date || !time) {
        e.preventDefault();
        alert('Please select both date and time for your appointment.');
        return false;
    }
});
</script>

<style>
.time-slots-container {
    min-height: 100px;
    padding: 15px;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    background-color: #f8f9fa;
}

.time-slot-btn {
    transition: all 0.3s;
}

.time-slot-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
</style>
@endsection

