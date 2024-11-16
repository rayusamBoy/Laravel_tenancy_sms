<?php

namespace App\Jobs;

use App\Events\TenantDeletingIDCardsThemeDir;
use App\Events\TenantIDCardsThemeDirDeleted;
use App\Helpers\Qs;
use File;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class DeleteIDCardsThemeDir implements ShouldQueue
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
        event(new TenantDeletingIDCardsThemeDir($this->tenant, ['msg' => 'Deleting Tenant ID Cards Theme Directory...', 'url' => request()->url()]));

        $dir = Qs::getTenancyAwareIDCardsThemeDir();
        if (is_dir($dir))
            File::deleteDirectory($dir); // Delete tenant's students id cards theme file's specific dir.

        event(new TenantIDCardsThemeDirDeleted($this->tenant, ['msg' => 'Tenant ID Cards Theme Directory Deleted.', 'url' => request()->url()]));
    }
}
