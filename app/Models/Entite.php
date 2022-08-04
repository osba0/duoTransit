<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entite extends Model
{
    protected $fillable = ['id', 'nom', 'slug', 'adresse', 'logo', 'telephone', 'fax', 'email', 'etat', 'contenaires_id', 'entrepots_id'];

    use HasFactory;

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'contenaires_id' => 'array',
        'entrepots_id' => 'array'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'nom'
            ]
        ];
    }
}
