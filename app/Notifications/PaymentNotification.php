<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Payment $payment,
        public string $type // 'uploaded', 'verified', 'rejected'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $messages = [
            'uploaded' => 'Your payment receipt has been uploaded and is pending verification.',
            'verified' => 'Your payment has been verified successfully.',
            'rejected' => 'Your payment receipt has been rejected.',
        ];

        $mail = (new MailMessage)
            ->subject('Payment ' . ucfirst($this->type))
            ->line($messages[$this->type] ?? 'Payment update')
            ->line('Amount: $' . number_format($this->payment->amount, 2));

        if ($this->type === 'rejected' && $this->payment->rejection_reason) {
            $mail->line('Reason: ' . $this->payment->rejection_reason);
        }

        return $mail->action('View Payment', url('/payments/' . $this->payment->id))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'type' => $this->type,
            'message' => 'Payment ' . $this->type,
            'amount' => $this->payment->amount,
        ];
    }
}
