<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContenaireEmpotageResource extends JsonResource
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
            "id"             => $this->idContenaire,
            "IdEmpotage"     => $this->empotages_id,
            "numContenaire"  => $this->numContenaire,
            "typeContenaire" => $this->nom,
            "plomb"          => $this->plomb,
            "total_poids"    => $this->poidEmpote,
            "total_volume"   => $this->volumeEmpote,
           // "nbrCmd"         => $this->total_cmd!=''?$this->total_cmd:0,
            "colis_total"    => $this->colisEmpote,
            "nomContenaire"   => $this->nom,
            "capaciteContenaire"   => $this->volume,
             "etat"   => $this->etatContanaire
         
        ];
    }
}
