@extends('layouts.master')

@section('page_title', 'Analytics')

@section('content')

<div class="{{ isset($google_analytics_setup_ok) ? 'card card-collapsed' : 'card' }}">
    <div class="card-header header-elements-inline">
        <h6 class="card-title font-weight-semibold">Set up or Update Google Analytics @if(isset($google_analytics_setup_ok))<span class="text-success">[active]</span>@else <span class="text-warning-800">[inactive]</span> @endif</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="row">
        <div class="col-12">
            <div class="alert alert-info border-0 alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <span>
                    First you need to create a google account if you do not have one. Then you configure it for use with analytics (You may google on how to do it). Then follow this link
                    <a target="_blank" href="https://github.com/spatie/laravel-analytics"> How to obtain the credentials to communicate with Google Analytics</a> to get finished.<br>
                </span>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form enctype="multipart/form-data" method="post" action="{{ route('analytics.google_setup') }}">
            @csrf
            <div class="form-group row">
                {{-- Tag ID --}}
                <div class="col-lg-4">
                    <label>Tag ID</label>
                    @if(isset($gtag_code_structure_file))
                    <span class="display-i-on-hover float-right text-info">Info
                        <i class="float-right text-info display-none dropdown-menu position-absolute top-auto p-2">
                            <div>
                                <span class="ml-3">A Google tag ID is a series of letters and numbers that usually starts with "G-".</span>
                                @php
                                highlight_file($gtag_code_structure_file)
                                @endphp
                            </div>
                        </i>
                    </span>
                    @endif

                    <input name="google_analytic_tag_id" type="text" data-mask="G-wwwwwwwww?www" class="form-control" placeholder="Ie., G-4BCDFR6HGE">
                    @if(empty($google_analytic_tag_id))
                    <em class="form-text text-warning-800">No Tag ID found.</em>
                    @else
                    <span class="form-text text-green-800">Found Tag ID: {{ $google_analytic_tag_id }}</span>
                    @endif
                </div>
                {{-- Analytic Property ID --}}
                <div class="col-lg-4">
                    <label for="" class="d-block">Analytic Property ID</label>
                    <input id="google_analytic_property_id" name="google_analytic_property_id" type="text" data-mask="999999999" class="form-control" placeholder="Ie., 123456789">
                    @if(empty($google_analytic_property_id))
                    <em class="form-text text-warning-800">No Property ID found.</em>
                    @else
                    <span class="form-text text-green-800">Found Property ID: {{ $google_analytic_property_id }}</span>
                    @endif
                </div>
                {{-- Credential file --}}
                <div class="col-lg-4">
                    <label>Upload Service Account Credential File:</label>
                    <input value="{{ old('service_acc_credential_file') }}" accept=".json" type="file" name="service_acc_credential_file" class="form-input-styled" data-fouc>
                    <span class="form-text text-muted">Accepted File: json, size 2Mb</span>

                    @if(isset($credential_file))
                    <span class="display-i-on-hover float-right text-info">File Contents
                        <i class="float-right text-info display-none dropdown-menu position-absolute top-auto p-2">
                            <div>
                                @php
                                highlight_file($credential_file)
                                @endphp
                            </div>
                        </i>
                    </span>
                    @else
                    <em class="form-text text-warning-800">No Credential File found.</em>
                    @endif
                </div>
            </div>
            <div class="float-right d-flex">
                @if(!empty($google_analytic_tag_id) && !empty($google_analytic_property_id) && isset($credential_file))

                @if(isset($google_analytics_setup_ok))
                {{-- Disable analytics --}}
                <a href="{{ route('settings.disable_analytics') }}" type="button" class="btn btn-danger">Disable Analytics</a>
                @else
                {{-- Submit button --}}
                <button type="submit" class="btn btn-success d-flex mr-2">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                {{-- Enable analytics --}}
                <a href="{{ route('settings.enable_analytics') }}" type="button" class="btn btn-primary">Enable Analytics</a>
                @endif

                @else
                {{-- Submit button --}}
                <button type="submit" class="btn btn-success d-flex mr-2">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                
                @endif
            </div>
        </form>
    </div>
</div>

