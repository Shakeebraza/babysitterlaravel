<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupMemberResource extends JsonResource
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
            'member_email' => $this->member_email,
            'status' => $this->status,
            'group' => new GroupResource($this->whenLoaded("Group")),
            'member' => new UserResource($this->whenLoaded('Member'))
        ];
    }
}
