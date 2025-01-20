<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function suppliers()
    {
        return $this->morphedByMany(Supplier::class, 'groupable');
    }
    public function customers()
    {
        return $this->morphedByMany(Customer::class, 'groupable');
    }
    public function products()
    {
        return $this->morphedByMany(Product::class, 'groupable');
    }
    public function staffs()
    {
        return $this->morphedByMany(Staff::class, 'groupable');
    }
}
