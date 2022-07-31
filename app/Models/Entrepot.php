<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrepot extends Model
{
    protected $fillable = ['id', 'titulaire', 'email', 'telephone', 'nomEntrepot','adresse'];
    use HasFactory;
}
