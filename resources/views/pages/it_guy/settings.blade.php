@extends('layouts.master')
@section('page_title', 'Manage System Settings')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title font-weight-semibold">Update System Settings </h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form enctype="multipart/form-data" method="post" action="{{ route('settings_non_tenancy.update') }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 border-right-blue-400">
                    {{-- School System --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Name of System <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input name="system_name" value="{{ $settings->where('type' , 'system_name')->value('description') }}" required type="text" class="form-control" placeholder="Name of School">
                        </div>
                    </div>
                    {{-- System acronym --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">System Acronym</label>
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
                        <label class="col-lg-3 col-form-label font-weight-semibold">System Email</label>
                        <div class="col-lg-9">
                            <input name="system_email" value="{{ $settings->where('type' , 'system_email')->value('description') }}" type="email" class="form-control" placeholder="School Email">
                        </div>
                    </div>
                    {{-- Address --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">System Address <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            <input required name="address" value="{{ $settings->where('type' , 'address')->value('description') }}" type="text" class="form-control" placeholder="School Address">
                        </div>
                    </div>                   
                </div>
                <div class="col-md-6">
                    <div class="row">                        
                        {{--Login page background--}}
                        <div class="col-lg-6">
                            <div class="form-group text-center">
                                <label class="col-form-label font-weight-semibold">Change Login Page Background:</label>
                                <div class="mb-3">
                                    <img style="width: 100px" height="100px" src="{{ asset($settings->where('type' , 'login_and_related_pages_bg')->value('description')) }}" alt="">
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
                            <input required name="admin_email" value="{{ $settings->where('type' , 'admin_email')->value('description') }}" type="email" class="form-control" placeholder="Email">
                        </div>
                    </div>
                    {{-- Admin whatsapp --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">WhatsApp Link</label>
                        <div class="col-lg-9">
                            <input name="admin_whatsapp_link" value="{{ $settings->where('type' , 'admin_whatsapp_link')->value('description') }}" type="text" class="form-control" placeholder="Format; https://wa.me/255111222333">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- Admin facebook social --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Facebook Link</label>
                        <div class="col-lg-9">
                            <input name="admin_facebook_link" value="{{ $settings->where('type' , 'admin_facebook_link')->value('description') }}" type="text" class="form-control" placeholder="Eg., https://www.facebook.com/username">
                        </div>
                    </div>
                    {{-- Admin github link --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Github Link</label>
                        <div class="col-lg-9">
                            <input name="admin_github_link" value="{{ $settings->where('type' , 'admin_github_link')->value('description') }}" type="text" class="form-control" placeholder="Eg., https://github.com/username">
                        </div>
                    </div>
                    {{-- Admin linkedIn link --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">LinkedIn Link</label>
                        <div class="col-lg-9">
                            <input name="admin_linkedin_link" value="{{ $settings->where('type' , 'admin_linkedin_link')->value('description') }}" type="text" class="form-control" placeholder="Eg., https://www.linkedin.com/in/username">
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
