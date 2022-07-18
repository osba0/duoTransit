<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Models\TypActivity;

class ChargementCreation extends Model
{
    protected $fillable = ['numDossier', 'dateDebut', 'dateCloture', 'users_id', 'clients_id', 'type_commandes_id', 'reetat'];
   
    use HasFactory, LogsActivity;

    public static $idClient=0;
    public static $idEntite=0;

    protected static $ignoreChangedAttributes = ['updated_at'];

    protected static $logAttributes = ['numDossier', 'dateDebut', 'dateCloture', 'users_id', 'clients_id', 'type_commandes_id', 'reetat'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function scopeChargementQuery($query)
    {
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
                $activity->description = "Création nouveau dossier";
            break;
            case 'updated': 
                $activity->log_name = TypActivity::MODIFIER;
                $activity->description = "Modification dossier";
            break;
            case 'deleted': 
                $activity->log_name = TypActivity::SUPPRIMER;
                $activity->description = "Suppression dossier";
            break;


        }
        
    }

    protected static $logOnlyDirty = true;
}
