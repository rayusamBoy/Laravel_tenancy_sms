<?php

namespace App\Notifications;

use App\Helpers\Qs;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;

class StudentPaymentPaid extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $receipt, $subject, $student;
    public function __construct($receipt, $student)
    {
        $this->receipt = $receipt;
        $this->subject = $student->user->name . ' Payment has been Paid.';
        $this->student = $student;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['mail', 'database', FcmChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        $url = route('payments.receipts', Qs::hash($this->receipt->pr_id));
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

    public function toFcm(): FcmMessage
    {
        $url = route('payments.receipts', [Qs::hash($this->receipt->pr_id), $this->id]);
        $data = [
            'title' => mb_strimwidth($this->subject, -30, 30, '...'),
            'body' => 'Amount paid was: ' . $this->receipt->amt_paid . ' with a remaining balance of: ' . $this->receipt . '. For the year: ' . $this->receipt->year,
            'icon' => Qs::getAppIcon(),
            'type' => 'notifications',
            'url' => $url,
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
