<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypActivity extends Model
{
    use HasFactory;

    const LISTER = 1;
    const CREER = 2;
    const MODIFIER = 3;
    const SUPPRIMER = 4;
    const CONNEXION = 5;


    /***
     * @return array
     */
    public static function getTypeActivity()
    {
        return [
            static::LISTER  => 'Affichage',
            static::CREER => 'Insertion',
            static::MODIFIER => 'Modification',
            static::SUPPRIMER => 'Suppression',
            static::CONNEXION => 'Connexion'
           
        ];
    }
}
