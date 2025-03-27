@extends('layouts.master')

@section('page_title', 'Payments Status')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title font-weight-bold">Payment Status for {{ $sr->user->name }} </h6>
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
                            <th>Description</th>
                            <th>Pay_Ref</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($uncleared as $uc)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $uc->payment->title }}</td>
                            <td>{{ $uc->payment->description }}</td>
                            <td>{{ $uc->payment->ref_no }}</td>
                            {{--Amount--}}
                            <td class="font-weight-bold" id="amt-{{ Qs::hash($uc->id) }}" data-amount="{{ $uc->payment->amount }}">{{ $uc->payment->amount }}</td>
                            {{--Amount Paid--}}
                            <td id="amt_paid-{{ Qs::hash($uc->id) }}" data-amount="{{ $uc->amt_paid ?: 0 }}" class="text-blue font-weight-bold">{{ $uc->amt_paid ?: '0.00' }}</td>
                            {{--Balance--}}
                            <td id="bal-{{ Qs::hash($uc->id) }}" class="text-danger font-weight-bold">{{ $uc->balance ?: $uc->payment->amount }}</td>
                            {{--Year--}}
                            <td>{{ $uc->year }}</td>
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
                            <th>Pay_Ref</th>
                            <th>Amount</th>
                            <th>Receipt_No</th>
                            <th>Year</th>
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{--Payments Status Ends--}}

@endsection