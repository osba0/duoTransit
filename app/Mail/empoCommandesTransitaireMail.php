<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class empoCommandesTransitaireMail extends Mailable
{
    use Queueable, SerializesModels;

    public $commandes;
    public $numeroDossier;
    public $numtc;
    public $typetc;
    public $plomb;
    public $transitaire;
    public $societe;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transitaire, $societe, $commandes, $numeroDossier, $numtc, $typetc, $plomb)
    {
        $this->commandes    = $commandes;
        $this->numeroDossier = $numeroDossier;
        $this->numtc         = $numtc;
        $this->typetc        = $typetc;
        $this->plomb         = $plomb;
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
       return $this->view('emails.empotageMail', ["numeroDossier"=>$this->numeroDossier, "societe" => $this->societe, "transitaire" => $this->transitaire, "numtc" => $this->numtc, "typetc" => $this->typetc, "plomb" => $this->plomb ]);
    }
}
