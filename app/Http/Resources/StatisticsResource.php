<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatisticsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'confirmed' => $this['confirmed'],
            'recovered' => $this['recovered'],
            'critical' => $this['critical'],
            'deaths' => $this['deaths'],
            'created_at' => $this['created_at'],
        ];
    }
}
