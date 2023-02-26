<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class MotifListResource extends JsonResource
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
            "id"             => $this->id,
            "idReception"    => $this->idReception,
            "motif"          => $this->motif,
            "username"       => $this->username,
            "user"           => $this->user,
            "datecmd"        => $this->datecmd,
            "numcommande"    => $this->numcommande,
            "datemotif"      => Carbon::parse($this->datemotif)->format('d/m/Y H:i:s')

        ];
    }
}
