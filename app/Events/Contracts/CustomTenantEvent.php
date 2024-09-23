<?php

declare(strict_types=1);

namespace App\Events\Contracts;

use Illuminate\Queue\SerializesModels;
use Stancl\Tenancy\Contracts\Tenant;

abstract class CustomTenantEvent
{
    use SerializesModels;

    /** @var Tenant */
    public $tenant, $data;

    public function __construct(Tenant $tenant, array $data)
    {
        $this->tenant = $tenant;
        $this->data = $data;
    }
}