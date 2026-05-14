<?php

namespace App\Support\Tenancy;

use App\Models\Author;

class TenantManager
{
    protected ?Author $tenant = null;

    public function setTenant(?Author $author): void
    {
        $this->tenant = $author;
    }

    public function tenant(): ?Author
    {
        return $this->tenant;
    }

    public function id(): ?int
    {
        return $this->tenant?->id;
    }
}
