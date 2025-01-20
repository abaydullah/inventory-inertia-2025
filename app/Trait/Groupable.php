<?php

namespace App\Trait;

use App\Models\Group;

trait Groupable
{
    public function groups()
    {
        return $this->morphToMany(Group::class, 'groupable');
    }
}
