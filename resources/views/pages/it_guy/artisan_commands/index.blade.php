@extends('layouts.master')
@section('page_title', 'Artisan Commands')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Artisan Commands</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info border-0 alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <span>
                    Here you can quickly run some artisan commands directly from this browser without shell access.
                </span>
            </div>
        </div>
    </div>

    <div class="card-body pl-4">
        <div class="row">
            {{-- Optimze --}}
            <div class="mr-2 mt-2">
                <button class="btn bg-primary" onclick="confirmOperation(this.id)" id="optimize" data-toggle="tooltip" title="Cache the framework bootstrap files">Optimze</button>
                <form method="post" id="item-confirm-operation-optimize" action="{{ route('artisan_command.optimize') }}" class="hidden page-block ajax-update">@csrf</form>
            </div>
            {{-- Optimze clear --}}
            <div class="mr-2 mt-2">
                <button class="btn bg-primary" onclick="confirmOperation(this.id)" id="optimize_clear" data-toggle="tooltip" title="Remove the cached bootstrap files">Optimze Clear</button>
                <form method="post" id="item-confirm-operation-optimize_clear" action="{{ route('artisan_command.optimize_clear') }}" class="hidden page-block ajax-update">@csrf</form>
            </div>
            {{-- Route cache --}}
            <div class="mr-2 mt-2">
                <button class="btn bg-dark" onclick="confirmOperation(this.id)" id="route_cache" data-toggle="tooltip" title="Create a route cache file for faster route registration">Routes Cache</button>
                <form method="post" id="item-confirm-operation-route_cache" action="{{ route('artisan_command.route_cache') }}" class="hidden ajax-update page-block">@csrf</form>
            </div>
            {{-- Route cached clear --}}
            <div class="mr-2 mt-2">
                <button class="btn bg-dark" onclick="confirmOperation(this.id)" id="route_clear" data-toggle="tooltip" title="Remove the route cache file">Routes Clear</button>
                <form method="post" id="item-confirm-operation-route_clear" action="{{ route('artisan_command.route_clear') }}" class="hidden ajax-update page-block">@csrf</form>
            </div>
            {{-- Config cache --}}
            <div class="mr-2 mt-2">
                <button class="btn bg-secondary" onclick="confirmOperation(this.id)" id="config_cache" data-toggle="tooltip" title="Create a cache file for faster configuration loading">Configs Cache</button>
                <form method="post" id="item-confirm-operation-config_cache" action="{{ route('artisan_command.config_cache') }}" class="hidden ajax-update page-block">@csrf</form>
            </div>
            {{-- Config cache clear --}}
            <div class="mr-2 mt-2">
                <button class="btn bg-secondary" onclick="confirmOperation(this.id)" id="config_clear" data-toggle="tooltip" title="Remove the configuration cache file">Configs Clear</button>
                <form method="post" id="item-confirm-operation-config_clear" action="{{ route('artisan_command.config_clear') }}" class="hidden ajax-update page-block">@csrf</form>
            </div>
            {{-- Event cache --}}
            <div class="mr-2 mt-2">
                <button class="btn bg-info" onclick="confirmOperation(this.id)" id="event_cache" data-toggle="tooltip" title="Discover and cache the application's events and listeners">Events Cache</button>
                <form method="post" id="item-confirm-operation-event_cache" action="{{ route('artisan_command.event_cache') }}" class="hidden ajax-update page-block">@csrf</form>
            </div>
            {{-- Event cache clear --}}
            <div class="mr-2 mt-2">
                <button class="btn bg-info" onclick="confirmOperation(this.id)" id="event_clear" data-toggle="tooltip" title="Clear all cached events and listeners">Events Clear</button>
                <form method="post" id="item-confirm-operation-event_clear" action="{{ route('artisan_command.event_clear') }}" class="hidden ajax-update page-block">@csrf</form>
            </div>
            {{-- View cache --}}
            <div class="mr-2 mt-2">
                <button class="btn bg-success" onclick="confirmOperation(this.id)" id="view_cache" data-toggle="tooltip" title="Compile all of the application's Blade templates">Views Cache</button>
                <form method="post" id="item-confirm-operation-view_cache" action="{{ route('artisan_command.view_cache') }}" class="hidden ajax-update page-block">@csrf</form>
            </div>
            {{-- View cache clear --}}
            <div class="mt-2 mr-2">
                <button class="btn bg-success" onclick="confirmOperation(this.id)" id="view_clear" data-toggle="tooltip" title="Clear all compiled view files">Views Clear</button>
                <form method="post" id="item-confirm-operation-view_clear" action="{{ route('artisan_command.view_clear') }}" class="hidden ajax-update page-block">@csrf</form>
            </div>
            {{-- Log viewer publish --}}
            <div class="mt-2 mr-2">
                <button class="btn bg-warning" onclick="confirmOperation(this.id)" id="log_viewer_publish" data-toggle="tooltip" title="Publish Log Viewer assets">Log Viewer Publish</button>
                <form method="post" id="item-confirm-operation-log_viewer_publish" action="{{ route('artisan_command.log_viewer_publish') }}" class="hidden ajax-update page-block">@csrf</form>
            </div>
            {{-- Activity Log clean --}}
            <div class="mt-2 mr-2">
                <button class="btn bg-danger float-right" onclick="confirmOperation(this.id)" id="activity_log_clean" data-toggle="tooltip" title="The deletion of all recorded activity that is older than {{ config('activitylog.delete_records_older_than_days') }} days">Activity Log Clean</button>
                <form method="post" id="item-confirm-operation-activity_log_clean" action="{{ route('logs.activity_log_clean') }}" class="hidden ajax-update page-block">@csrf</form>
            </div>
            {{-- Storage link --}}
            <div class="mt-2 mr-2">
                <button class="btn bg-indigo float-right" onclick="confirmOperation(this.id)" id="storage_link" data-toggle="tooltip" title="Create a symlink in public folder to storage folder.">Storage Link</button>
                <form method="post" id="item-confirm-operation-storage_link" action="{{ route('logs.storage_link') }}" class="hidden ajax-update page-block">@csrf</form>
            </div>
             {{-- Storage unlink --}}
            <div class="mt-2 mr-2">
                <button class="btn bg-indigo-400 float-right" onclick="confirmOperation(this.id)" id="storage_unlink" data-toggle="tooltip" title="Delete created symlink to storage folder if any.">Storage Unlink</button>
                <form method="post" id="item-confirm-operation-storage_unlink" action="{{ route('logs.storage_unlink') }}" class="hidden ajax-update page-block">@csrf</form>
            </div>
        </div>
    </div>
</div>

@endsection
