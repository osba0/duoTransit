<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class preCommandesClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public $commandes;
    public $numeroPre;
    public $transitaire;
    public $societe;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transitaire,$societe, $commandes, $numeroPre, $pathFile)
    {
        $this->commandes     = $commandes;
        $this->numeroPre     = $numeroPre;
        $this->transitaire   = $transitaire;
        $this->societe       = $societe;
        $this->pathFile      = $pathFile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.preChargmementClientMail', ["numeroPre"=>$this->numeroPre, "societe" => $this->societe, "transitaire" => $this->transitaire, "pathFile" => $this->pathFile]);
    }
}
