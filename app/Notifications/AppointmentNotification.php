<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public string $type // 'created', 'confirmed', 'cancelled', 'reminder'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $messages = [
            'created' => 'Your appointment has been created successfully.',
            'confirmed' => 'Your appointment has been confirmed.',
            'cancelled' => 'Your appointment has been cancelled.',
            'reminder' => 'Reminder: You have an appointment tomorrow.',
        ];

        return (new MailMessage)
            ->subject('Appointment ' . ucfirst($this->type))
            ->line($messages[$this->type] ?? 'Appointment update')
            ->line('Date: ' . $this->appointment->appointment_date->format('F d, Y'))
            ->line('Time: ' . $this->appointment->appointment_time)
            ->action('View Appointment', url('/appointments/' . $this->appointment->id))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'type' => $this->type,
            'message' => 'Appointment ' . $this->type,
            'appointment_date' => $this->appointment->appointment_date->format('Y-m-d'),
            'appointment_time' => $this->appointment->appointment_time,
        ];
    }
}
