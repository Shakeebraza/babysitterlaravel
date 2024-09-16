<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $approximateAddress="";
        if (!empty($this->address)){
            $approximateAddress = $this->zip . " " . $this->city;
        }

        $image = !empty($this->image) ? url($this->image) : '';

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'surname' => $this->surname,
            'identify' => $this->identify,
            'image' => $image,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth,
            'address' => $approximateAddress,
            'city' => $this->city,
            'aboutme' => $this->aboutme,
        ];
    }

}
