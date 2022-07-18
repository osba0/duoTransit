<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreChargementData extends Model
{
    protected $fillable = [ 'type_commandes_id', 'poidEmpote', 'volumeEmpote', 'colisEmpote', 'reetat', 'clients_id', 'users_id', 'commandes'];
    use HasFactory;
    

  
  
    public function type_commande()
    {
        return $this->belongsTo(TypeCommande::class, 'type_commandes_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
   

}
