<?php

namespace App\Jobs;

use App\Events\TenantDeletingStorageDir;
use App\Events\TenantStorageDirDeleted;
use App\Helpers\Qs;
use File;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class DeleteStorageDir implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $tenant;

    public function __construct(TenantWithDatabase $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        event(new TenantDeletingStorageDir($this->tenant, ['msg' => 'Deleting Tenant Storage Directory...', 'url' => request()->url()]));

        $path = Qs::getTenantStorageDir();
        if (is_dir($path))
            File::deleteDirectory($path); // Delete the tenant's storage folder.

        event(new TenantStorageDirDeleted($this->tenant, ['msg' => 'Tenant Storage Directory Deleted.', 'url' => request()->url()]));
    }
}
