<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminAnnouncementNotification extends Notification
{
    use Queueable;

    public function __construct(public Announcement $announcement)
    {
        //
    }

    public function via(object $notifiable): array
    {
        // Always store in database, try email but don't fail if it doesn't work
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->announcement->title)
            ->line($this->announcement->message);

        // Add priority indicator
        if ($this->announcement->priority === 'urgent' || $this->announcement->priority === 'high') {
            $mail->line('⚠️ This is a ' . ucfirst($this->announcement->priority) . ' priority message.');
        }

        // Add type-specific information
        switch ($this->announcement->type) {
            case 'reminder':
                $mail->line('This is a reminder notification.');
                break;
            case 'policy_update':
                $mail->line('Please review this important policy update.');
                break;
            case 'system_alert':
                $mail->line('This is a system alert. Please take appropriate action.');
                break;
        }

        return $mail->action('View Details', url('/'))
            ->line('Thank you for your attention.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'announcement_id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'message' => $this->announcement->message,
            'type' => $this->announcement->type,
            'priority' => $this->announcement->priority,
            'created_at' => $this->announcement->created_at->toDateTimeString(),
        ];
    }
}
