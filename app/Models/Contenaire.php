<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contenaire extends Model
{
    use HasFactory;
     protected $fillable = ['id', 'nom', 'volume', 'isdefault'];
}
