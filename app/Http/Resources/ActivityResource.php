<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;


class ActivityResource extends JsonResource
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
            "id"   => $this->idLog,
            "logName" => $this->log_name,
            "description" => $this->description,
            "user" => $this->user,
            "fullname" => $this->firstname.' '.$this->lastname,
            "propriete" => json_decode($this->properties,1),
            "causertype" => $this->causer_type,
            "date" => Carbon::parse($this->dateLog)->format('d/m/Y H:i:s')
        ];
    }
}
