<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'group_id' => $this->group_id,
            'supplier' => $this->supplier,
            'supplier_id' => $this->supplier_id,
            'date' => $this->date,
            'user_id' => $this->user,
            'withdraws' => $this->withdraws,

            'purchase_products' => PurchaseProductResource::collection($this->purchaseProducts)
        ];
    }
}
