@extends('layouts.master')
@section('page_title', 'My Dashboard')

@section('content')

<div class="card-columns">
    <!-- Users -->
    <div class="card card-body bg-purple-300 has-bg-image">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-4">
                        <h3 class="mb-0 counter">{{ $users->count() }}</h3>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                M:
                                {{ $users->where('user.gender', 'Male')->count() }}
                            </div>
                            <div class="col-6">
                                F:
                                {{ $users->where('user.gender', 'Female')->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span class="text-capitalize font-size-xs">Users</span>
                    </div>
                </div>
            </div>
            <div class="ml-3 align-self-center d-none d-md-block">
                <i class="material-symbols-rounded symbol-3x opacity-75">group</i>
            </div>
        </div>
    </div>

    <!-- Tenants -->
    <div class="card card-body bg-success-400 has-bg-image">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-4">
                        <h3 class="mb-0 counter">{{ $tenants->where('account_status', 'active')->count() }}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span class="text-capitalize font-size-xs">Active Tenants</span>
                    </div>
                </div>
            </div>
            <div class="ml-3 align-self-center d-none d-md-block">
                <i class="material-symbols-rounded symbol-3x opacity-75">manage_accounts</i>
            </div>
        </div>
    </div>

    <div class="card card-body bg-danger-400 has-bg-image">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-4">
                        <h3 class="mb-0 counter">{{ $tenants->where('account_status', 'inactive')->count() }}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span class="text-capitalize font-size-xs">In-active Tenants</span>
                    </div>
                </div>
            </div>
            <div class="ml-3 align-self-center d-none d-md-block">
                <i class="material-symbols-rounded symbol-3x opacity-75">group</i>
            </div>
        </div>
    </div>

    <div class="card card-body bg-warning-400 has-bg-image">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-4">
                        <h3 class="mb-0 counter">{{ $tenants->where('account_status', 'suspended')->count() }}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span class="text-capitalize font-size-xs">Suspended Tenants</span>
                    </div>
                </div>
            </div>
            <div class="ml-3 align-self-center d-none d-md-block">
                <i class="material-symbols-rounded symbol-3x opacity-75">group</i>
            </div>
        </div>
    </div>

    <div class="card card-body bg-dark has-bg-image">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-4">
                        <h3 class="mb-0 counter">{{ $tenants->where('account_status', 'blocked')->count() }}</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span class="text-capitalize font-size-xs">Blocked Tenants</span>
                    </div>
                </div>
            </div>
            <div class="ml-3 align-self-center d-none d-md-block">
                <i class="material-symbols-rounded symbol-3x opacity-75">block</i>
            </div>
        </div>
    </div>
</div>

@endsection
