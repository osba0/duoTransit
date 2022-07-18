<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DossierPrechargementResource extends JsonResource
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
            "id" => $this->idPre,
            "total_poids"               => $this->total_poids!=''?$this->total_poids:0,
            "total_volume"              => $this->total_volume!=''?$this->total_volume:0,
            "total_colis"               => $this->total_colis!=''?$this->total_colis:0,
            "total_pallette"            => $this->total_palette!=''?$this->total_palette:0,
            "nbre_contenaire"           => $this->nbreContenaire!=''?$this->nbreContenaire:0,
            "created_at"                => $this->created_at_pre,
            "nbrCmd"                    => $this->total_cmd!=''?$this->total_cmd:0,
            "user"                      => $this->user,
            "etat"                      => $this->etat,
            "typecmd"                   => $this->typecmd,
            "contenaire"                => $this->contenaire,
            

        ];
    }
}
