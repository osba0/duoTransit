<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class preCommandesTransitaireMail extends Mailable
{
    use Queueable, SerializesModels;

    public $commandes; 
    public $numeroDossier;
    public $dateDebut;
    public $datecloture;
    public $transitaire;
    public $societe;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transitaire, $societe, $commandes, $numeroDossier, $dateDebut, $datecloture)
    {
        $this->commandes = $commandes; 
        $this->numeroDossier = $numeroDossier;
        $this->dateDebut     = $dateDebut;
        $this->datecloture   = $datecloture;
        $this->transitaire   = $transitaire;
        $this->societe       = $societe;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.preChargmementTransitaireMail', ["numeroDossier"=>$this->numeroDossier, "societe" => $this->societe,"dateDebut"=>$this->dateDebut, "datecloture"=>$this->datecloture, "transitaire" => $this->transitaire ]);
    }
}
