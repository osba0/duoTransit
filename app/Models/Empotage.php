<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empotage extends Model
{
    use HasFactory;
    protected $fillable = ['id','reference', 'numContenaire', 'typeContenaire', 'plomb', 'poidEmpote','volumeEmpote','colisEmpote','reetat','is_close', 'users_id', 'clients_id','type_commandes_id', 'contenaires_id','rapport_pdf', 'created_at'];
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
}
