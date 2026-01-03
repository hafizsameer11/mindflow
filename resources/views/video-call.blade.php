<?php $page = 'video-call'; ?>
@extends('layout.mainlayout')
@section('content')
<!-- Page Content -->
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="call-wrapper">
                    <div class="call-main-row">
                        <div class="call-main-wrapper">
                            <div class="call-view">
                                <div class="call-window">
                                    <!-- Video Meeting Container -->
                                    <div id="jitsi-container" style="width: 100%; height: 600px; background: #000;"></div>
                                    
                                    <!-- Meeting Info Overlay -->
                                    <div class="call-info-overlay">
                                        <div class="call-info">
                                            <h4>Session with {{ $appointment->psychologist->user->name }}</h4>
                                            <p>Appointment: {{ $appointment->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
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
<!-- /Page Content -->

@push('scripts')
<!-- Jitsi Meet External API -->
<script src="https://8x8.vc/external_api.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const domain = 'meet.jit.si';
        const options = {
            roomName: '{{ $appointment->meeting_link }}',
            width: '100%',
            height: 600,
            parentNode: document.querySelector('#jitsi-container'),
            configOverwrite: {
                startWithAudioMuted: false,
                startWithVideoMuted: false,
                enableNoAudioDetection: true,
                enableNoisyMicDetection: true,
                // Disable audio-only mode - force video
                disableAudio: false,
                disableVideo: false,
                // Force video to be enabled
                constraints: {
                    video: {
                        height: { ideal: 720, max: 1080, min: 240 },
                        width: { ideal: 1280, max: 1920, min: 320 }
                    }
                }
            },
            interfaceConfigOverwrite: {
                TOOLBAR_BUTTONS: [
                    'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                    'fodeviceselection', 'hangup', 'chat', 'settings', 'videoquality',
                    'filmstrip', 'feedback', 'stats', 'shortcuts', 'tileview'
                ],
                SETTINGS_SECTIONS: ['devices', 'language', 'moderator', 'profile'],
                DEFAULT_BACKGROUND: '#474747',
                INITIAL_TOOLBAR_TIMEOUT: 20000,
                TOOLBAR_TIMEOUT: 4000,
                HIDE_INVITE_MORE_HEADER: false
            },
            userInfo: {
                displayName: '{{ Auth::user()->name }}',
                email: '{{ Auth::user()->email }}'
            },
            onload: function() {
                console.log('Jitsi Meet API loaded');
            }
        };

        const api = new JitsiMeetExternalAPI(domain, options);

        // Handle meeting events
        api.addEventListener('videoConferenceJoined', function() {
            console.log('Joined video conference');
            // Ensure video is enabled
            api.executeCommand('toggleVideo');
        });

        api.addEventListener('readyToClose', function() {
            // Redirect when meeting ends
            window.location.href = '{{ route("patient.appointments.show", $appointment) }}';
        });

        // Handle errors
        api.addEventListener('participantLeft', function(event) {
            console.log('Participant left:', event);
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            api.dispose();
        });
    });
</script>
@endpush

@endsection

