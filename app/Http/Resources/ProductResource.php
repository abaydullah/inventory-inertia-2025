<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'description' => $this->description,
            'buy_price' => $this->buy_price,
            'sell_price' => $this->sell_price,
            'barcode' => $this->barcode,
            'warning_stock' => $this->warning_stock,
            'opening_stock' => $this->opening_stock,
            'category_id' => $this->category_id,
            'category' => $this->category,
            'group_id' => $this->group_id,
            'status' => $this->status,
            'stock' => $this->purchase_total(),
            'stocks' => ProductStockResource::collection($this->stocks)
        ];
    }
}
