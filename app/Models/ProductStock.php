<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function sells()
    {
        return $this->hasMany(ProductSell::class, 'product_id', 'product_id');
    }

    public function stockQty()
    {
        $qty = $this->sells()->where('color_id', $this->color_id)->where('size_id', $this->size_id)->where('unit_id', $this->unit_id)->sum('qty');
        return $this->qty - $qty;
    }
}
