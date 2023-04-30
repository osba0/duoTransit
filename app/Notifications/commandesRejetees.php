<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

use App\Mail\commandesRejeteesMail;

class commandesRejetees extends Notification
{
    use Queueable;

    public $commandes;
    public $emails;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($emails, $commandes)
    {
        $this->commandes = $commandes;
        $this->emails = $emails;
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
         return (new commandesRejeteesMail($this->commandes))->to($notifiable->email)->subject('Commande(s) rejetée(s)');
       
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }
}
