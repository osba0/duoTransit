<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportCommandes extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'type_commande', 'fournisseur', 'commandes', 'client', 'date_transmission', 'user_import', 'etat_cmd'];

     public function getTypeCommande()
    {
        return $this->belongsTo(TypeCommande::class, 'type_commande');
    }

    public function scopeImportcmdQuery($query)
    {

        $typeCmd = request('typeCmd');

        $etat = request('etat');

        if($typeCmd!=""){
            $query->where('type_commande', $typeCmd);
        }

         if($etat!=""){
            $query->where('etat_cmd', $etat);
        }

        $query->orderBy('created_at', 'desc');
    }
}
