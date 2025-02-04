<div class="navbar login">
    <div class="mt-2 mr-5">
        <a href="{{ route('login') }}" class="d-inline-block">
            <h4 class="text-bold d-none d-md-block text-color-custom">{{ $settings->where('type', 'system_name')->value('description') }}</h4>
            <h4 class="text-bold d-block d-md-none text-color-custom">{{ $settings->where('type', 'system_title')->value('description') }}</h4>
        </a>
    </div>

    <a href="{{ route('home') }}" class="navbar-nav-link d-flex text-color-custom"><span class="mr-1">{{ "Home" }}</span><i class="material-symbols-rounded">home</i></a>
</div>