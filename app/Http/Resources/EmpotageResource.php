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
            //"numContenaire"  => $this->numContenaire,
            //"typeContenaire" => $this->nomContenaire,
            //"plomb"          => $this->plomb,
            "user"           => $this->user,
            //"total_poids"    => $this->total_poids,
            //"total_volume"   => $this->total_volume,
            //"nbrCmd"         => $this->total_cmd!=''?$this->total_cmd:0,
            //"total_colis"    => $this->total_colis!=''?$this->total_colis:0,
            //"total_pallette" => $this->total_palette!=''?$this->total_palette:0,
            //"colis_total"    => isset($this->colis_total)? $this->colis_total: '',
            "typeCommande"   => $this->typecmd,
            "typeCommandeID" => $this->typecmdID,
            "entrepot"       => $this->nomEntrepot,
            "entrepotID"     => $this->idEntrepot,
            //"IDContenaire"   => $this->IDContenaire,
            //"capaciteContenaire"   => $this->capacite,
            "is_close"       => $this->cloture, 

            "etat"           => $this->etat,
            //"rapport_pdf"    => $this->rapport_pdf,
            "typeCmd_color"  =>  isset($this->tcolor)? $this->tcolor : '' ,
            "dateDepart"     => $this->dateDepart!=null ? Carbon::parse($this->dateDepart)->format('d/m/Y'):'',
            "dateArrivee"    => $this->dateArrivee!=null ? Carbon::parse($this->dateArrivee)->format('d/m/Y'): '',
            "date"           => Carbon::parse($this->created_at)->format('d/m/Y'),
            "dateDepartEng"     => $this->dateDepart!=null ? $this->dateDepart:'',
            "dateArriveeEng"    => $this->dateArrivee!=null ? $this->dateArrivee: '',
            "document"  =>  isset($this->docs) && !is_null($this->docs)? json_decode($this->docs) : json_decode("[]"),
            "autre_document" => isset($this->autre_docs) && !is_null($this->autre_docs)? json_decode($this->autre_docs) : json_decode("[]"),
            "declDounae"  =>  isset($this->decldouane) && !is_null($this->decldouane)? json_decode($this->decldouane) : json_decode("[]"),
            "totalContenaire" => $this->nbreContenaireEmpote,
            "numDocim"   => $this->numDocim,
            "nbreContenaireNonValide" => isset($this->nbreContenaireNonValide)? $this->nbreContenaireNonValide:-1
        ];
    }
}
