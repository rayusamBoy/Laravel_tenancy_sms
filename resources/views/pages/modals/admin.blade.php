<!-- Admin Modal -->
<div class="modal fade" id="admin-contact-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog admin modal-dialog-centered">
        <div class="modal-content border-1">
            <div class="modal-header d-block text-center">
                <h3 class="modal-title" id="exampleModalLabel"><strong class="text-green">{{ ("Admin Contacts") }}</strong></h3>
            </div>

            <div class="modal-body pt-0 pb-0">
                <div class="card contact m-0">
                    <div class="card-body p-0">
                        <h4 class="card-title"></h4>
                        <div class="card-text">
                            <h6 class="text-white">{{ ("Hey there, Salaam, welcome.") }}</h6>

                            <ul>
                                <h4 class="text-white">{{ ("The Contacts.") }}</h4>
                                <li class="pb-1"><a href="{{ $settings->where('type', 'admin_whatsapp_link')->first()->description }}" target="_blank"> WhatsApp</a></li>
                                <li class="pb-1 break-all"><a href="mailto: {{ $settings->where('type', 'admin_email')->first()->description }}"> {{ $settings->where('type', 'admin_email')->first()->description }}</a></li>
                            </ul>

                            @if($settings->where('type', 'admin_facebook_link')->first()->description != "" || $settings->where('type', 'admin_linkedin_link')->first()->description != "" || $settings->where('type', 'admin_github_link')->first()->description != "")
                            <h4 class="text-lightslategray"><strong>{{ ("Orrr, let's get connected.") }}</strong></h4>

                            <ul>
                                @if($settings->where('type', 'admin_facebook_link')->first()->description != "")
                                <li class="pb-1"><a href="{{ $settings->where('type', 'admin_facebook_link')->first()->description }}" target="_blank"> Facebook</a></li>
                                @endif
                                @if($settings->where('type', 'admin_linkedin_link')->first()->description != "")
                                <li class="pb-1"><a href="{{ $settings->where('type', 'admin_linkedin_link')->first()->description }}" target="_blank"> LinkedIn</a></li>
                                @endif
                                @if($settings->where('type', 'admin_github_link')->first()->description != "")
                                <li><a href="{{ $settings->where('type', 'admin_github_link')->first()->description }}" target="_blank"> Github</a></li>
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