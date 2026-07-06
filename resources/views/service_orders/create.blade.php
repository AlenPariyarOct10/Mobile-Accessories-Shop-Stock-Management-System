@extends('layouts.app')
@section('page_title', 'Add Service Order')
@section('content')
<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('service-orders.store') }}">@csrf @include('service_orders._form')</form></div></div>
@endsection