@if(isset($google_analytics_setup_ok))
<form enctype="multipart/form-data" class="page-block" method="get" action="{{ route('analytics.fetch_data') }}">
    @csrf
    <div class="form-group row">
        <div class="col-md-6 offset-md-6">
            <div class="row">
                <label class="col-lg-5 col-form-label font-weight-semibold">The period ago to fetch data for <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                    <input name="period_number" value="{{ old('period_number') ?? $data['period_number'] }}" required type="number" min="1" class="form-control" placeholder="Type a number">
                </div>
                <div class="col-lg-4">
                    <select data-placeholder="Choose..." required name="period_name" class="select form-control">
                        @foreach(Qs::getSpatieAnalyticsPackagePeriods() as $key => $value)
                        <option value="{{ $key }}" @if($key==$data["period_name"]) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row mt-2">
                <div class="col-12">
                    <button type="submit" class="btn btn-success float-right">Fetch Data <i class="material-symbols-rounded ml-2">send</i></button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="card-columns">
    <!-- Visitors page view -->
    <div class="card card-body bg-violet-400">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <strong class="col-6">
                                Page Title
                            </strong>
                            <strong class="col-3">
                                Active Users
                            </strong>
                            <strong class="col-3">
                                Page Views
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @foreach($vstrsAndPgVw as $a)
                        <div class="row">
                            <div class="col-6">
                                {{ $a["pageTitle"] }}
                            </div>
                            <div class="col-3 counter">
                                {{ $a["activeUsers"] }}
                            </div>
                            <div class="col-3 counter">
                                {{ $a["screenPageViews"] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong class=" text-capitalize float-right">Visitors and Page Views</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Visitors page view by date -->
    <div class="card card-body bg-warning-400">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <strong class="col-6">
                                Page Title
                            </strong>
                            <strong class="col-2">
                                Active Users
                            </strong>
                            <strong class="col-2">
                                Page Views
                            </strong>
                            <strong class="col-2">
                                Date
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @foreach($vstrsAndPgVwByDt as $b)
                        <div class="row">
                            <div class="col-6">
                                {{ $b["pageTitle"] }}
                            </div>
                            <div class="col-2 counter">
                                {{ $b["activeUsers"] }}
                            </div>
                            <div class="col-2 counter">
                                {{ $b["screenPageViews"] }}
                            </div>
                            <div class="col-2 counter">
                                {{ $b["date"] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong class=" text-capitalize float-right">Visitors and Page Views by Date</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Total visitors and page view -->
    <div class="card card-body bg-success-400">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <strong class="col-6">
                                Date
                            </strong>
                            <strong class="col-3">
                                Visitors
                            </strong>
                            <strong class="col-3">
                                Page Views
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @foreach($ttlVstrsAndPgVw as $c)
                        <div class="row">
                            <div class="col-6">
                                {{ $c["date"] }}
                            </div>
                            <div class="col-3 counter">
                                {{ $c["activeUsers"] }}
                            </div>
                            <div class="col-3 counter">
                                {{ $c["screenPageViews"] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong class=" text-capitalize float-right">Total Visitors and Page Views</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Most visited pages -->
    <div class="card card-body bg-pink-400">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <strong class="col-6">
                                Full Page Url
                            </strong>
                            <strong class="col-3">
                                Page Title
                            </strong>
                            <strong class="col-3">
                                Page Views
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @foreach($mostVstdPgs as $a)
                        <div class="row">
                            <div class="col-6">
                                {{ $a["fullPageUrl"] }}
                            </div>
                            <div class="col-3 counter">
                                {{ $a["pageTitle"] }}
                            </div>
                            <div class="col-3 counter">
                                {{ $a["screenPageViews"] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong class=" text-capitalize float-right">Most Visited Pages</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Visitors page view by date -->
    <div class="card card-body bg-indigo-400">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <strong class="col-3">
                                Page Views
                            </strong>
                            <strong class="col-9">
                                Referrer
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @foreach($topReferrers as $b)
                        <div class="row">
                            <div class="col-3 counter">
                                {{ $b["screenPageViews"] }}
                            </div>
                            <div class="col-9 counter">
                                {{ $b["pageReferrer"] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong class=" text-capitalize float-right">Top Referrers</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- User types -->
    <div class="card card-body bg-purple-400">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <strong class="col-6">
                                Active Users
                            </strong>
                            <strong class="col-6">
                                Status
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @foreach($userTypes as $c)
                        <div class="row">
                            <div class="col-6 counter">
                                {{ $c["activeUsers"] }}
                            </div>
                            <div class="col-6 counter">
                                {{ $c["newVsReturning"] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong class=" text-capitalize float-right">User Types</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Top browsers -->
    <div class="card card-body bg-orange-400">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <strong class="col-6">
                                Page Views
                            </strong>
                            <strong class="col-6">
                                Browser
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @foreach($topBrowsers as $d)
                        <div class="row">
                            <div class="col-6 counter">
                                {{ $d["screenPageViews"] }}
                            </div>
                            <div class="col-6 counter">
                                {{ $d["browser"] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong class=" text-capitalize float-right">Top Browsers</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Top countries -->
    <div class="card card-body bg-primary-400">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <strong class="col-6">
                                Page Views
                            </strong>
                            <strong class="col-6">
                                Country
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @foreach($topCountries as $d)
                        <div class="row">
                            <div class="col-6 counter">
                                {{ $d["screenPageViews"] }}
                            </div>
                            <div class="col-6 counter">
                                {{ $d["country"] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong class=" text-capitalize float-right">Top Countries</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Top OSes -->
    <div class="card card-body bg-info-400">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <strong class="col-6">
                                Page Views
                            </strong>
                            <strong class="col-6">
                                Operating System
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @foreach($topOses as $d)
                        <div class="row">
                            <div class="col-6 counter">
                                {{ $d["screenPageViews"] }}
                            </div>
                            <div class="col-6 counter">
                                {{ $d["operatingSystem"] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong class=" text-capitalize float-right">Top Operating Systems</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<h6 class="font-weight-bold status-styled float-right">Showing Analytics Data for the past {{ $data["period_number"] . ' ' . $data["period_name"] }}.</h6>

@endif

@endsection