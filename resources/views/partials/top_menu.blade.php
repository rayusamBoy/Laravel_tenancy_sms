<div class="navbar navbar-expand-md">
    <div class="mt-2 mr-md-5">
        <a href="{{ route('dashboard') }}" class="d-inline-block">
            <h4 class="text-bold d-none d-md-block">{{ Qs::getSystemName() }}</h4>
            <h4 class="text-bold d-block d-md-none">{{ Qs::getStringAbbreviation() }}</h4>
        </a>
    </div>

    <div class="d-flex d-md-none">
        <a class="m-auto pr-3" data-toggle="tooltip" title="Notifications" href="javascript:;">@include('pages.notifications.status')</a>

        @if(Usr::tenancyInitilized())
        <a class="m-auto pr-3" data-toggle="tooltip" title="Messages" href="/messages">@include('messenger.unread-count')</a>
        @endif
        
        <a href="javascript:;" class="m-auto text-default refresh mobile"><i class="material-symbols-rounded mt-auto mb-auto">refresh</i></a>

        {{-- Toggle color modes (Modes display in modal - included in layouts->master) --}}
        <a href="javascript:;" class="m-auto text-default" data-toggle="modal" data-target="#color-modes"><i class="material-symbols-rounded mt-auto mb-auto color-modes mobile">palette</i></a>

        <button class="navbar-toggler pl-3 text-default" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="material-symbols-rounded align-middle">person_alert</i>
        </button>

        <button class="navbar-toggler sidebar-mobile-main-toggle text-default" type="button">
            <i class="material-symbols-rounded align-middle">menu</i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        {{-- If side bar is not minimzed by the user, show this element --}}
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="{{ (auth()->user()->sidebar_minimized) ? 'navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block disabled pointer-events-none' : 'navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block' }}">
                    <i class="material-symbols-rounded align-middle">menu</i>
                </a>
            </li>
        </ul>

        <li class="d-none d-md-block full-screen-handle">
            <a href="#" title="Request Full Screen" class="material-symbols-rounded d-flex navbar-nav-link">fullscreen</a>
        </li>

        <span class="navbar-text ml-md-3 mr-md-auto"></span>

        <a href="javascript:;" class="navbar-nav-link d-none d-md-block refresh"><i class="material-symbols-rounded mt-auto mb-auto mr-1">refresh</i>Refresh</a>

        {{-- Toggle color modes (Modes display in modal - included in layouts->master) --}}
        <a href="javascript:;" class="navbar-nav-link d-none d-md-block" data-toggle="modal" data-target="#color-modes"><i class="material-symbols-rounded mr-1 mt-auto mb-auto">palette</i>Color Modes</a>

        <ul class="navbar-nav">
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link" data-toggle="dropdown">
                    <img style="width: 38px; height: 38px;" src="{{ Usr::getTenantAwarePhoto(auth()->user()->photo) }}" class="rounded-circle" alt="photo">
                    <span>{{ auth()->user()->name }}</span><i class="material-symbols-rounded ml-1">keyboard_arrow_down</i>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ Qs::userIsStudent() ? route('students.show', Qs::hash(Qs::findStudentRecord(auth()->user()->id)->id)) : route('users.show', Qs::hash(auth()->user()->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">person_check</i> My profile</a>
                    
                    @if(Qs::userIsHead() || !Usr::tenancyInitilized())
                    <div class="dropdown-divider"></div>

                    @if(!Usr::tenancyInitilized() && Qs::userIsItGuy())
                    <a href="/log-viewer" class="dropdown-item"><i class="material-symbols-rounded">description</i> View System Log</a>
                    <a href="{{ route('artisan_commands.index') }}" class="dropdown-item"><i class="material-symbols-rounded">terminal</i> Artisan Commands</a>

                    @if(Qs::userIsHead())
                    <a href="{{ route('logs.index') }}" class="dropdown-item"><i class="material-symbols-rounded">browse_activity</i> Manage Activity Log</a>
                    @endif
         
                    @else
                    <a href="{{ route('tenancy_logs.index') }}" class="dropdown-item"><i class="material-symbols-rounded">browse_activity</i> Manage Activity Log</a>
                    @endif

                    @endif

                    <div class="dropdown-divider"></div>
                    <a href="{{ route('my_account') }}" class="dropdown-item"><i class="material-symbols-rounded">manage_accounts</i> Account settings</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item"><i class="material-symbols-rounded">logout</i> Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                </div>
            </li>
        </ul>
    </div>
</div>
