@extends('layouts.app')
@section('page_title', 'Edit Sale')
@section('content')<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('sales.update', $sale) }}">@csrf @method('PUT') @include('sales._form')</form></div></div>@endsection
