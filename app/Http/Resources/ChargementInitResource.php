<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChargementInitResource extends JsonResource
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
            //"id"             => $this->id,
            "numDossier"     => $this->numDossier,
            "dateDebut"      => $this->dateDebut,
            "dateCloture"    => $this->dateCloture,
            "user"           => $this->user->username,
           /* "poidEmpote"     => $this->poidEmpote,
            "volumeEmpote"   => $this->volumeEmpote,
            "colisEmpote"    => $this->colisEmpote,
            "typeCommande"    => $this->type_commandes_id,*/

        ];
    }
}
