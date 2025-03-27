<!-- Admin Modal -->
<div class="modal fade" id="admin-contact-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog admin modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-block text-center">
                <h3 class="modal-title" id="exampleModalLabel"><strong class="text-warning">{{ ("Admin Contacts") }}</strong></h3>
            </div>

            <div class="modal-body pt-0 pb-0">
                <div class="card contact m-0 border-0">
                    <div class="card-body p-0">
                        <h4 class="card-title"></h4>
                        <div class="card-text">
                            <h4 class="text-lightslategray"><strong>{{ ("Hey there, Salaam, welcome.") }}</strong></h4>

                            <ul>
                                <h5 class="text-lightslategray">{{ ("The Contacts.") }}</h5>
                                <li class="pb-1"><a href="{{ $settings->where('type', 'admin_whatsapp_link')->first()->description }}" target="_blank"><i class="material-symbols-rounded pb-2px">hdr_strong</i> <span class="align-middle">WhatsApp</span></a></li>
                                <li class="pb-1 break-all"><a href="mailto: {{ $settings->where('type', 'admin_email')->value('description') }}"><i class="material-symbols-rounded pb-2px">hdr_strong</i> {{ $settings->where('type', 'admin_email')->value('description') }}</a></li>
                            </ul>

                            @php
                            $admin_fb_link = $settings->where('type', 'admin_facebook_link')->value('description');
                            $admin_linkedin_link = $settings->where('type', 'admin_linkedin_link')->value('description');
                            $admin_github_link = $settings->where('type', 'admin_github_link')->value('description');
                            @endphp

                            @if($admin_fb_link !== null || $admin_linkedin_link !== null || $admin_github_link !== null)
                            <h5 class="text-lightslategray">{{ ("Orrr, let's get connected.") }}</h5>

                            <ul>
                                @if($admin_fb_link !== null)
                                <li class="pb-1"><a href="{{ $admin_fb_link }}" target="_blank"><i class="material-symbols-rounded pb-2px">hdr_strong</i> <span class="align-middle">Facebook</span></a></li>
                                @endif
                                @if($admin_linkedin_link !== null)
                                <li class="pb-1"><a href="{{ $admin_linkedin_link }}" target="_blank"><i class="material-symbols-rounded pb-2px">hdr_strong</i> <span class="align-middle">LinkedIn</span></a></li>
                                @endif
                                @if($admin_github_link !== null)
                                <li><a href="{{ $settings->where('type', 'admin_github_link')->first()->description }}" target="_blank"><i class="material-symbols-rounded pb-2px">hdr_strong</i> <span class="align-middle">Github</span></a></li>
                                @endif
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-lightslategray" aria-hidden="true">Close</span>
                </button>
            </div>
        </div>
    </div>
</div>
