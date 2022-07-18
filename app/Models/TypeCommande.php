<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeCommande extends Model
{
    protected $fillable = ['typcmd', 'tcolor', 'etat'];
    use HasFactory;
}
