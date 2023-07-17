<?php

namespace App\Notifications\User\Referral;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendInvitation extends Notification
{
    use Queueable;

    private $user;
    private $callbackUrl;

    /**
     * Create a new notification instance.
     *
     * @param $user
     * @param $callbackUrl
     */
    public function __construct($user, $callbackUrl)
    {
        $this->user = $user;
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = $this->callbackUrl . "/register?liveizyId={$this->user->liveizy_id}";
        return (new MailMessage)
            ->subject('Liveizy Invitation')
            ->line("You've been Invited by {$this->user->getName()} to join the Liveizy platform ")
            ->line('Click on the below to Register!')
            ->action('Register', $url)
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
