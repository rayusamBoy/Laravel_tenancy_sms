<?php

namespace App\Listeners;

use App\Events\TenantDeletingIDCardsThemeDir;
use App\Events\TenantDeletingStorageDir;
use App\Events\TenantIDCardsThemeDirDeleted;
use App\Events\TenantStorageDirDeleted;
use App\Notifications\SendTenantEvent;
use Stancl\Tenancy\Events\CreatingDatabase;
use Stancl\Tenancy\Events\CreatingDomain;
use Stancl\Tenancy\Events\CreatingTenant;
use Stancl\Tenancy\Events\DatabaseCreated;
use Stancl\Tenancy\Events\DatabaseDeleted;
use Stancl\Tenancy\Events\DatabaseMigrated;
use Stancl\Tenancy\Events\DatabaseSeeded;
use Stancl\Tenancy\Events\DeletingDomain;
use Stancl\Tenancy\Events\DeletingTenant;
use Stancl\Tenancy\Events\DomainCreated;
use Stancl\Tenancy\Events\DomainDeleted;
use Stancl\Tenancy\Events\MigratingDatabase;
use Stancl\Tenancy\Events\SeedingDatabase;
use Stancl\Tenancy\Events\TenantCreated;
use Stancl\Tenancy\Events\TenantDeleted;
use Illuminate\Support\Facades\Notification;
use Illuminate\Events\Dispatcher;

class TenantEventSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            TenantDeletingIDCardsThemeDir::class => 'handleDeletingThemeDir',
            TenantDeletingStorageDir::class => 'handleDeletingStorageDir',
            TenantIDCardsThemeDirDeleted::class => 'handleThemeDirDeleted',
            TenantStorageDirDeleted::class => 'handleStorageDirDeleted',
            CreatingTenant::class => 'handleCreatingTenant',
            TenantCreated::class => 'handleTenantCreated',
            DeletingTenant::class => 'handleDeletingTenant',
            TenantDeleted::class => 'handleTenantDeleted',
            CreatingDomain::class => 'handleCreatingDomain',
            DomainCreated::class => 'handleDomainCreated',
            DeletingDomain::class => 'handleDeletingDomain',
            DomainDeleted::class => 'handleDomainDeleted',
            CreatingDatabase::class => 'handleCreatingDB',
            DatabaseCreated::class => 'handleDBCreated',
            MigratingDatabase::class => 'handleDBMigration',
            DatabaseMigrated::class => 'handleDBMigrated',
            SeedingDatabase::class => 'handleSeedingDB',
            DatabaseSeeded::class => 'handleDBSeeded',
            DatabaseDeleted::class => 'handleDBDeleted',
        ];
    }

    public function handleDeletingThemeDir(TenantDeletingIDCardsThemeDir $event): void
    {
        $event->data = ['msg' => 'Deleting tenant ID Cards theme dir...', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleDeletingStorageDir(TenantDeletingStorageDir $event): void
    {
        $event->data = ['msg' => 'Deleting tenant storage dir...', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleThemeDirDeleted(TenantIDCardsThemeDirDeleted $event): void
    {
        $event->data = ['msg' => 'Tenant ID Cards theme deleted.', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleStorageDirDeleted(TenantStorageDirDeleted $event): void
    {
        $event->data = ['msg' => 'Tenant storage dir deleted.', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleCreatingTenant(CreatingTenant $event): void
    {
        $event->data = ['msg' => 'Creating tenant...', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleTenantCreated(TenantCreated $event): void
    {
        $event->data = ['msg' => 'Tenant created.', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleDeletingTenant(DeletingTenant $event): void
    {
        $event->data = ['msg' => 'Deleting tenant...', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleTenantDeleted(TenantDeleted $event): void
    {
        $event->data = ['msg' => 'Tenant deleted.', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleDBDeleted(DatabaseDeleted $event): void
    {
        $event->data = ['msg' => 'Tenant database deleted.', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleCreatingDomain(CreatingDomain $event): void
    {
        $event->data = ['msg' => 'Creating tenant domain...', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleDomainCreated(DomainCreated $event): void
    {
        $event->data = ['msg' => 'Tenant domain created.', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleDeletingDomain(DeletingDomain $event): void
    {
        $event->data = ['msg' => 'Deleting tenant domain...', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleDomainDeleted(DomainDeleted $event): void
    {
        $event->data = ['msg' => 'Tenant domain deleted.', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleCreatingDB(CreatingDatabase $event): void
    {
        $event->data = ['msg' => 'Creating tenant database...', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleDBMigration(MigratingDatabase $event): void
    {
        $event->data = ['msg' => 'Migrating tenant database...', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleDBMigrated(DatabaseMigrated $event): void
    {
        $event->data = ['msg' => 'Tenant database migrated.', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleSeedingDB(SeedingDatabase $event): void
    {
        $event->data = ['msg' => 'Seeding tenant database...', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleDBSeeded(DatabaseSeeded $event): void
    {
        $event->data = ['msg' => 'Tenant database seeded.', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }

    public function handleDBCreated(DatabaseCreated $event): void
    {
        $event->data = ['msg' => 'Tenant database created.', 'url' => route('tenants.index')];
        Notification::sendNow(auth()->user(), new SendTenantEvent($event));
    }
}