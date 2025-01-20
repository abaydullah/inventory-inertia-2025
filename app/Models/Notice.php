<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    public function fileSize()
    {
        if ($this->file_size){

        return number_format($this->file_size/1e+6,2).' MB';
        }
        return;
    }

    public function ScopePublished(){
        return $this->where('status',1);
    }
}
