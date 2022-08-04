<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EmpotageResource extends JsonResource
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
            "id"             => $this->IdEmpotage,
            "reference"      => $this->numDossier,
            "numContenaire"  => $this->numContenaire,
            "typeContenaire" => $this->nomContenaire,
            "plomb"          => $this->plomb,
            "user"           => $this->user,
            "total_poids"    => $this->total_poids,
            "total_volume"   => $this->total_volume,
            "nbrCmd"         => $this->total_cmd!=''?$this->total_cmd:0,
            "total_colis"    => $this->total_colis!=''?$this->total_colis:0,
            "total_pallette" => $this->total_palette!=''?$this->total_palette:0,
            "typeCommande"   => $this->typecmd,
            "typeCommandeID" => $this->typecmdID,
            "entrepot"       => $this->nomEntrepot,
            "entrepotID"     => $this->idEntrepot,
            "IDContenaire"   => $this->IDContenaire,
            "capaciteContenaire"   => $this->capacite,
            "is_close"       => $this->cloture, 
            //"clientID"       => 1,
            "etat"           => $this->etat,
            "rapport_pdf"    => $this->rapport_pdf,
            "typeCmd_color"  =>  isset($this->tcolor)? $this->tcolor : '' ,
            "date"           => Carbon::parse($this->created_at)->format('d/m/Y h:i:s')
        ];
    }
}
