<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class DossierRessource extends JsonResource
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
            "identifiant"               => $this->id,
            "id"                        => $this->numdossier,
            "total_poids"               => $this->total_poids!=''?$this->total_poids:0,
            "total_volume"              => $this->total_volume!=''?$this->total_volume:0,
            "total_colis"               => $this->total_colis!=''?$this->total_colis:0,
            "total_pallette"            => $this->total_palette!=''?$this->total_palette:0,
            "dateDebut"                 => Carbon::parse($this->dateDebut)->format('d/m/Y'),
            "dateCloture"               => Carbon::parse($this->dateCloture)->format('d/m/Y'),
            "booking"                   => $this->booking==''?'-':$this->booking,
            "terminal_retour"           => $this->terminalRetour==''?'-':$this->terminalRetour,
            "nomNavire"                 => $this->nomNavire==''?'-':$this->nomNavire,
            "dateDepart"                => $this->dateDepart=='' || $this->dateDepart==null ?'-':Carbon::parse($this->dateDepart)->format('d/m/Y'),
            "dateArrivee"               => $this->dateArrivee=='' || $this->dateArrivee==null ?'-'?'-':Carbon::parse($this->dateArrivee)->format('d/m/Y'),
            "dateCrea"                  => isset($this->creation_dos) ? Carbon::parse($this->creation_dos)->format('d/m/Y'):'',
            "user"                      => $this->user,
            "etat"                      => $this->etat,
            "typecmd"                   => $this->typecmd,
            "typecmdId"                 => $this->idTypeCmd,
            "entrepot"                  => $this->nomEntrepot,
            "entrepotID"                => $this->idEntrepot,
            "nbrCmd"                    => $this->total_cmd!=''?$this->total_cmd:0,
            "typeCmd_color"             => isset($this->tcolor)? $this->tcolor : '' ,

        ];
    }
}
