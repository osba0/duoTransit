<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class ImportCMDResource extends JsonResource
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
            "id"                  => $this->id,
            "type_commande"       => $this->getTypeCommande->typcmd,
            "fournisseur"         => $this->fournisseur,
            "commandes"           => $this->commandes,
            "client"              => $this->client,
            "user_import"         => $this->user_import,
            "etat_cmd"             => $this->etat_cmd,
            "created_at"          => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
            "date_transmission"   => Carbon::parse($this->date_transmission)->format('d/m/Y')

        ];
    }
}
