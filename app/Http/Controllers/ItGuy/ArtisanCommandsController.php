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
        $command_name = 'optimize';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    // Optimze clear
    public function optimize_clear()
    {
        $command_name = 'optimize:clear';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    // Route cache
    public function route_cache()
    {
        $command_name = 'route:cache';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    // Route cache clear
    public function route_clear()
    {
        $command_name = 'route:clear';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    // Config cache
    public function config_cache()
    {
        $command_name = 'config:cache';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    // Config cache clear
    public function config_clear()
    {
        $command_name = 'config:clear';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    // Event cache
    public function event_cache()
    {
        $command_name = 'event:cache';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    // Event cache clear
    public function event_clear()
    {
        $command_name = 'event:clear';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    // View cache
    public function view_cache()
    {
        $command_name = 'view:cache';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    // View cache clear
    public function view_clear()
    {
        $command_name = 'view:clear';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    // Log viewer publish
    public function log_viewer_publish()
    {
        $command_name = 'vendor:publish --tag=log-viewer-assets --force';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    // Clean activity log
    public function activity_log_clean()
    {
        $command_name = 'activitylog:clean --force';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    // Storage link
    public function storage_link()
    {
        $command_name = 'storage:link';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    // Unlink the storage symlink
    public function storage_unlink()
    {
        $command_name = 'storage:unlink';
        $response_name = __FUNCTION__;
        $status = $this->handle_command($command_name, $response_name);

        return $status;
    }

    /**
     * Handle the given command and return appropriate response
     * @param mixed $command_name
     * @param mixed $response_name
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    private function handle_command($command_name, $response_name)
    {
        // Some commands can be prohibited for some reasons; 
        // eg., for route:cache; routes with the same name cannot be cached (routes that are used in both tenant and central domains).
        $prohibited_commands_name = ['route:cache', 'optimize'];
        $response_name = str_replace("_", " ", $response_name);

        if (in_array($command_name, $prohibited_commands_name))
            return Qs::json("The $response_name is command prohibited.", false);

        $status = Artisan::call($command_name);
        if ($status === 0)
            return Qs::json("The $response_name command run successfully.", true);

        return Qs::json("The $response_name command failed to run.", false);
    }
}
