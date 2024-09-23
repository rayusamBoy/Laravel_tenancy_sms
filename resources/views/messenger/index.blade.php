@extends('layouts.master')

@section('page_title', 'Messages')

@section('content')
@include('messenger.partials.flash')

@each('messenger.partials.thread', $threads, 'thread', 'messenger.partials.no-threads')

@if(Qs::userIsSuperAdmin())
@include('messenger.create')
<button type="button" id="new-message" onclick="showFormCard()" class="btn btn-success btn-sm float-right position-fixed bottom-p5em right-p5em"><i class="material-symbols-rounded">add</i> New Message thread</button>
@endif
@stop
