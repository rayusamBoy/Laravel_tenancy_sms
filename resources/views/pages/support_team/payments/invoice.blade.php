@extends('layouts.master')

@section('page_title', 'Manage Payments')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title font-weight-bold">Manage Payment Records for {{ $sr->user->name }} ({{ $year ?? "All" }})</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#all-uc" class="nav-link active" data-toggle="tab">Incomplete Payments</a></li>
            <li class="nav-item"><a href="#all-cl" class="nav-link" data-toggle="tab">Completed Payments</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="all-uc">
                <table class="table datatable-button-html5-columns table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Pay Ref</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Pay Now</th>
                            <th>Receipt No</th>
                            <th>Year</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($uncleared as $uc)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $uc->payment->title }}</td>
                            <td>{{ $uc->payment->ref_no }}</td>

                            {{--Amount--}}
                            <td class="font-weight-bold" id="amt-{{ Qs::hash($uc->id) }}" data-amount="{{ $uc->payment->amount }}">{{ $uc->payment->amount }}</td>
                            {{--Amount Paid--}}
                            <td id="amt_paid-{{ Qs::hash($uc->id) }}" data-amount="{{ $uc->amt_paid ?: 0 }}" class="text-blue font-weight-bold">{{ $uc->amt_paid ?: '0.00' }}</td>
                            {{--Balance--}}
                            <td id="bal-{{ Qs::hash($uc->id) }}" class="text-danger font-weight-bold">{{ $uc->balance ?: $uc->payment->amount }}</td>
                            {{--Pay Now Form--}}
                            <td>
                                <form id="{{ Qs::hash($uc->id) }}" method="post" class="ajax-pay" action="{{ route('payments.pay_now', Qs::hash($uc->id)) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 input-group">
                                            <input min="1" max="{{ $uc->balance ?: $uc->payment->amount }}" id="val-{{ Qs::hash($uc->id) }}" class="form-control form-control-sm w-6rem" required placeholder="Pay Now" title="Pay Now" name="amt_paid" type="number">
                                            <button class="btn input-group-text border-0" data-text=" " type="submit">Pay</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                            {{--Receipt No--}}
                            <td>{{ $uc->ref_no }}</td>
                            {{-- Year --}}
                            <td>{{ $uc->year }}</td>
                            {{--Action--}}
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a class="material-symbols-rounded" href="javascript:;" data-toggle="dropdown">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{--Reset Payment--}}
                                            <a id="{{ Qs::hash($uc->id) }}" onclick="confirmResetTwice(this.id)" href="javascript:;" class="dropdown-item"><i class="material-symbols-rounded">restart_alt</i> Reset Payment</a>
                                            <form method="post" id="item-reset-{{ Qs::hash($uc->id) }}" action="{{ route('payments.reset_record', Qs::hash($uc->id)) }}" class="hidden">@csrf @method('delete')</form>
                                            {{--Receipt--}}
                                            <a target="_blank" href="{{ route('payments.receipts', Qs::hash($uc->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">print</i> Print Receipt</a>
                                            {{--PDF Receipt--}}
                                            {{--<a href="{{ route('payments.pdf_receipts', Qs::hash($uc->id)) }}" class="dropdown-item download-receipt"><i class="material-symbols-rounded">download</i> Download Receipt</a>--}}
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="all-cl">
                <table class="table datatable-button-html5-columns table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Pay Ref</th>
                            <th>Amount</th>
                            <th>Receipt No</th>
                            <th>Year</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cleared as $cl)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $cl->payment->title }}</td>
                            <td>{{ $cl->payment->ref_no }}</td>
                            {{--Amount--}}
                            <td class="font-weight-bold">{{ $cl->payment->amount }}</td>
                            {{--Receipt No--}}
                            <td>{{ $cl->ref_no }}</td>
                            {{-- Year --}}
                            <td>{{ $cl->year }}</td>
                            {{--Action--}}
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="javascript:;" class="material-symbols-rounded" data-toggle="dropdown">lists</a>

                                        <div class="dropdown-menu dropdown-menu-left">
                                            {{--Reset Payment--}}
                                            @if (Qs::userIsSuperAdmin() or Qs::userIsAccountant())
                                            <a id="{{ Qs::hash($cl->id) }}" onclick="confirmResetTwice(this.id)" href="javascript:;" class="dropdown-item"><i class="material-symbols-rounded">restart_alt</i> Reset Payment</a>
                                            <form method="post" id="item-reset-{{ Qs::hash($cl->id) }}" action="{{ route('payments.reset_record', Qs::hash($cl->id)) }}" class="hidden">@csrf @method('delete')</form>
                                            @endif
                                            {{--Receipt--}}
                                            <a target="_blank" href="{{ route('payments.receipts', Qs::hash($cl->id)) }}" class="dropdown-item"><i class="material-symbols-rounded">print</i> Print Receipt</a>
                                            {{--PDF Receipt--}}
                                            {{-- <a  href="{{ route('payments.pdf_receipts', Qs::hash($uc->id)) }}" class="dropdown-item download-receipt"><i class="material-symbols-rounded">download</i> Download Receipt</a>--}}
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

{{--Payments Invoice List Ends--}}

@endsection
