<?php

namespace App\Models;

use App\Trait\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseProduct extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseProductFactory> */
    use HasFactory, SoftDeletes, TenantTrait;

    protected $guarded = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function product_stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function total_buy_price()
    {
        $buy_price = 0;
        $this->product_stocks()->each(function ($product_stock) use (&$buy_price) {
            $buy_price += $product_stock->buy_price * $product_stock->qty;
        });

        return $buy_price;
    }

    public function total_sell_price()
    {
        $sell_price = 0;
        $this->product_stocks()->each(function ($product_stock) use (&$sell_price) {
            $sell_price += $product_stock->sell_price * $product_stock->qty;
        });

        return $sell_price;
    }
}
