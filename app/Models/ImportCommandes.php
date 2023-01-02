<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportCommandes extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'type_commande', 'fournisseur', 'commandes', 'client', 'date_transmission', 'user_import', 'etat_cmd'];
}
