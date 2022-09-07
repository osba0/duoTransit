<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Models\TypActivity;

class Empotage extends Model
{
     use HasFactory, LogsActivity;

    public static $idClient=0;
    public static $idEntite=0;

    protected $casts = [
        'complements_document' => 'array'
    ];

    protected $fillable = ['id','reference', 'numContenaire', 'typeContenaire', 'plomb', 'poidEmpote','volumeEmpote','colisEmpote','reetat','is_close', 'users_id', 'clients_id','type_commandes_id','entrepots_id', 'contenaires_id','rapport_pdf','date_depart','date_arrivee', 'created_at', 'complements_document'];


    protected static $ignoreChangedAttributes = ['updated_at'];

    protected static $logAttributes = ['reference', 'numContenaire', 'typeContenaire', 'plomb', 'poidEmpote','volumeEmpote','colisEmpote','reetat','is_close', 'users_id', 'clients_id','type_commandes_id', 'contenaires_id','rapport_pdf', 'created_at', 'complements_document'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    public function chargement_init()
    {
        return $this->belongsTo(ChargementInit::class, 'chargement_inits_numDossier');   
    }
  
    public function type_commande()
    {
        return $this->belongsTo(TypeCommande::class, 'type_commandes_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public static function setIDClient($currentClient, $currentEntite){
        static::$idClient = $currentClient;
        static::$idEntite = $currentEntite;
        
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->subject_id = static::$idClient; // Which client is doing the action?
        $activity->subject_type = static::$idEntite; 
        switch($eventName){
            case 'created': 
                $activity->log_name = TypActivity::CREER;
                $activity->description = "Création rapport d'empotage";
            break;
            case 'updated': 
                $activity->log_name = TypActivity::MODIFIER;
                $activity->description = "Modification rapport d'empotage";
            break;
            case 'deleted': 
                $activity->log_name = TypActivity::SUPPRIMER;
                $activity->description = "Suppression rapport d'empotage";
            break;


        }
        
    }

    protected static $logOnlyDirty = true;
}
