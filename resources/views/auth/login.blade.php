@extends('layouts.login_master')

@section('page_title', 'Login')

@section('content')
<div class="page-content login-cover">

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Content area -->
        <div class="content d-flex justify-content-center">
            <div class="row align-items-center">

                {{-- <div class="col-md-7">
                    <div class="introductory-section">
                        <div class="card intro border-0">
                            <div class="card-body">
                                <h4 class="card-title"><strong class="text-info">Peace Be Upon You, Welcome.</strong></h4>
                                <div class="card-text">
                                    <p>
                                        {{ ("School Maanagement System (SMS) deals with information related to the school
                                        in general such as; teachers information, parents information,
                                        students information, students accommodation status,
                                        students academic records, fees payments records and
                                        other related informations.") }}
                                    </p>

                                    <h5><strong class="text-info">{{ ("Authorized Users can,") }}</strong></h5>
                                    <ul class="pl-3">
                                        <li>{{ ("Publish Exam Results.") }}</li>
                                        <li>{{ ("Manage Classes, Subjects, Examinations, Payments, Books, Announcements etc...") }}</li>
                                        <li>{{ ("View respective Informations.") }}</li>
                                        <li>{{ ("Create, View, Delete, and Edit records.") }}</li>
                                        <li>{{ ("Generate Reports.") }}</li>
                                        <li>{{ ("Among others...") }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <!-- Login card -->
                <div class="col-md-5">
                    <form class="login-form" method="post" action="{{ route('login') }}">
                        @csrf
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <span class="material-symbols-rounded symbol-2x text-warning-400 border-warning-400 border-3 rounded-round p-3 mb-3 mt-1">security</span>
                                    <h5 class="mb-0 text-info">Login to your account</h5>
                                    <span class="d-block text-muted">Your credentials</span>
                                </div>

                                @if ($errors->any())
                                <div class="alert alert-danger alert-styled-left alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <i>Oops! {{ implode('<br>', $errors->all()) }}</i>
                                </div>
                                @endif

                                {{-- Look for login controller for more --}}
                                @if (session("session_expired") && $errors->isEmpty())
                                <div class="alert alert-danger alert-styled-left alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <i>{{ session("session_expired") }}</i>
                                </div>
                                @endif

                                {{-- Error message for a blocked user --}}
                                @if (Session::has("access_denied"))
                                <div class="alert alert-warning alert-styled-left alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <i>{{ session("access_denied") }}</i>
                                </div>
                                @endif

                                <div class="form-group ">
                                    <input type="text" class="form-control" name="identity" value="{{ old('identity') }}" placeholder="Login ID or Email" autocomplete="identity">
                                </div>

                                <div class="form-group ">
                                    <input required name="password" type="password" class="form-control" placeholder="{{ __('Password') }}" autocomplete="password">
                                </div>

                                <div class="form-group d-flex align-items-center">
                                    <div class="form-check mb-0">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="remember" class="form-input-styled" {{ old('remember') ? 'checked' : '' }} data-fouc>
                                            <span class="text-info">Remember Me</span>
                                        </label>
                                    </div>
                                    <a href="{{ route('password.request') }}" class="ml-auto">Forgot password?</a>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn bg-blue w-100 d-flex"><i class="material-symbols-rounded ml-auto mr-1">login</i> <span class="mr-auto">Sign in</span></button>
                                </div>
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
