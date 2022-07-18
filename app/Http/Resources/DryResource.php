<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DryResource extends JsonResource
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
            'id' => $this->dryidd,
            'nom' => $this->drynom,
            'description' => $this->drydes,
            'etat' => $this->dryeta,
            'datecloture' => ($this->drydcl=='' ? '' : $this->drydcl->toFormattedDateString()),
            'created_at' => $this->drycre->toFormattedDateString()
        ];
    }
}
