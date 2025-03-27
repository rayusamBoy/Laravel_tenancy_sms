@extends('layouts.app')

@section('page_title', 'Confirm Access')

@pushOnce('css')
<style>
    .page-content.app-cover {
        background-image: url('{{ tenant_asset(Qs::getSetting("logo")) }}');
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        background-color: #141414 !important;
        background-blend-mode: multiply;

    }
</style>
@endPushOnce

@section('content')

<div class="page-content app-cover">
    <div class="content-wrapper">
        <div class="content d-flex justify-content-center align-items-center">

            <div class="row mw-sm-25em">
                <div class="card col-12 border-none box-shadow-none text-mupted text-center"></div>
                <div class="m-auto">
                    <i class="material-symbols-rounded symbol-2x text-danger border-danger border-3 rounded-round p-3 mb-3 mt-1">passkey</i>
                </div>

                <div class="card col-12 border-1 border-color-1d1d1d">
                    <div class="card-header text-green">
                        <h5 class="mb-0 text-danger"><strong>Confirm Access</strong></h5>
                    </div>

                    <div class="card-body text-white">

                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <label for="password" class="col-form-label text-md-end">Password</label>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <input id="password" type="password" placeholder="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <span class="font-small">{{ $message }}</span>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-success">
                                        Confirm Password
                                    </button>
                                </div>

                                <div class="col-6">
                                    <a class="btn btn-link" href="{{ (url()->previous() == url()->current()) ? url('/dashboard') : url()->previous() }}">
                                        Return Back
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card col-12 border-none box-shadow-none text-muted text-center">
                    For security, confirm your password to access this operation. It expires after about 30 minutes of inactivity.
                </div>
            </div>

        </div>
    </div>
</div>

@endsection