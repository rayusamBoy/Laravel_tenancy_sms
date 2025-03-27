@extends('layouts.login_master')

@section('page_title', 'Account Recovery')

@section('content')

<div class="page-content login-cover">
    <div class="content-wrapper">
        <div class="content d-flex justify-content-center">
            <div class="row align-items-center w-100">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header text-info"><strong>{{ ('Recover Your Account') }}</strong></div>

                        <div class="card-body text-muted">
                            <p>{{ ('Please enter one of the recovery codes to recover your account.') }}</p>

                            <form method="POST" action="{{ route('account_security.account_recovery') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="recovery_codes" class="col-md-4 col-form-label text-md-right">{{ ('Recovery code') }}</label>

                                    <div class="col-md-6">
                                        <input id="recovery_codes" type="text" class="form-control @if($errors->any()) is-invalid @endif" name="recovery_codes" placeholder="Recovery code" required autofocus>

                                        @if ($errors->any())
                                        <strong class="invalid-feedback" role="alert">
                                            <em>{{ implode('<br>', $errors->all()) }}</em>
                                        </strong>
                                        @enderror

                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary btn-sm"> Submit</button>
                                        <a type="button" href="{{ route('2fa.show') }}" class="btn btn-secondary btn-sm"> Return Back</a>
                                        <button type="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-danger btn-sm"> Cancel</button>
                                    </div>
                                </div>
                            </form>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
