<?php

namespace App\Trait;

use App\Models\Scopes\TenantScope;
use App\Models\Tenant;

trait TenantTrait
{
    protected static function bootTenantTrait(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (session()->has('tenantId')) {
                $model->tenant_id = session()->get('tenantId');
            }

            if (auth()->id()) {
                $model->user_id = auth()->id();
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
