<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrechargementResource extends JsonResource
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
            "numDossier"     => $this->chargement_inits_numDossier,
            "dateDebut"      => $this->chargement_init->dateDebut,
            "dateCloture"    => $this->chargement_init->dateCloture,
            "user"           => $this->user->username,
            "poidEmpote"     => $this->poidEmpote,
            "volumeEmpote"   => $this->volumeEmpote,
            "colisEmpote"    => $this->colisEmpote,
            "typeCommande"   => $this->type_commande->typcmd,
            "typeCommandeID" => $this->type_commande->id,
            'commandes' => json_decode($this->commandes, true)

        ];;
    }
}
