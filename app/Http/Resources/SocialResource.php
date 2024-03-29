<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SocialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'is_approved_by_post_author' => $this->is_approved_by_post_author,
            'is_approved_by_guest_user' => $this->is_approved_by_guest_user,
            'description' => $this->description,
            'tag_id' => $this->tag_id,
            'post_id' => $this->post_id,
            'author' => new UserResource($this->whenLoaded('author'))

        ];
    }
}
