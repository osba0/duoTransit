<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class receptionCommandesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transitaire;
    public $societe;
    public $commande;
    public $emails;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transitaire, $societe, $commande)
    {
        $this->transitaire = $transitaire;
        $this->societe = $societe;
        $this->commande = $commande;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->view('emails.receptionCommande', ["commande"=>$this->commande, "societe" => $this->societe, "transitaire" => $this->transitaire ]);
    }
}
