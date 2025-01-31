<?php

namespace App\Models;

use App\Trait\Groupable;
use App\Trait\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, Groupable, SoftDeletes, TenantTrait;

    protected $guarded = false;

    public function imageUrl()
    {


        if (isset($this->image) && Storage::disk('public')->exists($this->image)) {

            return Storage::disk('local')->url($this->image);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function purchase_products()
    {
        return $this->hasMany(PurchaseProduct::class);
    }

    public function purchase_total()
    {
        return $this->purchase_products()->sum('qty');
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }
}
