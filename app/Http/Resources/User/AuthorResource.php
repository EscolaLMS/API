<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->getKey(),
            'name' => $this->user->name,
            'slug' => $this->slug,
            'image' => $this->image,
            'description' => $this->biography,
            'email_verified' => $this->email_verified,
            'contact' => [
                'email' => $this->contact_email,
                'telephone' => $this->telephone,
                'mobile' => $this->mobile,
            ],
            'social_media' => [
                'facebook' => $this->link_facebook,
                'linkedin' => $this->link_linkedin,
                'twitter' => $this->link_twitter
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
