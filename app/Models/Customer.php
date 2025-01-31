<?php

namespace App\Models;

use App\Trait\Groupable;
use App\Trait\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Customer extends Model
{
    use HasFactory, Groupable, SoftDeletes, TenantTrait;

    protected $fillable = ['name',
        'photo',
        'mobile',
        'gender',
        'address',
        'due',
        'email',
        'status'];


    public function photoUrl()
    {


        if (isset($this->photo) && Storage::disk('public')->exists($this->photo)) {

            return Storage::disk('local')->url($this->photo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
