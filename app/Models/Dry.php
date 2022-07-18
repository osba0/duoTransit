<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dry extends Model
{
    protected $fillable = ['drynom', 'drydes', 'dryeta'];
    
    use HasFactory;
    const CREATED_AT = 'drycre';
    const UPDATED_AT = 'dryupd';
}
