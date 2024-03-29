<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class ReceptionResource extends JsonResource
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
            "reidre"         => $this->reidre,
            "refere"         => $this->refere,
            "reecvr"         => $this->reecvr,
            "renufa"         => $this->renufa,
            "revafa"         => $this->revafa,
            "fournisseurs"   => isset($this->fournisseur)?$this->fournisseur->fonmfo:$this->fonmfo,
            "id_fournisseurs"=> $this->fournisseurs_id,
            "type_commandes" => isset($this->type_commande->typcmd)? $this->type_commande->typcmd : '',
            "typeCmd_color"  => isset($this->type_commande->tcolor)? $this->type_commande->tcolor : '' ,
            "id_commandes"   => is_null($this->type_commandes_id)? $this->typeCmd : $this->type_commandes_id, // typeCmd sur empotage
            "clients_id"     => $this->clients_id,
            "entrepots_id"   => $this->entrepots_id,
            "reuser"         => isset($this->user->username)? $this->user->username : '',
            "repoid"         => $this->repoid,
            "revolu"         => $this->revolu,
            "renbpl"         => $this->renbpl,
            "renbcl"         => $this->renbcl,
            "reempl"         => $this->reempl,
            "redali"         => Carbon::parse($this->redali)->format('d/m/Y'),
            "redalivraison"  => $this->redali,
            "rencmd"         => $this->rencmd,
            "reetat"         => $this->reetat,
            "typeproduit"    => $this->typeproduit,
            "refasc"         => (isset($this->refasc) && !is_null($this->refasc) && $this->refasc!='')? is_array($this->refasc)?$this->refasc: json_decode($this->refasc) : json_decode("[]"),
            "recomt"         => $this->recomt,
            "douane"         => $this->douane,
            "depalettisation" => $this->depalettisation,
            "recrea"         => Carbon::parse($this->recrea)->format('d/m/Y H:i:s'),
            "reupda"         => Carbon::parse($this->reupda)->format('d/m/Y H:i:s'),
            "isLoad"         => $this->isLoad,
            "isPreLoad"      => $this->isPreLoad,
            "idPre"          => $this->dossier_prechargements_id,
            "dossier_id"     => $this->dossier_id,
            "prechargeur"    => $this->prechargeur,
            "user_created"   => $this->user_created,
            "dossier_empotage_id"     => $this->dossier_empotage_id,
            "hasIncident"    => $this->reinci,
            "priorite"       => isset($this->priorite)? $this->priorite:'',
            "nbreJour"       =>  Carbon::now()->diffInDays(Carbon::parse($this->redali)),
            "listgroup"      => $this->regroup,
            "motifID"        => isset($this->idReception)? $this->idReception : '',
            //"isPreLoaded"    => $this->dossier_prechargements_id 

        ];
    }
}
