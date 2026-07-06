@extends('layouts.app')
@section('page_title', 'Add Sale')
@section('content')<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('sales.store') }}">@csrf @include('sales._form')</form></div></div>@endsection
