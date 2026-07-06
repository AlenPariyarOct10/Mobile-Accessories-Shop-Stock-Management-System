@extends('layouts.app')
@section('page_title', 'Service Order Details')
@section('actions')@if($serviceOrder->status === 'Pending')<a class="btn btn-success" href="{{ route('service-orders.complete', $serviceOrder) }}">Mark Completed</a>@endif <a class="btn btn-light" href="{{ route('repair-services.index') }}">Back</a>@endsection
@section('content')
<div class="card content-card"><div class="card-body"><dl class="row mb-0">
    <dt class="col-sm-3">Status</dt><dd class="col-sm-9">{{ $serviceOrder->status }}</dd>
    <dt class="col-sm-3">Order Date</dt><dd class="col-sm-9">{{ \App\Support\DateFormatter::human($serviceOrder->date) }}</dd>
    <dt class="col-sm-3">Expected</dt><dd class="col-sm-9">{{ \App\Support\DateFormatter::human($serviceOrder->expected_at, true) }}</dd>
    <dt class="col-sm-3">Service</dt><dd class="col-sm-9">{{ $serviceOrder->service_type }}</dd>
    <dt class="col-sm-3">Customer</dt><dd class="col-sm-9">{{ $serviceOrder->customer_name }} {{ $serviceOrder->customer_phone }} {{ $serviceOrder->customer_address }}</dd>
    <dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $serviceOrder->description }}</dd>
    <dt class="col-sm-3">Notes</dt><dd class="col-sm-9">{{ $serviceOrder->notes }}</dd>
    @if($serviceOrder->repairService)
        <dt class="col-sm-3">Service Record</dt><dd class="col-sm-9"><a href="{{ route('repair-services.show', $serviceOrder->repairService) }}">View completed service</a></dd>
    @endif
</dl></div></div>
@endsection
