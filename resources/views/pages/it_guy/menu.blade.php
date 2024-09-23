{{--Manage Tenants--}}
<li class="nav-item">
    <a href="{{ route('tenants.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['tenants.index', 'tenants.edit']) ? 'active' : '' }}"><i class="material-symbols-rounded">manage_accounts</i> <span>Tenants</span></a>
</li>

{{--Manage Users--}}
<li class="nav-item">
    <a href="{{ route('users.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['users.index', 'users.show', 'users.edit']) ? 'active' : '' }}"><i class="material-symbols-rounded">group</i> <span> Users</span></a>
</li>

{{--Manage Settings--}}
<li class="nav-item">
    <a href="{{ route('settings_non_tenancy.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['settings_non_tenancy.index']) ? 'active' : '' }}"><i class="material-symbols-rounded">settings</i> <span>Settings</span></a>
</li>
