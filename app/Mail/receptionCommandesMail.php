<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class receptionCommandesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $commandes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($commandes)
    {
        $this->commandes = $commandes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->view('emails.receptionCommande', ["commandes"=>$this->commandes]);
    }
}
