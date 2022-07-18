<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidents extends Model
{
    protected $fillable = ['commandes', 'objet', 'commentaire','photos', 'users_id', 'clients_id', 'entites_id'];
    
    use HasFactory;
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'photos' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id'); 
    }
}
