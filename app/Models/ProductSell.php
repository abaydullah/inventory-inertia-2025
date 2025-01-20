<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSell extends Model
{
    /** @use HasFactory<\Database\Factories\ProductSellFactory> */
    use HasFactory;

    protected $guarded = false;
}
