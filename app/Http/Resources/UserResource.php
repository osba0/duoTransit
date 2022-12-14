<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'firstname' => $this->firstname,
            'lastname'  => $this->lastname,
            'login'     => $this->username,
            'email'     => $this->email,
            'roles'    =>  $this->roles,
            'client_supervisor' =>  $this->client_supervisor,
            'etat'    =>  $this->status,
            'entite'    =>  $this->entites_id

        ];
    }
}
