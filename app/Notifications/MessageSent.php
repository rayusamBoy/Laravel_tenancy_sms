<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use App\Helpers\Qs;

class MessageSent extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $message;
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return [FcmChannel::class];
    }

    public function toFcm(): FcmMessage
    {
        $url = route('messages.show', Qs::hash($this->message->thread_id));
        $data = [
            'title' => mb_strimwidth('New message from ' . $this->message->user->name, 0, 35, '...'),
            'body' => mb_strimwidth($this->message->body, 0, 50, "..."),
            'icon' => Qs::getAppIcon(),
            'type' => 'messages',
            'url' => $url,
            'url_title' => "view message",
            'created_at' => $this->message->created_at
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
