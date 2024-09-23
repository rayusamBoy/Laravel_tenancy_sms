<?php

namespace App\Http\Controllers\ItGuy;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ArtisanCommandsController extends Controller
{
    public function index()
    {
        return view('pages.it_guy.artisan_commands.index');
    }

    // Optimze
    public function optimize()
    {
        $status = Artisan::call('optimize');
        if ($status === 0) {
            $ok = true;
            $message = 'Framework bootstrap files cached successfully.';
        } else {
            $ok = false;
            $message = 'Framework bootstrap files caching failed.';
        }

        return Qs::json($message, $ok);
    }

    // Optimze clear
    public function optimize_clear()
    {
        $status = Artisan::call('optimize:clear');
        if ($status === 0) {
            $ok = true;
            $message = 'Cached bootstrap files removed successfully.';
        } else {
            $ok = false;
            $message = 'Cached bootstrap files removing failed.';
        }

        return Qs::json($message, $ok);
    }

    // Route cache
    public function route_cache()
    {
        $status = Artisan::call('route:cache');
        if ($status === 0) {
            $ok = true;
            $message = 'Route cached successfully.';
        } else {
            $ok = false;
            $message = 'Route caching failed.';
        }

        return Qs::json($message, $ok);
    }

    // Route cache clear
    public function route_clear()
    {
        $status = Artisan::call('route:clear');
        if ($status === 0) {
            $ok = true;
            $message = 'Cached routes cleared successfully.';
        } else {
            $ok = false;
            $message = 'Cached route clearing failed.';
        }

        return Qs::json($message, $ok);
    }

    // Config cache
    public function config_cache()
    {
        $status = Artisan::call('config:cache');
        if ($status === 0) {
            $ok = true;
            $message = 'Configuration cached successfully.';
        } else {
            $ok = false;
            $message = 'Configuration caching failed.';
        }

        return Qs::json($message, $ok);
    }

    // Config cache clear
    public function config_clear()
    {
        $status = Artisan::call('config:clear');
        if ($status === 0) {
            $ok = true;
            $message = 'Configuration cleared successfully.';
        } else {
            $ok = false;
            $message = 'Configuration clearing failed.';
        }

        return Qs::json($message, $ok);
    }

    // Event cache
    public function event_cache()
    {
        $status = Artisan::call('event:cache');
        if ($status === 0) {
            $ok = true;
            $message = 'Events cached successfully.';
        } else {
            $ok = false;
            $message = 'Events caching failed.';
        }

        return Qs::json($message, $ok);
    }

    // Event cache clear
    public function event_clear()
    {
        $status = Artisan::call('event:clear');
        if ($status === 0) {
            $ok = true;
            $message = 'Events cleared successfully.';
        } else {
            $ok = false;
            $message = 'Events clearing failed.';
        }

        return Qs::json($message, $ok);
    }

    // View cache
    public function view_cache()
    {
        $status = Artisan::call('view:cache');
        if ($status === 0) {
            $ok = true;
            $message = 'Views cached successfully.';
        } else {
            $ok = false;
            $message = 'Views caching failed.';
        }

        return Qs::json($message, $ok);
    }

    // View cache clear
    public function view_clear()
    {
        $status = Artisan::call('view:clear');
        if ($status === 0) {
            $ok = true;
            $message = 'Views cleared successfully.';
        } else {
            $ok = false;
            $message = 'Views clearing failed.';
        }

        return Qs::json($message, $ok);
    }

    // Log viewer publish
    public function log_viewer_publish()
    {
        $status = Artisan::call('vendor:publish --tag=log-viewer-assets --force');
        if ($status === 0) {
            $ok = true;
            $message = 'Log viewer assets published successfully.';
        } else {
            $ok = false;
            $message = 'Log viewer assets publishing failed.';
        }

        return Qs::json($message, $ok);
    }

    // Clean activity log
    public function activity_log_clean()
    {
        $status = Artisan::call('activitylog:clean --force');
        if ($status === 0) {
            $ok = true;
            $message = 'All recorded activity older than ' . config('activitylog.delete_records_older_than_days') . ' deleted successfully.';
        } else {
            $ok = false;
            $message = 'Activity log cleaning failed.';
        }

        return Qs::json($message, $ok);
    }

    // Storage link
    public function storage_link()
    {
        $status = Artisan::call('storage:link');
        if ($status === 0) {
            $ok = true;
            $message = 'The symlink to storage folder created successfully.';
        } else {
            $ok = false;
            $message = 'Failed to create a symlink.';
        }

        return Qs::json($message, $ok);
    }

    // Unlink the storage symlink
    public function storage_unlink()
    {
        $status = Artisan::call('storage:unlink');
        if ($status === 0) {
            $ok = true;
            $message = 'The symlink to storage folder deleted successfully.';
        } else {
            $ok = false;
            $message = 'Failed to delete a symlink';
        }
        
        return Qs::json($message, $ok);
    }
}
