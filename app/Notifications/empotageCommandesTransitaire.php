<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

use App\Mail\empoCommandesTransitaireMail;

class empotageCommandesTransitaire extends Notification
{
    use Queueable;

    public $commandes;
    public $emails;
    public $pathFile;
    public $numeroDossier;
    public $numtc;
    public $typetc;
    public $plomb;
    public $transitaire;
    public $societe;
    public $typeCommande;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transitaire, $societe, $emails, $commandes, $pathFile, $numeroDossier, $numtc, $typetc, $plomb, $typeCommande)
    {
        $this->commandes    = $commandes;
        $this->emails        = $emails;
        $this->pathFile      = $pathFile;
        $this->numeroDossier = $numeroDossier;
        $this->numtc         = $numtc;
        $this->typetc        = $typetc;
        $this->plomb         = $plomb;
        $this->transitaire   = $transitaire;
        $this->societe       = $societe;
        $this->typeCommande  = $typeCommande;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new empoCommandesTransitaireMail($this->transitaire, $this->societe, $this->commandes, $this->numeroDossier, $this->numtc, $this->typetc, $this->plomb, $this->typeCommande))->to($this->emails)->attach(public_path() . '/' .$this->pathFile)->subject('Rapport d\'empotage   n° dossier: '.$this->numeroDossier);
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
            'title' => 'Validation rapport d\'empotage n°'.$this->numeroDossier.', '.$this->typeCommande,
            'description' => 'TC: '.$this->numtc.' TypeTC: '.$this->typetc.' Plomb:'.$this->plomb,
            'fichier' => '/' .$this->pathFile,
            'user' => Auth::user(),
            'slug' => $this->societe['slug']   
        ];
    }
}
