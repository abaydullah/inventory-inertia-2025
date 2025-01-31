<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'photo_url' => $this->photoUrl(),
            'gender' => $this->gender,
            'address' => $this->address,
            'due' => $this->due,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'status' => $this->status,
            'group_id' => $this->groups->select('id', 'name'),

        ];
    }
}
