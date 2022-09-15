<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

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
            "total_mnt"                 => $this->total_mnt!=''?$this->total_mnt:0,
            "nbre_contenaire"           => $this->nbreContenaire!=''?$this->nbreContenaire:0,
            "created_at"                => Carbon::parse($this->created_at_pre)->format('d/m/Y'),
            "updated_at"                => isset($this->updated_at_pre) ? Carbon::parse($this->updated_at_pre)->format('d/m/Y H:i:s'):'',
            "nbrCmd"                    => $this->total_cmd!=''?$this->total_cmd:0,
            "user"                      => $this->user,
            "etat"                      => $this->etat,
            "typecmd"                   => $this->typecmd,
            "typecmdID"                 => $this->typecmdID, 
            "typeCmd_color"             => $this->typecmdColor, 
            "contenaire"                => $this->contenaire,
            //"entrepotID"                => $this->entrepots_id,
            //"entrepot"                  => $this->entrepots_name,
            "entiteID"                  => $this->entite_id,
            "entite"                    => $this->entite_name,
            //"rapport_pdf"               => isset($this->rapport_pdf) ? Carbon::parse($this->updated_at_pre)->format('d/m/Y'):''


        ];
    }
}
