<?php

namespace App\Models;

use App\Trait\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceProduct extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceProductFactory> */
    use HasFactory, SoftDeletes, TenantTrait;

    protected $guarded = false;
}
