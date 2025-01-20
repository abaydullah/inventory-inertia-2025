<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
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
            'company_name' => $this->company_name,
            'photo_url' => $this->photoUrl(),
            'proprietor_name' => $this->proprietor_name,
            'mobile' => $this->mobile,
            'address' => $this->address,
            'due' => $this->due,
            'email' => $this->email,
            'group_id' => $this->groups->select('id','name'),

        ];
    }
}
