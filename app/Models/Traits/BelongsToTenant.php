<?php

namespace App\Models\Traits;

use App\Support\Tenancy\TenantManager;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            $manager = resolve(TenantManager::class);
            $tenantId = $manager->id();

            if ($tenantId) {
                $builder->where($builder->getModel()->getTable().'.author_id', $tenantId);
            }
        });
    }

    public function scopeForTenant(Builder $query, int $authorId): Builder
    {
        return $query->withoutGlobalScope('tenant')->where('author_id', $authorId);
    }
}
