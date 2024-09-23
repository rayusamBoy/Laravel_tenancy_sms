@extends('layouts.master')
@section('page_title', 'Manage System Settings')
@section('content')

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
                        <label class="col-lg-3 col-form-label font-weight-semibold">Name of School <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input name="system_name" value="{{ $settings->where('type' , 'system_name')->value('description') }}" required type="text" class="form-control" placeholder="Name of School">
                        </div>
                    </div>
                    {{-- Session --}}
                    <div class="form-group row">
                        <label for="current_session" class="col-lg-3 col-form-label font-weight-semibold">Current Session <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <select data-placeholder="Choose..." required name="current_session" id="current_session" class="select-search form-control">
                                <option value=""></option>
                                @for($y=date('Y', strtotime('- 3 years')); $y<=date('Y', strtotime('+ 2 years')); $y++) <option {{ ($settings->where('type' , 'current_session')->value('description') == (($y-=1).'-'.($y+=1))) ? 'selected' : '' }}>{{ ($y-=1).'-'.($y+=1) }}</option> @endfor
                            </select>
                        </div>
                    </div>
                    {{-- System acronym --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">School Acronym</label>
                        <div class="col-lg-9">
                            <input name="system_title" value="{{ $settings->where('type' , 'system_title')->value('description') }}" type="text" class="form-control" placeholder="School Acronym">
                        </div>
                    </div>
                    {{-- Phone --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Phone</label>
                        <div class="col-lg-9">
                            <input name="phone" value="{{ $settings->where('type' , 'phone')->value('description') }}" type="text" class="form-control" placeholder="Phone">
                        </div>
                    </div>
                    {{-- Email --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">School Email</label>
                        <div class="col-lg-9">
                            <input name="system_email" value="{{ $settings->where('type' , 'system_email')->value('description') }}" type="email" class="form-control" placeholder="School Email">
                        </div>
                    </div>
                    {{-- Address --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">School Address <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input required name="address" value="{{ $settings->where('type' , 'address')->value('description') }}" type="text" class="form-control" placeholder="School Address">
                        </div>
                    </div>
                    {{-- Term ends --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">This Term Ends</label>
                        <div class="col-lg-6">
                            <input name="term_ends" value="{{ $settings->where('type' , 'term_ends')->value('description') }}" type="text" class="form-control date-pick" placeholder="Date Term Ends">
                        </div>
                        <div class="col-lg-3 mt-2">
                            <span class="font-weight-bold font-italic">M-D-Y or M/D/Y </span>
                        </div>
                    </div>
                    {{-- Next term begins --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Next Term Begins</label>
                        <div class="col-lg-6">
                            <input name="term_begins" value="{{ $settings->where('type' , 'term_begins')->value('description') }}" type="text" class="form-control date-pick" placeholder="Date Term Ends">
                        </div>
                        <div class="col-lg-3 mt-2">
                            <span class="font-weight-bold font-italic">M-D-Y or M/D/Y </span>
                        </div>
                    </div>
                    {{-- Lock exam --}}
                    <div class="form-group row">
                        <label for="lock_exam" class="col-lg-3 col-form-label font-weight-semibold">Lock Exam</label>
                        <div class="col-lg-3">
                            <select class="form-control select" name="lock_exam" id="lock_exam">
                                <option {{ $settings->where('type' , 'lock_exam')->value('description') ? 'selected' : '' }} value="1">Yes</option>
                                <option {{ $settings->where('type' , 'lock_exam')->value('description') ?: 'selected' }} value="0">No</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <span class="font-weight-bold font-italic text-info-800">{{ __('msg.lock_exam') }}</span>
                        </div>
                    </div>
                    {{--Marksheet print--}}
                    <div class="form-group row">
                        <label for="allow_marksheet_print" class="col-lg-8 col-form-label font-weight-semibold">Allow Marksheet Print</label>
                        <div class="col-lg-4">
                            <div class="form-group text-center">
                                <select class="form-control select" name="allow_marksheet_print" id="allow_marksheet_print">
                                    <option {{ $settings->where('type' , 'allow_marksheet_print')->value('description') ? 'selected' : '' }} value="1">Yes</option>
                                    <option {{ $settings->where('type' , 'allow_marksheet_print')->value('description') ?: 'selected' }} value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    {{--Assessmentsheet print--}}
                    <div class="form-group row">
                        <label for="allow_assessmentsheet_print" class="col-lg-8 col-form-label font-weight-semibold">Allow Assessmentsheet Print</label>
                        <div class="col-lg-4">
                            <div class="form-group text-center">
                                <select class="form-control select" name="allow_assessmentsheet_print" id="allow_assessmentsheet_print">
                                    <option {{ $settings->where('type' , 'allow_assessmentsheet_print')->value('description') ? 'selected' : '' }} value="1">Yes</option>
                                    <option {{ $settings->where('type' , 'allow_assessmentsheet_print')->value('description') ?: 'selected' }} value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {{--Fees--}}
                    <fieldset>
                        <strong>Next Term Fees</strong>
                        @foreach($class_types as $ct)
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">{{ $ct->name }}</label>
                            <div class="col-lg-9">
                                <input class="form-control" value="{{ $settings->where('type' , 'next_term_fees_'.strtolower($ct->code))->value('description') }}" name="next_term_fees_{{ strtolower($ct->code) }}" placeholder="{{ $ct->name }}" type="text">
                            </div>
                        </div>
                        @endforeach
                    </fieldset>

                    <hr class="divider">

                    <div class="row">
                        {{--Logo--}}
                        <div class="col-lg-6">
                            <div class="form-group text-center">
                                <label class="col-form-label font-weight-semibold">Change Logo:</label>
                                <div class="mb-3">
                                    <img style="width: 100px" height="100px" src="{{ tenant_asset($settings->where('type' , 'logo')->value('description')) }}" alt="">
                                </div>
                                <input name="logo" accept="image/*" type="file" class="file-input" data-show-caption="false" data-show-upload="false" data-fouc>
                            </div>
                        </div>
                        {{-- Login and related page background--}}
                        <div class="col-lg-6">
                            <div class="form-group text-center">
                                <label class="col-form-label font-weight-semibold">Change Login And related Pages Background:</label>
                                <div class="mb-3">
                                    <img id="login-and-related-pgs-bg" style="width: 100px" height="100px" src="{{ tenant_asset($settings->where('type' , 'login_and_related_pages_bg')->value('description')) }}" alt="">
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

                    @if(session()->has('show_login_and_related_pgs_preview'))
                    @include('pages.modals.auth_pages_preview')
                    @endif

                </div>
            </div>

            @if(Qs::userIsHead())
            <hr class="divider">

            <div class="row">
                <div class="col-md-6">
                    {{-- Super Admin or Head Super Admin (The one responsible for administering the system in its operation)--}}
                    <h6>Admin Contacts</h6>
                    {{-- Admin email --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Admin Email <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input required name="admin_email" value="{{ $settings->where('type' , 'admin_email')->value('description') }}" type="email" class="form-control" placeholder="Eg., admin@hasnuumakame.sc.tz">
                        </div>
                    </div>
                    {{-- Admin whatsapp --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">WhatsApp Link</label>
                        <div class="col-lg-9">
                            <input name="admin_whatsapp_link" value="{{ $settings->where('type' , 'admin_whatsapp_link')->value('description') }}" type="text" class="form-control" placeholder="Format; https://wa.me/255710355377">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- Admin facebook social --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Facebook Link</label>
                        <div class="col-lg-9">
                            <input name="admin_facebook_link" value="{{ $settings->where('type' , 'admin_facebook_link')->value('description') }}" type="text" class="form-control" placeholder="Eg., https://www.facebook.com/rysmtulia07">
                        </div>
                    </div>
                    {{-- Admin github link --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Github Link</label>
                        <div class="col-lg-9">
                            <input name="admin_github_link" value="{{ $settings->where('type' , 'admin_github_link')->value('description') }}" type="text" class="form-control" placeholder="Eg., https://github.com/rayusamBoy">
                        </div>
                    </div>
                    {{-- Admin linkedIn link --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">LinkedIn Link</label>
                        <div class="col-lg-9">
                            <input name="admin_linkedin_link" value="{{ $settings->where('type' , 'admin_linkedin_link')->value('description') }}" type="text" class="form-control" placeholder="Eg., https://www.linkedin.com/in/rayusam">
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <hr class="divider">

            <div class="float-right">
                <button type="submit" class="btn btn-danger d-flex">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
            </div>
        </form>
    </div>
</div>

{{--Settings Edit Ends--}}

@endsection
