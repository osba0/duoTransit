<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargementInit extends Model
{
    protected $fillable = ['numDossier', 'dateDebut', 'dateCloture', 'users_id', 'clients_id', 'type_commandes_id'];
    //protected $primaryKey = 'numDossier';
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    /*public function PreChargementData()
    {
        return $this->belongsTo(PreChargementData::class, 'numDossier');
    } */

    public function scopeChargementQuery($query)
    {

        

    }
}
