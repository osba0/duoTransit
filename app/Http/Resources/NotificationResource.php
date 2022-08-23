<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $diff = floor((strtotime(date('Y-m-d H:i:s')) - strtotime($this->created_at)));
        $time = '';
        if(($diff / 60) < 60){
            $time = floor($diff/ 60).'mn';
        }
        elseif(($diff/ 3600) < 24){
            $time =  floor($diff / 3600).'h';
        }
        else{
            $time =  floor($diff / 86400).'j';
        }

         return [
            'id'        => $this->id,
            'data'      => $this->data,
            'read'      => $this->read_at != null ? true: false,
            'date'      => $time,
            'user'      => $this->data['user']['username']
           
        ];
    }
}
