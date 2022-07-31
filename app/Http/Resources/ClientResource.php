<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'slug'      => $this->slug,
            'nom'       => $this->clnmcl,
            'email'     => $this->clmail,
            'telephone' => $this->cltele,
            'adresse'   => $this->cladcl,
            'logo'      => $this->cllogo,
            'pays'      => $this->pays,   
            'etat'      => $this->cletat,
            'typeCmd'      => $this->cltyco,
            'fournisseurs' => $this->clfocl,
            'clenti'    => $this->clenti

        ];
    }
}
