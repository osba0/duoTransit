<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Mail\preCommandesClientMail;

class prechargementCommandesClient extends Notification
{
    use Queueable;

    public $commandes;
    public $emails;
    public $pathFile;
    public $numeroPre;
    public $transitaire;
    public $societe;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transitaire,$societe, $emails, $commandes, $pathFile, $numeroPre)
    {
        $this->commandes     = $commandes;
        $this->emails        = $emails;
        $this->pathFile      = $pathFile;
        $this->numeroPre     = $numeroPre;
        $this->transitaire   = $transitaire;
        $this->societe       = $societe;
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
        return (new preCommandesClientMail($this->transitaire, $this->societe, $this->commandes, $this->numeroPre))->to($this->emails)->attach(public_path() . '/' .$this->pathFile)->subject('Nouveau préchargement  n° '.$this->numeroPre);
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
