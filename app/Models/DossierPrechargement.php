<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierPrechargement extends Model
{
    use HasFactory; 

    protected $fillable = ['contenaires_id', 'nbreContenaire', 'reetat', 'users_id', 'entites_id', 'type_commandes_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
