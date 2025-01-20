<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoticeResource extends JsonResource
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
            'file' => $this->file,
            'file_size' => $this->fileSize(),
            'file_type' => $this->file_type,
            'body' => $this->body,
            'user_id' => $this->user_id,
            'status' => $this->status,
        ];
    }
}
