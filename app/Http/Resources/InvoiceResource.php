<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'discount_type' => $this->discount_type,
            'discount_amount' => $this->discount_amount,
            'total' => $this->total,
            'payment' => $this->payment,
            'due' => $this->due,
            'group' => $this->group,
            'customer' => $this->customer,
            'date' => $this->date,
            'user_id' => $this->user_id,
        ];
    }
}
