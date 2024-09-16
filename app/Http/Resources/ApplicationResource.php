<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
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
            'id' => $this->id,
            'request_status' => $this->request_status,
            'status' => $this->status,
            'description' => $this->description,
            'payment_type' => $this->payment_type,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->whenLoaded('User'))
        ];
    }

}
