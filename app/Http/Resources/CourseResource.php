<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'fee' => $this->fee,
            'fee_type' => $this->fee_type,
            'course_type' => $this->course_type,
            'type' => $this->type,
            'image_url' => $this->imageUrl(),
            'instructors' => $this->instructors->select('id','name'),
            'body' => $this->body,
            'status' => $this->status,
        ];
    }


}
