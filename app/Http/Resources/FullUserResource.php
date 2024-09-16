<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FullUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $image = !empty($this->image) ? url($this->image) : '';

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'surname' => $this->surname,
            'identify' => $this->identify,
            'profile_type' => $this->profile_type,
            'language' => $this->language,
            'image' => $image,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth,
            'address' => $this->address,
            'street' => $this->street,
            'zip' => $this->zip,
            'city' => $this->city,
            'country_code' => $this->country_code,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'aboutme' => $this->aboutme,
        ];
    }

}
