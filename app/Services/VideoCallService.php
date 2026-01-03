<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Str;

class VideoCallService
{
    /**
     * Generate a unique meeting link for an appointment
     * Using Jitsi Meet format: random alphanumeric string
     */
    public function generateMeetingLink(Appointment $appointment): string
    {
        if ($appointment->meeting_link) {
            return $appointment->meeting_link;
        }

        // Generate a unique meeting room name for Jitsi Meet
        // Format: appointment-{id}-{random}
        $meetingLink = 'appointment-' . $appointment->id . '-' . Str::random(16);
        $appointment->update(['meeting_link' => $meetingLink]);

        return $meetingLink;
    }

    /**
     * Get meeting room URL (for WebRTC integration)
     * This can be integrated with services like Agora, Twilio, or custom WebRTC
     */
    public function getMeetingRoomUrl(Appointment $appointment): string
    {
        $meetingLink = $this->generateMeetingLink($appointment);
        
        // For now, return a simple URL structure
        // In production, this would integrate with a video calling service
        return route('video.meeting', ['link' => $meetingLink]);
    }

    /**
     * Validate meeting access
     */
    public function canAccessMeeting(Appointment $appointment, $user): bool
    {
        if (!$appointment->meeting_link) {
            return false;
        }

        if ($appointment->status !== 'confirmed' && $appointment->status !== 'completed') {
            return false;
        }

        // Check if user is patient or psychologist for this appointment
        if ($user->isPatient() && $appointment->patient_id === $user->patient->id) {
            return true;
        }

        if ($user->isPsychologist() && $appointment->psychologist_id === $user->psychologist->id) {
            return true;
        }

        return false;
    }
}

