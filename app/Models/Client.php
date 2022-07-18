<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Client extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = ['clnmcl', 'slug', 'cladcl', 'cllogo', 'cltele', 'cletat', 'clfocl','cltyco', 'pays', 'clenti'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'clfocl' => 'array',
        'cltyco' => 'array',
        'clenti' => 'array'
    ];
    
    const CREATED_AT = 'clcrea'; 
    const UPDATED_AT = 'clupda';
}
