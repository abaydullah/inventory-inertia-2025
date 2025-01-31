<?php

namespace App\Models;

use App\Trait\Groupable;
use App\Trait\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Supplier extends Model
{
    use HasFactory, Groupable, SoftDeletes, TenantTrait;

    protected $guarded = false;

    public function photoUrl()
    {


        if ($this->photo && Storage::disk('public')->exists($this->photo)) {

            return Storage::disk('local')->url($this->photo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->photo);
    }
}
