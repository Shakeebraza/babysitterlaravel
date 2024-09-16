<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{
    protected $resourceRequester = null;

    /**
     * @param User $resourceRequester
     */
    public function setResourceRequester(User $resourceRequester): RequestResource
    {
        $this->resourceRequester = $resourceRequester;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //$isOwner = auth()->check() && $this->user_id === auth()->id();
        $isOwner = $this->resourceRequester && $this->user_id === $this->resourceRequester->id;

        $address=$this->address;
        if (!empty($this->address) && !$isOwner){
            $address = $this->zip . " " . $this->city;
        }

        //workaround for old versions
        $visibility = ($this->public_visibility) ? 'public' : 'group';

        $response = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('User')),
            'awarded' => $this->awarded, // an application was chosen
            'reawarded' => $this->awarded, // an application was chosen
            'title' => $this->title,
            'description' => $this->description,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'visibility' => $visibility,
            'group_visibility' => $this->group_visibility,
            'public_visibility' => $this->public_visibility,
            'address' => $address,
            'city' => $this->city,
            'country_code' => $this->country_code,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'status' => $this->status,
            'kids' => KidResource::collection($this->whenLoaded('Kids')),
        ];
        if ($this->resourceRequester != null) {
            $myApplicationStatus = $this->AllAcceptedRequest()->where('user_id', $this->resourceRequester->id)->first();
            $response['requestAccepted'] = $myApplicationStatus ? $myApplicationStatus->status : 0;
        }
        if ($isOwner) {
            $response['address_type'] = $this->address_type;
            $response['zip'] = $this->zip;
            $response['street'] = $this->street;
            $response['groups'] = GroupResource::collection($this->whenLoaded('Groups'));
            $response['reawardedRequest'] = ApplicationResource::collection($this->whenLoaded('ConfirmedRequest')); //chosen application
            $response['accepted_request'] = ApplicationResource::collection($this->whenLoaded('AcceptedRequest')); //applies
            $response['max_applies'] = $this->getMaxApplies();
            $response['dismissed_count'] = $this->dismissedRequestsFromGroupMembers()->count();
        }
        return $response;
    }

}
