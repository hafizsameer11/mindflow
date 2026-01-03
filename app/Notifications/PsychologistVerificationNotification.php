<?php

namespace App\Notifications;

use App\Models\Psychologist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PsychologistVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Psychologist $psychologist,
        public string $status // 'verified', 'rejected'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        if ($this->status === 'verified') {
            return (new MailMessage)
                ->subject('Account Verified')
                ->line('Congratulations! Your psychologist account has been verified.')
                ->line('You can now start accepting appointments from patients.')
                ->action('Go to Dashboard', url('/psychologist/dashboard'))
                ->line('Thank you for joining our platform!');
        } else {
            return (new MailMessage)
                ->subject('Verification Status Update')
                ->line('Your psychologist account verification has been reviewed.')
                ->line('Unfortunately, your account could not be verified at this time.')
                ->line('Please contact support for more information.')
                ->line('Thank you for your interest.');
        }
    }

    public function toArray(object $notifiable): array
    {
        return [
            'psychologist_id' => $this->psychologist->id,
            'status' => $this->status,
            'message' => 'Account verification ' . $this->status,
        ];
    }
}
