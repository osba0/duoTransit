<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         return [
            'id'        => $this->id,
            'nom'       => $this->nom,
            'telephone' => $this->telephone,
            'adresse'   => $this->adresse,
            'fax'       => $this->fax,
            'email'     => $this->email,
            'logo'      => $this->logo,
            'etat'      => $this->etat,
            'entrepots' => $this->entrepots_id,
            'contenaires' => $this->contenaires_id
        ];
    }
}
