<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function bannerUrl(){


        if (Storage::disk('public')->exists($this->banner)){

            return Storage::disk('local')->url($this->banner);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->title);
    }
}
