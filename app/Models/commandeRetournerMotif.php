<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commandeRetournerMotif extends Model
{
    protected $fillable = ['motif', 'username', 'numcommande', 'idreception', 'datemotif', 'datecmd', 'user'];
    use HasFactory;

    const CREATED_AT = 'datemotif';
    const UPDATED_AT = 'dateupdate';
}
