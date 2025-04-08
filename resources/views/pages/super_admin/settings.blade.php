@extends('layouts.master')

@section('page_title', 'Manage System Settings')

@section('content')

@use('Database\Seeders\NonTenancySettingsTableSeeder')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title font-weight-semibold">Update System Settings </h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form enctype="multipart/form-data" method="post" action="{{ route('settings.update') }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 border-right-blue-400">
                    {{-- School name --}}
                    <div class="form-group row">
                        <label for="system_name" class="col-lg-3 col-form-label font-weight-semibold">Name of School <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input id="system_name" name="system_name" value="{{ $settings->where('type' , 'system_name')->value('description') }}" required type="text" class="form-control" placeholder="Name of School">
                        </div>
                    </div>
                    {{-- Session --}}
                    <div class="form-group row">
                        <label for="current_session" class="col-lg-3 col-form-label font-weight-semibold">Current Session <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select data-placeholder="Choose..." required name="current_session" id="current_session" class="select-search form-control">
                                <option value=""></option>
                                @for($y=date('Y', strtotime('- 3 years')); $y<=date('Y', strtotime('+ 2 years')); $y++) <option @selected($settings->where('type' , 'current_session')->value('description') == (($y -=1 ) . "-" . ($y += 1)))>{{ ($y -= 1) . "-" . ($y += 1) }}</option> @endfor
                            </select>
                        </div>
                    </div>
                    {{-- System acronym --}}
                    <div class="form-group row">
                        <label for="system_title" class="col-lg-3 col-form-label font-weight-semibold">School Acronym</label>
                        <div class="col-lg-9">
                            <input id="system_title" name="system_title" value="{{ $settings->where('type' , 'system_title')->value('description') }}" type="text" class="form-control" placeholder="School Acronym">
                        </div>
                    </div>
                    {{-- Phone --}}
                    <div class="form-group row">
                        <label for="phone" class="col-lg-3 col-form-label font-weight-semibold">Phone</label>
                        <div class="col-lg-9">
                            <input id="phone" name="phone" value="{{ $settings->where('type' , 'phone')->value('description') }}" type="text" class="form-control" placeholder="Phone">
                        </div>
                    </div>
                    {{-- Email --}}
                    <div class="form-group row">
                        <label for="system_email" class="col-lg-3 col-form-label font-weight-semibold">School Email</label>
                        <div class="col-lg-9">
                            <input id="system_email" name="system_email" value="{{ $settings->where('type' , 'system_email')->value('description') }}" type="email" class="form-control" placeholder="School Email">
                        </div>
                    </div>
                    {{-- Address --}}
                    <div class="form-group row">
                        <label for="address" class="col-lg-3 col-form-label font-weight-semibold">School Address <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input id="address" required name="address" value="{{ $settings->where('type' , 'address')->value('description') }}" type="text" class="form-control" placeholder="School Address">
                        </div>
                    </div>
                    {{-- Term ends --}}
                    <div class="form-group row">
                        <label for="term_ends" class="col-lg-3 col-form-label font-weight-semibold">This Term Ends</label>
                        <div class="col-lg-6">
                            <input id="term_ends" name="term_ends" value="{{ $settings->where('type' , 'term_ends')->value('description') }}" type="text" class="form-control date-pick" placeholder="Date Term Ends">
                        </div>
                        <div class="col-lg-3 m-lg-auto mt-1">
                            <span class="font-weight-bold font-italic text-info-800">MM/DD/YYYY </span>
                        </div>
                    </div>
                    {{-- Next term begins --}}
                    <div class="form-group row">
                        <label for="term_begins" class="col-lg-3 col-form-label font-weight-semibold">Next Term Begins</label>
                        <div class="col-lg-6">
                            <input id="term_begins" name="term_begins" value="{{ $settings->where('type' , 'term_begins')->value('description') }}" type="text" class="form-control date-pick" placeholder="Date Term Ends">
                        </div>
                        <div class="col-lg-3 m-lg-auto mt-1">
                            <span class="font-weight-bold font-italic text-info-800">MM/DD/YYYY </span>
                        </div>
                    </div>
                    {{-- Lock exam --}}
                    <div class="form-group row">
                        <label for="lock_exam" class="col-lg-3 col-form-label font-weight-semibold">Lock Exam</label>
                        <div class="col-lg-2">
                            <select class="form-control select" name="lock_exam" id="lock_exam">
                                <option @selected($settings->where('type' , 'lock_exam')->value('description') == 1) value="1">Yes</option>
                                <option @selected($settings->where('type' , 'lock_exam')->value('description') == 0) value="0">No</option>
                            </select>
                        </div>
                        <div class="col-lg-7 m-lg-auto mt-1">
                            <span class="font-weight-bold font-italic text-info-800">{{ __('msg.lock_exam') }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{-- Enable notification --}}
                        <div class="col-12">
                            <label class="col-form-label font-weight-semibold">Enable Notifications</label>
                            <div class="form-group text-center row">
                                <div class="col-4">
                                    <label for="email_channel" class="col-form-label font-weight-semibold float-left">Email</label>
                                    <div class="form-group text-center">
                                        <select class="form-control select" name="enable_email_notification" id="email_channel">
                                            <option @selected($settings->where('type' , 'enable_email_notification')->value('description') == 1) value="1">On</option>
                                            <option @selected($settings->where('type' , 'enable_email_notification')->value('description') == 0) value="0">Off</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="push_channel" class="col-form-label font-weight-semibold float-left">Push</label>
                                    <div class="form-group text-center">
                                        <select class="form-control select" name="enable_push_notification" id="push_channel">
                                            <option @selected($settings->where('type' , 'enable_push_notification')->value('description') == 1) value="1">On</option>
                                            <option @selected($settings->where('type' , 'enable_push_notification')->value('description') == 0) value="0">Off</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="sms_channel" class="col-form-label font-weight-semibold float-left">SMS</label>
                                    <div class="form-group text-center">
                                        <select class="form-control select" name="enable_sms_notification" id="sms_channel">
                                            <option @selected($settings->where('type' , 'enable_sms_notification')->value('description') == 1) value="1">On</option>
                                            <option @selected($settings->where('type' , 'enable_sms_notification')->value('description') == 0) value="0">Off</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {{--Fees--}}
                    <fieldset>
                        <h6>Next Term Fees ({{ Pay::getCurrencyUnit() }})</h6>
                        @foreach($class_types as $ct)
                        <div class="form-group row">
                            <label for="next_term_fees_{{ strtolower($ct->code) }}" class="col-lg-3 col-form-label font-weight-semibold">{{ $ct->name }}</label>
                            <div class="col-lg-9">
                                <input id="next_term_fees_{{ strtolower($ct->code) }}" class="form-control" value="{{ $settings->where('type' , 'next_term_fees_'.strtolower($ct->code))->value('description') }}" name="next_term_fees_{{ strtolower($ct->code) }}" placeholder="{{ $ct->name }}" type="text">
                            </div>
                        </div>
                        @endforeach
                    </fieldset>

                    <hr>

                    <div class="row">
                        {{--Logo--}}
                        <div class="col-lg-6">
                            <div class="form-group text-center">
                                <label for="logo" class="col-form-label font-weight-semibold">Change Logo:</label>
                                <div class="mb-3">
                                    <img style="width: 100px" height="100px" src="{{ tenant_asset($settings->where('type' , 'logo')->value('description')) }}" alt="">
                                </div>
                                <input id="logo" name="logo" accept="image/*" type="file" class="file-input" data-show-caption="false" data-show-upload="false" data-fouc>
                            </div>
                        </div>
                        {{-- Login and related page background --}}
                        @php
                        $settings_table_seeder = new NonTenancySettingsTableSeeder();
                        $bg = $settings->where('type', 'login_and_related_pages_bg')->value('description');
                        @endphp
                        <div class="col-lg-6">
                            <div class="form-group text-center">
                                <label for="login_and_related_pages_bg" class="col-form-label font-weight-semibold">Change Login And related Pages Background:</label>
                                <div class="mb-3">
                                    <img id="login-and-related-pgs-bg" style="width: 100px" height="100px" src="{{ $bg !== null ? tenant_asset($bg) : asset($settings_table_seeder->getLoginAndRelatedPagesBgDescription()) }}" alt="">
                                </div>
                                <input id="login-and-related-pgs-bg-input" name="login_and_related_pages_bg" accept="image/*" type="file" class="file-input" data-show-caption="false" data-show-upload="false" data-fouc>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        {{-- Login and related pages texts and backgrounds colors --}}
                        <label for="texts_and_bg_colors" class="col-lg-4 col-form-label font-weight-semibold">Texts and background colors</label>
                        <div class="col-lg-8">
                            <div class="form-group text-center input-group-text p-0 border-0 bg-transparent auth-pages-preview default">
                                <select disabled class="form-control select" name="texts_and_bg_colors" id="texts_and_bg_colors">
                                    <option selected value="default">Use default colors</option>
                                    <option value="from_img">Generate from the image</option>
                                </select>
                                {{-- Show preview? --}}
                                <div class="form-check mb-auto mt-auto ml-1 mr-2">
                                    <label class="form-check-label">
                                        Preview
                                        <input disabled type="checkbox" name="show_login_and_related_pgs_preview" class="form-input-styled" data-fouc>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            {{-- Allow print --}}
                            <label class="col-form-label font-weight-semibold">Allow Print</label>
                            <div class="form-group text-center row">
                                <div class="col-6">
                                    <label for="allow_marksheet_print" class="col-form-label font-weight-semibold float-left">Marksheet</label>
                                    <div class="form-group text-center">
                                        <select class="form-control select" name="allow_marksheet_print" id="allow_marksheet_print">
                                            <option @selected($settings->where('type' , 'allow_marksheet_print')->value('description') == 1) value="1">Yes</option>
                                            <option @selected($settings->where('type' , 'allow_marksheet_print')->value('description') == 0) value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label for="allow_assessmentsheet_print" class="col-form-label font-weight-semibold float-left">Assessmentsheet</label>
                                    <div class="form-group text-center">
                                        <select class="form-control select" name="allow_assessmentsheet_print" id="allow_assessmentsheet_print">
                                            <option @selected($settings->where('type' , 'allow_assessmentsheet_print')->value('description') == 1) value="1">Yes</option>
                                            <option @selected($settings->where('type' , 'allow_assessmentsheet_print')->value('description') == 0) value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(session()->has('show_login_and_related_pgs_preview'))
                    @include('pages.modals.auth_pages_preview')
                    @endif
                </div>
            </div>

            @if(Qs::userIsHead())
            <hr>

            <div class="row">
                <div class="col-md-6">
                    {{-- Super Admin or Head Super Admin (The one responsible for administering the system in its operation)--}}
                    <h6>Admin Contacts</h6>
                    {{-- Admin email --}}
                    <div class="form-group row">
                        <label for="admin_email" class="col-lg-3 col-form-label font-weight-semibold">Admin Email <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input id="admin_email" required name="admin_email" value="{{ $settings->where('type' , 'admin_email')->value('description') }}" type="email" class="form-control" placeholder="Email">
                        </div>
                    </div>
                    {{-- Admin whatsapp --}}
                    <div class="form-group row">
                        <label for="admin_whatsapp_link" class="col-lg-3 col-form-label font-weight-semibold">WhatsApp Link</label>
                        <div class="col-lg-9">
                            <input id="admin_whatsapp_link" name="admin_whatsapp_link" value="{{ $settings->where('type' , 'admin_whatsapp_link')->value('description') }}" type="text" class="form-control" placeholder="Format; https://wa.me/255111222333">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- Admin facebook social --}}
                    <div class="form-group row">
                        <label for="admin_facebook_link" class="col-lg-3 col-form-label font-weight-semibold">Facebook Link</label>
                        <div class="col-lg-9">
                            <input id="admin_facebook_link" name="admin_facebook_link" value="{{ $settings->where('type' , 'admin_facebook_link')->value('description') }}" type="text" class="form-control" placeholder="Eg., https://www.facebook.com/username">
                        </div>
                    </div>
                    {{-- Admin github link --}}
                    <div class="form-group row">
                        <label for="admin_github_link" class="col-lg-3 col-form-label font-weight-semibold">Github Link</label>
                        <div class="col-lg-9">
                            <input id="admin_github_link" name="admin_github_link" value="{{ $settings->where('type' , 'admin_github_link')->value('description') }}" type="text" class="form-control" placeholder="Eg., https://github.com/username">
                        </div>
                    </div>
                    {{-- Admin linkedIn link --}}
                    <div class="form-group row">
                        <label for="admin_linkedin_link" class="col-lg-3 col-form-label font-weight-semibold">LinkedIn Link</label>
                        <div class="col-lg-9">
                            <input id="admin_linkedin_link" name="admin_linkedin_link" value="{{ $settings->where('type' , 'admin_linkedin_link')->value('description') }}" type="text" class="form-control" placeholder="Eg., https://www.linkedin.com/in/username">
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="float-right">
                <button type="submit" class="btn btn-danger d-flex">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
            </div>
        </form>
    </div>
</div>

{{--Settings Edit Ends--}}

@endsection
