<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->imageUrl(),
            'gender' => $this->gender,
            'experience' => $this->experience,
            'occupation' => $this->occupation,
            'about' => $this->about,
            'mobile' => $this->mobile,
            'status' => $this->status,

        ];
    }
}
