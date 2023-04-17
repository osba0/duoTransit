<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Mail\preCommandesTransitaireMail;

class prechargementCommandesTransitaire extends Notification
{
    use Queueable;

    public $commandes;
    public $emails;
    public $pathFile;
    public $numeroDossier;
    public $dateDebut;
    public $datecloture;
    public $transitaire;
    public $societe;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transitaire,$societe, $emails, $commandes, $pathFile, $numeroDossier, $dateDebut, $datecloture)
    {
        $this->commandes     = $commandes;
        $this->emails        = $emails;
        $this->pathFile      = $pathFile;
        $this->numeroDossier = $numeroDossier;
        $this->dateDebut     = $dateDebut;
        $this->datecloture   = $datecloture;
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
         return (new preCommandesTransitaireMail($this->transitaire, $this->societe, $this->commandes, $this->numeroDossier, $this->dateDebut, $this->datecloture))->to($notifiable->email)->attach(public_path() . '/' .$this->pathFile)->subject('Nouveau dossier de préchargement  n° '.$this->numeroDossier.' du '.$this->dateDebut.' au '.$this->datecloture);
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
