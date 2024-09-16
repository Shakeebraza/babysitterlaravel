<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KidResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $age = "";
        if ($this->year != ""){
            $age = $this->year;
        }
        if ($this->month != "") {
            if ($age == "") {
                $age = $this->month;
            } else {
                $age .= " & " .$this->month;
            }
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'month' => $this->month,
            'year' => $this->year,
            'age' => $age,
        ];
    }

}
