<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_filter([
            'id' => $this->id,
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'age' => $this->age,
            'gender' => $this->gender,
            'country' => $this->country,
            'city' => $this->city,
            'street' => $this->street,
            'postcode' => $this->postcode,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'onboarding_completed' => $this->onboarding_completed,
            'email_verified' => $this->email_verified,
            'interests' => CategoryResource::collection($this->interests()->get()),
            'avatar' => $this->avatar_url,
        ], function ($el) {
            return !is_null($el);
        });
    }
}
