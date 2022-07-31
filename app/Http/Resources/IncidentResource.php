<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
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
            "id"             => $this->id,
            "commandes"      => $this->commandes,
            "objet"          => $this->objet,
            "commentaires"   => isset($this->commentaire)? $this->commentaire : '' ,
            "photos"         => json_decode($this->photos),
            "user"           => $this->user->username,
            "clientID"       => $this->clients_id, 
        ];
    }
}
