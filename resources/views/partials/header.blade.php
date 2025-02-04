<div id="page-header" class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4 class="d-flex" id="page-title"><i class="material-symbols-rounded mr-2">dashboard_customize</i> <span class="font-weight-semibold">@yield('page_title')</span></h4>
            <a href="javascript:;" class="header-elements-toggle text-default d-md-none"><i class="material-symbols-rounded">more_vert</i></a>
        </div>

        <div class="header-elements d-none">
            <div class="d-flex justify-content-center">

                <a href="javascript:;" data-toggle="popover" data-placement="bottom" data-html="true" data-content="
                    <small>
                        <span class='text-default'>School Management System</span><br />
                        <i><span class='text-default'>Source:</span> <a target='_blank' href='https://github.com/rayusamBoy/Laravel_tenancy_sms'>Laravel_tenancy_sms</a></i><br />
                    </small>" class="btn btn-link btn-float text-default">
                    <i class="material-symbols-rounded text-primary">info</i> <span class="d-none d-sm-block">About</span>
                </a>

                @if(Usr::tenancyInitilized())
                @if(Qs::userIsSuperAdmin())
                <a href="{{ route('analytics.index') }}" class="btn btn-link btn-float text-default"><i class="material-symbols-rounded text-primary">analytics</i> <span class="d-none d-sm-block">Analytics</span></a>
                @endif

                @if(Qs::userIsPTACL() or Qs::userIsStudent())
                <a href="{{ route('statistics.index') }}" class="btn btn-link btn-float text-default"><i class="material-symbols-rounded text-primary">query_stats</i><span class="d-none d-sm-block">Statistics</span></a>
                @endif
                
                {{-- <a href="javascript:;" class="btn btn-link btn-float text-default"><i class="material-symbols-rounded text-primary">calculate</i> <span class="d-none d-sm-block">Invoices</span></a> --}}
                <a href="{{ route('schedule.index') }}" class="btn btn-link btn-float text-default"><i class="material-symbols-rounded text-primary">calendar_month</i> <span class="d-none d-sm-block">Schedule</span></a>
                <a href="{{ Qs::userIsSuperAdmin() ? route('settings.index') : '' }}" class="btn btn-link btn-float text-default"><i class="material-symbols-rounded text-primary d-none d-sm-block">arrow_downward_alt</i> <span class="font-weight-semibold d-flex" id="current-session"><span class="d-none d-sm-block pr-1">Current Session:</span>{{ Qs::getCurrentSession() }}</span></a>
                @endif
            </div>
        </div>
    </div>

    <div class="breadcrumb-line header-elements-md-inline">
        <div class="d-flex">
            {{ Breadcrumbs::render() }}
            <a href="javascript:;" class="header-elements-toggle text-default d-md-none"><span class="material-symbols-rounded">more_vert</span></a>
        </div>

        <div class="header-elements d-none">
            <div class="breadcrumb justify-content-center" id="breadcrumb">
                <div class="breadcrumb-elements-item d-none d-md-flex p-0">
                    @include('pages.notifications.badge')
                </div>

                @if(Usr::tenancyInitilized())
                <a class="breadcrumb-elements-item d-none d-md-block" data-toggle="tooltip" title="Messages" href="/messages">@include('messenger.unread-count')</a>
                @endif

                <div class="breadcrumb-elements-item dropdown p-0">
                    <a href="javascript:;" class="breadcrumb-elements-item d-flex" data-toggle="dropdown">
                        <i class="material-symbols-rounded mr-1 mt-auto mb-auto">support</i>
                        Support
                        <i class="material-symbols-rounded m-auto">keyboard_arrow_down</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left">
                        <a target="_blank" href="#" class="dropdown-item"><i class="material-symbols-rounded">quick_reference</i> Knowledge Base</a>
                        <a target="_blank" href="#" class="dropdown-item"><i class="material-symbols-rounded">local_activity</i> Ticketing</a>
                    </div>
                </div>

                @if(Qs::userIsSuperAdmin())
                <div class="breadcrumb-elements-item p-0">
                    <a href="{{ route('bin') }}" @if(Qs::isCurrentRoute('bin')) class="breadcrumb-elements-item is-disabled d-flex" @else class="breadcrumb-elements-item d-flex" @endif>
                        <i class="material-symbols-rounded mr-1 mt-auto mb-auto">delete_forever</i>
                        Bin
                    </a>
                </div>
                @endif

                <div class="breadcrumb-elements-item dropdown p-0">
                    <a href="javascript:;" class="breadcrumb-elements-item d-flex" data-toggle="dropdown">
                        <i class="material-symbols-rounded mr-1 mt-auto mb-auto">settings</i>
                        Settings
                        <i class="material-symbols-rounded m-auto">keyboard_arrow_down</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('account_security.index') }}" class="dropdown-item"><i class="material-symbols-rounded">passkey</i> Account security</a>
                        <a href="javascript:;" class="dropdown-item"><i class="material-symbols-rounded">accessibility_new</i> Accessibility</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
