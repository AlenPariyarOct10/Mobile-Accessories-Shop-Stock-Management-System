@extends('layouts.app')
@section('page_title', 'Edit Service Order')
@section('content')
<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('service-orders.update', $serviceOrder) }}">@csrf @method('PUT') @include('service_orders._form')</form></div></div>
@endsection
