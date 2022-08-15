<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

use App\Mail\receptionCommandesMail;

class receptionCommandes extends Notification
{
    use Queueable;
    
    public $transitaire;
    public $societe;
    public $commande;
    public $emails;
    public $pathFile;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transitaire, $societe, $emails, $commande, $pathFile)
    {
        $this->transitaire = $transitaire;
        $this->societe = $societe;
        $this->commande = $commande;
        $this->emails = $emails;
        $this->pathFile = $pathFile;

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
        return (new receptionCommandesMail($this->transitaire, $this->societe, $this->commande))->to($this->emails)->attach(public_path() . '/' .$this->pathFile)->subject('Nouvelle commande n°: '.$this->commande['rencmd']);
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
            'title' => 'Nouvelle commande n°'.$this->commande['rencmd'].', Type Commande:'.$this->commande['typeCmd'],
            'description' => 'Fournisseur: '.$this->commande['fournisseur'].', Entrepôt: '.$this->commande['entrepot'].', N°Facture:'.$this->commande['renufa'].', N°FE: '.$this->commande['refere'].', N°ECV: '.$this->commande['reecvr'].', Nbre Palette: '.$this->commande['renbpl'].', Nbre Colis: '.$this->commande['renbcl'].', Poids(KG): '.$this->commande['repoid'].', Volume: '.$this->commande['revolu'].', Montant Facture(€): '.$this->commande['revafa'].', Commantaire: '.$this->commande['recomt'].', Date livraison: '.$this->commande['redali'],
            'fichier' => '/' .$this->pathFile,
            'user' => Auth::user(),
            'slug' => $this->societe['slug']   
        ];
    }
}
