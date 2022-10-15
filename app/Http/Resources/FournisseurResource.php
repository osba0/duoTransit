<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FournisseurResource extends JsonResource
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
            'nom'       => $this->fonmfo,
            'telephone' => $this->fotele,
            'email'     => $this->foemail,
            'adresse'   => $this->foadrs,
            'logo'      => $this->fologo,
            'etat'      => $this->foetat
        ];
    }
}
