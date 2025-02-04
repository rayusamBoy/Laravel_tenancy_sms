<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use Pay;

class StudentPaymentPaid extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $receipt, $subject, $student, $url;
    public function __construct($receipt, $student)
    {
        $this->receipt = $receipt;
        $this->subject = "{$student->user->name} Payment has been made";
        $this->student = $student;
        $this->url = route('payments.receipts', [Pay::hash($this->receipt->pr_id), $this->id]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = Pay::getActiveNotificationChannels($notifiable, true, true, true, true);
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        $url = route('payments.receipts', Pay::hash($this->receipt->pr_id));
        return (new MailMessage)
            ->subject($this->subject)
            ->greeting('Salaam, Hello')
            ->line('One of payments for your child have been paid!')
            ->line('This link will expire at midnight tonight. Please use it before then!')
            ->action('View Receipt', $url)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'receipt' => $this->receipt,
            'subject' => $this->subject,
        ];
    }

    /**
     * Get the Vonage / SMS representation of the notification.
     */
    public function toVonage(): VonageMessage
    {
        return (new VonageMessage)
            ->content("$this->subject. Url: $this->url");
    }

    public function toFcm(): FcmMessage
    {
        $currency_unit = Pay::getCurrencyUnit();
        $data = [
            'title' => $this->subject,
            'body' => "Amount paid was: {$this->receipt->amt_paid} $currency_unit with a remaining balance of: {$this->receipt->balance} $currency_unit. For the academic year: {$this->receipt->year}.",
            'icon' => Pay::getAppIcon(),
            'type' => 'notifications',
            'url' => $this->url,
            'url_title' => "view receipt",
            'created_at' => $this->receipt->created_at
        ];

        return (new FcmMessage())
            ->data($data)
            ->custom([
                'android' => [
                    'notification' => [
                        'color' => '#0A0A0A',
                        'sound' => 'default',
                    ],
                    'fcm_options' => [
                        'analytics_label' => 'analytics',
                    ],
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default'
                        ],
                    ],
                    'fcm_options' => [
                        'analytics_label' => 'analytics',
                    ],
                ],
            ]);
    }
}
