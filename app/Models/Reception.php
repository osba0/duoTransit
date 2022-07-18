<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use App\Models\TypActivity;

class Reception extends Model
{
    protected $fillable = ['refere', 'reecvr', 'renufa', 'revafa', 'fournisseurs_id', 'type_commandes_id', 'clients_id', 'repoid', 'revolu', 'renbcl','renbpl','reemba', 'reempl', 'redali', 'rencmd', 'reetat','refasc', 'recomnt', 'entrepots_id', 'users_id', 'entites_id', 'dossier_prechargements_id','dossier_empotage_id'];

    use HasFactory, LogsActivity;

    public static $idClient=0;
    public static $idEntite=0;

    const CREATED_AT = 'recrea';
    const UPDATED_AT = 'reupda';

    protected $primaryKey = 'reidre';

    protected static $ignoreChangedAttributes = ['updated_at'];

    protected static $logAttributes = ['reidre','refere', 'reecvr', 'renufa', 'revafa', 'fournisseurs_id', 'type_commandes_id', 'clients_id', 'repoid', 'revolu', 'renbcl','renbpl','reemba', 'reempl','dossier_id', 'redali', 'rencmd', 'reetat','refasc', 'recomnt', 'entrepots_id', 'users_id', 'entites_id', 'dossier_prechargements_id','dossier_empotage_id'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

   
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseurs_id');
    }
  
    public function type_commande()
    {
        return $this->belongsTo(TypeCommande::class, 'type_commandes_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

     public function scopeReceptionsQuery($query)
    {

        $typeCmd = request('typeCmd');
        if(isset($typeCmd)){
            $query->where('type_commandes_id', $typeCmd)->where('receptions.clients_id', request('id'))
                ->orderBy('redali', 'desc');
        }else{
             $query->where('receptions.clients_id', request('id'))
                ->orderBy('redali', 'asc');
         
        }
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function ($query) use ($term) {
            $query->where('reecvr', 'like', $term)
                ->orWhere('renufa', 'like', $term)
                ->orWhere('repoid', 'like', $term)
                ->orWhere('revolu', 'like', $term)
                ->orWhereHas('user', function ($query) use ($term) {
                    $query->where('firstname', 'like', $term);
                })
                ->orWhereHas('type_commande', function ($query) use ($term) {
                    $query->where('typcmd', 'like', $term);
                }) 
                ->orWhereHas('fournisseur', function ($query) use ($term) {
                    $query->where('fonmfo', 'like', $term);
                });
        });
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
                $activity->description = "Nouvelle réception";
            break;
            case 'updated': 
                $activity->log_name = TypActivity::MODIFIER;
                $activity->description = "Modification commande";
            break;
            case 'deleted': 
                $activity->log_name = TypActivity::SUPPRIMER;
                $activity->description = "Suppression commande";
            break;


        }
        
    }

    protected static $logOnlyDirty = true;
}
