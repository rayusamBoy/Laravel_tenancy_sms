<div class="navbar navbar-expand-lg login">
    <span class="navbar-text d-flex">
        <span class="text-color-custom">&copy; {{ date('Y') }}, &nbsp</span>
        <a href="#" class="text-color-custom">
            <span class="d-none d-md-block">{{ config('app.name') }}.</span>
            <span class="d-block d-md-none">{{ Qs::getStringAbbreviation(config('app.name')) }}</span>
        </a>
    </span>

    <ul class="navbar-nav ml-lg-auto">
        <li class="nav-item"><a href="{{ route('privacy_policy') }}" class="navbar-nav-link text-color-custom" target="_blank"><i class="material-symbols-rounded mr-2">policy</i><span class="d-none d-md-block">{{ 'Privacy Policy' }} </span></a></li>
        <li class="nav-item"><a href="{{ route('terms_of_use') }}" class="navbar-nav-link text-color-custom" target="_blank"><i class="material-symbols-rounded mr-2">gavel</i><span class="d-none d-md-block">{{ 'Terms of Use' }} </span></a></li>
        <li class="nav-item"><a class="navbar-nav-link text-color-custom" id="contact-link" data-toggle="modal" data-target="#admin-contact-modal"><i class="material-symbols-rounded mr-2">call</i> <span class="d-none d-md-block"> {{ 'Contact Admin' }} </span></a></li>
    </ul>
</div>

<script type="text/javascript">
        $("#exam-announce").marquee({
            direction: 'left', 
            pauseOnHover: true, 
            speed: 100
        });
</script>
