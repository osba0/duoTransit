<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntrepotResource extends JsonResource
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
            'nom'       => $this->nomEntrepot,
            'titulaire' => $this->titulaire,
            'email'     => $this->email,
            'telephone' => $this->telephone,
            'adresse'   => $this->adresse
        ];
    }
}
