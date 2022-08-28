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
    public $nameFile;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transitaire, $societe, $emails, $commande, $pathFile, $namefile)
    {
        $this->transitaire = $transitaire;
        $this->societe = $societe;
        $this->commande = $commande;
        $this->emails = $emails;
        $this->pathFile = $pathFile;
        $this->nameFile = $namefile;

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
        if(is_null($this->nameFile) || $this->nameFile==''){
            return (new receptionCommandesMail($this->transitaire, $this->societe, $this->commande))->to($this->emails)->subject('Nouvelle commande n° '.$this->commande['rencmd'].'-'.$this->commande['typeCmd']);
         }else{
            return (new receptionCommandesMail($this->transitaire, $this->societe, $this->commande))->to($this->emails)->attach(public_path() . '/' .$this->pathFile)->subject('Nouvelle commande n° '.$this->commande['rencmd'].'-'.$this->commande['typeCmd']);
         }
       
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
            'title' => 'Nouvelle commande n° '.$this->commande['rencmd'].', Type Commande:'.$this->commande['typeCmd'],
            'description' => 'N° Commande:'.$this->commande['rencmd'].', Fournisseur: '.$this->commande['fournisseur'].', Poids(KG): '.$this->commande['repoid'].', Volume(m3): '.$this->commande['revolu'], 
            'fichier' => '/' .$this->pathFile,
            'user' => Auth::user(),
            'slug' => $this->societe['slug']   
        ];
    }
}

/*.', Entrepôt: '.$this->commande['entrepot'].', N°Facture:'.$this->commande['renufa'].', N°FE: '.$this->commande['refere'].', N°ECV: '.$this->commande['reecvr'].', Nbre Palette: '.$this->commande['renbpl'].', Nbre Colis: '.$this->commande['renbcl'].', Poids(KG): '.$this->commande['repoid'].', Volume: '.$this->commande['revolu'].', Montant Facture(€): '.$this->commande['revafa'].', Commantaire: '.$this->commande['recomt'].', Date livraison: '.$this->commande['redali']*/