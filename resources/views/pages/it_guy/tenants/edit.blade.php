@extends('layouts.master')

@section('page_title', 'Edit Tenant')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 id="ajax-title" class="card-title">Edit Tenant Details - {{ $tenant->name }}</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="post" enctype="multipart/form-data" class="ajax-update" data-reload="#ajax-title" action="{{ route('tenants.update', Qs::hash($tenant->id)) }}" data-fouc>
            @csrf @method('PUT')

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Tenant Name: <span class="text-danger">*</span></label>
                        <input disabled id="name" required name="name" value="{{ $tenant->name }}" type="text" class="form-control" placeholder="tenant name">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="account_status"> Account Status: <span class="text-danger">*</span></label>
                        <select required data-placeholder="Select User" class="form-control select" name="account_status" id="account_status">
                            @foreach (Usr::getAccountStatuses() as $status)
                            <option @selected($tenant->account_status === $status) value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="domain" class="font-weight-semibold">Domain <span class="text-danger">*</span></label>
                        <input id="domain" required name="domain" value="{{ $tenant->domain->domain ?? $tenant->domain }}" type="text" class="form-control" placeholder="Eg., foo.localhost">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="payment_status"> Payment Status: <span class="text-danger">*</span></label>
                        <select required data-placeholder="Select User" class="form-control select" name="payment_status" id="payment_status">
                            @foreach (Pay::getPaymentStatuses() as $key => $status)
                            <option @selected($tenant->payment_status === $status) value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="form-group">
                        <label for="remarks" class="font-weight-semibold">Remarks</label>
                        <textarea id="remarks" name="remarks" class="form-control">{{ $tenant->remarks }}</textarea>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
            </div>
        </form>
    </div>
</div>

@endsection
