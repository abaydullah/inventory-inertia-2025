<?php

namespace App\Models;

use App\Trait\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSell extends Model
{
    /** @use HasFactory<\Database\Factories\ProductSellFactory> */
    use HasFactory, TenantTrait;

    protected $guarded = false;
}
