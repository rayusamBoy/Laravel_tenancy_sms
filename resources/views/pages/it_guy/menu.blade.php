{{--Manage Users--}}
<li class="nav-item">
    <a href="{{ route('users.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['users.index', 'users.show', 'users.edit']) ? 'active' : '' }}"><i class="material-symbols-rounded">group</i> <span> Users</span></a>
</li>

{{--Manage Tenants--}}
<li class="nav-item">
    <a href="{{ route('tenants.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['tenants.index', 'tenants.edit']) ? 'active' : '' }}"><i class="material-symbols-rounded">manage_accounts</i> <span>Tenants</span></a>
</li>

{{--Manage Settings--}}
<li class="nav-item">
    <a href="{{ route('settings_non_tenancy.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['settings_non_tenancy.index']) ? 'active' : '' }}"><i class="material-symbols-rounded">settings</i> <span>Settings</span></a>
</li>

{{--Support--}}
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['tickets.index_central']) ? 'nav-item-expanded nav-item-open' : '' }} ">
    <a href="javascript:;" class="nav-link"><i class="material-symbols-rounded">support</i> <span> Support</span></a>

    <ul class="nav nav-group-sub" data-submenu-title="Support">
        {{--Tickets--}}
        <li class="nav-item">
            <a href="{{ route('tickets.index_central') }}" class="nav-link {{ (Route::is('tickets.index_central')) ? 'active' : '' }}">Tickets</a>
        </li>
    </ul>
</li>
