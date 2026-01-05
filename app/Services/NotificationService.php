<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Psychologist;
use App\Notifications\AppointmentNotification;
use App\Notifications\PaymentNotification;
use App\Notifications\PsychologistVerificationNotification;
use Carbon\Carbon;

class NotificationService
{
    public function notifyAppointmentCreated(Appointment $appointment): void
    {
        // Notify patient
        $appointment->patient->user->notify(
            new AppointmentNotification($appointment, 'created')
        );

        // Notify psychologist
        $appointment->psychologist->user->notify(
            new AppointmentNotification($appointment, 'created')
        );
    }

    public function notifyAppointmentConfirmed(Appointment $appointment): void
    {
        $appointment->patient->user->notify(
            new AppointmentNotification($appointment, 'confirmed')
        );
    }

    public function notifyAppointmentCancelled(Appointment $appointment): void
    {
        $appointment->patient->user->notify(
            new AppointmentNotification($appointment, 'cancelled')
        );

        $appointment->psychologist->user->notify(
            new AppointmentNotification($appointment, 'cancelled')
        );
    }

    public function notifyAppointmentRescheduled(Appointment $appointment, $oldDate, $oldTime): void
    {
        $appointment->patient->user->notify(
            new AppointmentNotification($appointment, 'rescheduled')
        );

        $appointment->psychologist->user->notify(
            new AppointmentNotification($appointment, 'rescheduled')
        );
    }

    public function sendAppointmentReminders(): void
    {
        $tomorrow = Carbon::tomorrow();
        
        $appointments = Appointment::where('appointment_date', $tomorrow)
            ->where('status', 'confirmed')
            ->with(['patient.user', 'psychologist.user'])
            ->get();

        foreach ($appointments as $appointment) {
            $appointment->patient->user->notify(
                new AppointmentNotification($appointment, 'reminder')
            );

            $appointment->psychologist->user->notify(
                new AppointmentNotification($appointment, 'reminder')
            );
        }
    }

    public function notifyPaymentUploaded(Payment $payment): void
    {
        $payment->appointment->patient->user->notify(
            new PaymentNotification($payment, 'uploaded')
        );
    }

    public function notifyPaymentVerified(Payment $payment): void
    {
        $payment->appointment->patient->user->notify(
            new PaymentNotification($payment, 'verified')
        );
    }

    public function notifyPaymentRejected(Payment $payment): void
    {
        $payment->appointment->patient->user->notify(
            new PaymentNotification($payment, 'rejected')
        );
    }

    public function notifyPsychologistVerified(Psychologist $psychologist): void
    {
        $psychologist->user->notify(
            new PsychologistVerificationNotification($psychologist, 'verified')
        );
    }

    public function notifyPsychologistRejected(Psychologist $psychologist): void
    {
        $psychologist->user->notify(
            new PsychologistVerificationNotification($psychologist, 'rejected')
        );
    }

    public function notifyPaymentDisputed(Payment $payment): void
    {
        // Notify admin about payment dispute
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PaymentNotification($payment, 'disputed'));
        }
    }

    public function notifyDisputeResolved(Payment $payment): void
    {
        $payment->appointment->patient->user->notify(
            new PaymentNotification($payment, 'dispute_resolved')
        );
    }

    public function notifyRefundRequested(Payment $payment): void
    {
        // Notify admin about refund request
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PaymentNotification($payment, 'refund_requested'));
        }
    }

    public function notifyRefundApproved(Payment $payment): void
    {
        $payment->appointment->patient->user->notify(
            new PaymentNotification($payment, 'refund_approved')
        );
    }

    public function notifyRefundRejected(Payment $payment): void
    {
        $payment->appointment->patient->user->notify(
            new PaymentNotification($payment, 'refund_rejected')
        );
    }

    public function notifyRefundProcessed(Payment $payment): void
    {
        $payment->appointment->patient->user->notify(
            new PaymentNotification($payment, 'refund_processed')
        );
    }
}

