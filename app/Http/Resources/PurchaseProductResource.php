<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'purchase_product_id' => $this->id,
            'id' => $this->product->id,
            'name' => $this->product->name,
            'image_url' => $this->product->imageUrl(),
            'description' => $this->product->description,
            'buy_price' => $this->product->buy_price,
            'sell_price' => $this->product->sell_price,
            'barcode' => $this->product->barcode,
            'warning_stock' => $this->product->warning_stock,
            'opening_stock' => $this->product->opening_stock,
            'category_id' => $this->product->category_id,
            'category' => $this->product->category,
            'group_id' => $this->product->group_id,
            'status' => $this->product->status,
            'qty' => $this->qty,
            'total_buy_price' => $this->total_buy_price() ? $this->total_buy_price() : $this->total_buy_price,
            'total_sell_price' => $this->total_sell_price() ? $this->total_sell_price() : $this->total_sell_price,
            'stocks' => ProductStockResource::collection($this->product_stocks),

        ];
    }
}
