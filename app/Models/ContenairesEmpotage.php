<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContenairesEmpotage extends Model
{

    use HasFactory;
    protected $casts = [
        'photos_chargement' => 'array'
    ];
    protected $table = 'contenaires_empotage';
    protected $fillable = ['id','empotages_id','numContenaire', 'typeContenaire', 'plomb', 'poidEmpote','volumeEmpote','colisEmpote','etat', 'contenaires_id', 'photos_chargement'];
}
