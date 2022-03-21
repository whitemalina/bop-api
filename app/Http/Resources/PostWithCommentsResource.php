<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostWithCommentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->master != null) {
            return [
                'id' => $this->id,
                'sp' => $this->sp,
                'cab' => $this->cab,
                'text' => $this->text,
                'user' => $this->user->name,
                'master' => $this->master->name,
                'status' => $this->status,
                'created_at' => $this->created_at,
            ];
        } else {
            return [
                'id' => $this->id,
                'sp' => $this->sp,
                'cab' => $this->cab,
                'text' => $this->text,
                'user' => $this->user->name,
                'status' => $this->status,
                'created_at' => $this->created_at,
            ];
        }
    }
}
