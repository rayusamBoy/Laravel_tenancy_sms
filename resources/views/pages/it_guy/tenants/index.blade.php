@extends('layouts.master')
@section('page_title', 'Manage Tenants')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Manage Tenants</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#new-tenant" class="nav-link active" data-toggle="tab">Create New Tenant</a></li>
            <li class="nav-item"><a href="#manage-tenants" class="nav-link" data-toggle="tab">Manage Tenants</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="new-tenant">
                <form class="ajax-store" method="post" action="{{ route('tenants.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Tenant Name: <span class="text-danger">*</span></label>
                                <input id="name" required name="name" value="{{ old('name') }}" type="text" class="form-control" placeholder="Eg., Hasnuu Makame Secondary School">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="account_status"> Account Status: <span class="text-danger">*</span></label>
                                <select required data-placeholder="Select Status" class="form-control select" name="account_status" id="account_status">
                                    @foreach (Usr::getAccountStatuses() as $status)
                                    <option {{ old('account_status') === $status ? 'selected' : '' }} value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="domain" class="font-weight-semibold">Domain <span class="text-danger">*</span></label>
                                <div class="text-right input-group">
                                    <label class="input-group-text">https://</label>
                                    <input id="domain" required name="domain" value="{{ old('domain') }}" type="text" class="form-control" placeholder="Eg., foo.localhost">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="payment_status"> Payment Status: <span class="text-danger">*</span></label>
                                <select required data-placeholder="Select Status" class="form-control select" name="payment_status" id="payment_status">
                                    @foreach (Pay::getPaymentStatuses() as $key => $value)
                                    <option {{ old('payment_status') === $key ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="remarks" class="font-weight-semibold">Remarks</label>
                                <textarea id="remarks" name="remarks" class="form-control">{{ old('remarks') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" data-text="Creating..." class="btn btn-primary">Submit form <i class="material-symbols-rounded ml-2">send</i></button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade" id="manage-tenants">
                <table class="table datatable-button-html5-columns ">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Name</th>
                            <th>Domain</th>
                            <th>Account Status</th>
                            <th>Payment Status</th>
                            <th>Remarks</th>
                            <th>DB Name</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tenants as $tenant)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $tenant->name }}</td>
                            <td><a href="https://{{ $tenant->domain->domain ?? $tenant->domain }}" target="_blank">{{ $tenant->domain->domain ?? $tenant->domain }}</a></td>
                            <td>{{ ucfirst($tenant->account_status) }}</td>
                            <td>{{ ucfirst($tenant->payment_status) }}</td>
                            <td>{{ $tenant->remarks }}</td>
                            <td>{{ $tenant->tenancy_db_name }}</td>
                            <td>{{ $tenant->created_at }}</td>
                            <td>{{ $tenant->updated_at }}</td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="material-symbols-rounded" data-toggle="dropdown">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{-- Edit --}}
                                            <a href="{{ route('tenants.edit', Qs::hash($tenant->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">edit</i> Edit</a>
                                            {{-- Delete --}}
                                            @if(Qs::userIsHead())
                                            <a id="{{ Qs::hash($tenant->id) }}" onclick="confirmPermanentDeleteTwice(this.id)" href="javascript:;" class="dropdown-item text-danger"><i class="material-symbols-rounded">delete</i> Delete</a>
                                            <form method="post" id="item-delete-{{ Qs::hash($tenant->id) }}" action="{{ route('tenants.destroy', Qs::hash($tenant->id)) }}" class="hidden page-block">@csrf @method('delete')</form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Tenant List Ends --}}

@endsection
