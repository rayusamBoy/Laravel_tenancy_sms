@extends('layouts.master')
@section('page_title', 'My Dashboard')

@section('content')

@if(!Qs::currentSessionMatchesCurrentYear() && Qs::userIsSuperAdmin())
@php session()->flash('pop_warning_confirm', __('msg.session_and_year_not_matched')) @endphp
@endif

@if (Qs::userIsAdministrative() or Qs::userIsLibrarian())

@php
$staff = $users->where('user_type', '<>', 'student')->where('user_type', '<>', 'parent')
@endphp

<div class="card-columns">
    <!-- Active Students -->
    <div class="card card-body bg-purple-400 has-bg-image">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-4">
                        <h3 class="mb-0 counter">{{ $student_recs->where('grad', 0)->count() }}</h3>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                M:
                                {{ $student_recs->where('grad', 0)->where('user.gender', 'Male')->count() }}
                            </div>
                            <div class="col-6">
                                F:
                                {{ $student_recs->where('grad', 0)->where('user.gender', 'Female')->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span class="text-capitalize font-size-xs">Active Students</span>
                    </div>
                </div>
            </div>
            <div class="ml-3 align-self-center">
                <a href="javascript:;" class="material-symbols-rounded symbol-3x opacity-75" data-toggle="modal" data-target="#students-number">groups</a>
            </div>
        </div>
    </div>

    @if (Qs::userIsTeamSA())
    <!-- Graduated students -->
    <div class="card card-body bg-purple-300 has-bg-image">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-4">
                        <h3 class="mb-0 counter">{{ $grad_students->count() }}</h3>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                M:
                                {{ $grad_students->where('user.gender', 'Male')->count() }}
                            </div>
                            <div class="col-6">
                                F:
                                {{ $grad_students->where('user.gender', 'Female')->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span class="text-capitalize font-size-xs">Graduated Students</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall students -->
    <div class="card card-body bg-purple-400 has-bg-image">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-4">
                        <h3 class="mb-0 counter">{{ $student_recs->count() }}</h3>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                M:
                                {{ $student_recs->where('user.gender', 'Male')->count() }}
                            </div>
                            <div class="col-6">
                                F:
                                {{ $student_recs->where('user.gender', 'Female')->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span class="text-capitalize font-size-xs">Overall Students</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Administrators -->
    <div class="card card-body bg-danger-600 has-bg-image">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-4">
                        <h3 class="mb-0 counter">
                            {{ $staff->where('user_type', 'super_admin')->count() + $staff->where('user_type', 'admin')->count() }}
                        </h3>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                M:
                                {{ $staff->where('user_type', 'super_admin')->where('gender', 'Male')->count() + $staff->where('user_type', 'admin')->where('gender', 'Male')->count() }}
                            </div>
                            <div class="col-6">
                                F:
                                {{ $staff->where('user_type', 'super_admin')->where('gender', 'Female')->count() + $staff->where('user_type', 'admin')->where('gender', 'Female')->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span class="text-capitalize font-size-xs">Total Administrators</span>
                    </div>
                </div>
            </div>
            <div class="ml-3 align-self-center d-none d-md-block">
                <i class="material-symbols-rounded symbol-3x opacity-75">group</i>
            </div>
        </div>
    </div>
    @endif

    @if (Qs::userIsTeamSA())
    <!-- Support Team -->
    <div class="card card-body bg-danger-400 has-bg-image">
        <div class="media">
            <div class="media-body ml-2">
                <div class="row">
                    <div class="col-6 text-left">
                        {{ $staff->where('user_type', 'teacher')->count() }}
                        <br />
                        <span class="text-capitalize font-size-xs">Total Teachers</span>
                    </div>
                    <div class="col-6 text-left">
                        {{ $staff->where('user_type', 'companion')->count() }}
                        <br />
                        <span class="text-capitalize font-size-xs">Total Companions</span>
                    </div>
                </div>
            </div>
            <div class="ml-3 align-self-center d-none d-md-block">
                <i class="material-symbols-rounded symbol-3x opacity-75">group</i>
            </div>
        </div>
    </div>
    <!-- Accountants & Librarians -->
    <div class="card card-body bg-danger-400 has-bg-image">
        <div class="media">
            <div class="media-body ml-2">
                <div class="row">
                    <div class="col-6 text-left">
                        {{ $staff->where('user_type', 'accountant')->count() }}
                        <br />
                        <span class="text-capitalize font-size-xs">Total Accountants</span>
                    </div>
                    <div class="col-6 text-left">
                        {{ $staff->where('user_type', 'librarian')->count() }}
                        <br />
                        <span class="text-capitalize font-size-xs">Total Librarians</span>
                    </div>
                </div>
            </div>
            <div class="ml-3 align-self-center d-none d-md-block">
                <i class="material-symbols-rounded symbol-3x opacity-75">library_books</i>
            </div>
        </div>
    </div>
    <!-- Assessments -->
    <div class="card card-body bg-success-400 has-bg-image">
        <div class="media">
            <div class="media-body">
                <h3 class="mb-0 counter">{{ $assessments_count }}</h3>
                <span class="text-capitalize font-size-xs">Assesssments Declared</span>
            </div>

            <div class="ml-3 align-self-center">
                <i class="material-symbols-rounded symbol-3x opacity-75">folder_open</i>
            </div>
        </div>
    </div>
    <!-- Parents -->
    <div class="card card-body bg-indigo-400 has-bg-image">
        <div class="media">
            <div class="media-body">
                <div class="row">
                    <div class="col-4">
                        @php $parents = $users->where('user_type', 'parent') @endphp
                        <h3 class="mb-0 counter">{{ $parents->count() }}</h3>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                M:
                                {{ $parents->where('gender', 'Male')->count() }}
                            </div>
                            <div class="col-6">
                                F:
                                {{ $parents->where('gender', 'Female')->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span class="text-capitalize font-size-xs">Total Parents</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (Qs::userIsTeamSA())
    <!-- Total subjects -->
    <div class="card card-body bg-brown-400 has-bg-image">
        <div class="media">
            <div class="media-body">
                <h3 class="mb-0 counter">{{ count($subjects->all()) }}</h3>
                <span class="text-capitalize font-size-xs">Total Subjects</span>
            </div>

            <div class="ml-3 align-self-center">
                <i class="material-symbols-rounded symbol-3x opacity-75">subject</i>
            </div>
        </div>
    </div>
    @endif

    <!-- Classes -->
    <div class="card card-body bg-orange-400 has-bg-image">
        <div class="media">
            <div class="media-body">
                <h3 class="mb-0 counter">{{ count($class->all()) }}</h3>
                <span class="text-capitalize font-size-xs">Total Classes</span>
            </div>

            <div class="ml-3 align-self-center">
                <i class="material-symbols-rounded symbol-3x opacity-75">domain</i>
            </div>
        </div>
    </div>

    @if (Qs::userIsTeamSA())
    <!-- Results -->
    <div class="card card-body bg-primary-400 has-bg-image">
        <div class="media">
            <div class="media-body">
                <h3 class="mb-0 counter">{{ $exams_count }}</h3>
                <span class="text-capitalize font-size-xs">Results Declared</span>
            </div>

            <div class="ml-3 align-self-center">
                <i class="material-symbols-rounded symbol-3x opacity-75">file_open</i>
            </div>
        </div>
    </div>
    @endif

    @if (Qs::userIsAdministrative())
    <!-- Payments -->
    <div class="card card-body bg-pink-400 has-bg-image">
        <div class="media">
            <div class="media-body">
                <h3 class="mb-0 counter">{{ $total_pay_records }}</h3>
                <span class="text-capitalize font-size-xs">Total Payments Done</span>
            </div>

            <div class="ml-3 align-self-center">
                <i class="material-symbols-rounded symbol-3x opacity-75">paid</i>
            </div>
        </div>
    </div>
    @endif

    @if (Qs::userIsTeamLibrary())
    <!-- Books -->
    <div class="card card-body bg-violet-400 has-bg-image">
        <div class="media">
            <div class="media-body">
                <h3 class="mb-0 counter">{{ $books_count }}</h3>
                <span class="text-capitalize font-size-xs">Total Books</span>
            </div>

            <div class="ml-3 align-self-center">
                <i class="material-symbols-rounded symbol-3x opacity-75">library_books</i>
            </div>
        </div>
    </div>

    <!-- Support Team -->
    <div class="card card-body bg-warning-400 has-bg-image">
        <div class="media">
            <div class="media-body ml-2">
                <div class="row">
                    <div class="col-6 text-left">
                        {{ $books_requests->where('returned', 1)->count() }}
                        <br />
                        <span class="text-capitalize font-size-xs">Returned</span>
                    </div>
                    <div class="col-6 text-left">
                        {{ $books_requests->where('returned', 0)->count() }}
                        <br />
                        <span class="text-capitalize font-size-xs">Out</span>
                    </div>
                </div>
            </div>
            <div class="media-body">
                <h3 class="mb-0 counter">{{ $books_requests->count() }}</h3>
                <span class="text-capitalize font-size-xs">Total Books Requests</span>
            </div>
        </div>
    </div>
    @endif

</div>
@endif

<!-- Notices -->
<div class="row">
    <div class="col-12">
        {{-- Notices --}}
        <div class="card card-collapsed">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">Notices<sup class="badge badge-light">{{ $unviewed_count }}</sup></h5>
                @if(Qs::userIsAdministrative())
                <a class="btn btn-link" href="{{ route('notices.index') }}">Manage</a>
                @endif
                {!! Qs::getPanelOptions() !!}
            </div>
            <div class="notices">
                @include('pages/support_team/notices/show')
            </div>
        </div>
    </div>
</div>

{{--Students number breakdown Modal--}}
@include('pages/modals/students_number')

@endsection
