<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductStockResource extends JsonResource
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
            'product_id' => $this->product_id,
            'buy_price' => $this->buy_price,
            'sell_price' => $this->sell_price,
            'color_id' => $this->color_id,
            'color' => $this->color,
            'size_id' => $this->size_id,
            'size' => $this->size,
            'name' => $this->size->name . ' - ' . $this->color->name . ' - ' . $this->unit->name . ' (' . $this->unit_size . ')',
            'unit_id' => $this->unit_id,
            'unit' => $this->unit,
            'unit_size' => $this->unit_size,
            'qty' => $this->stockQty(),
        ];
    }
}
