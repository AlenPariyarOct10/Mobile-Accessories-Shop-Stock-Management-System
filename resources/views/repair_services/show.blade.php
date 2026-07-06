@extends('layouts.app')
@section('page_title', 'Repair / Service Details')
@section('content')<div class="card content-card"><div class="card-body"><dl class="row mb-0">
    <dt class="col-sm-3">Date</dt><dd class="col-sm-9">{{ \App\Support\DateFormatter::human($repairService->serviced_at, true) }}</dd>
    <dt class="col-sm-3">Service</dt><dd class="col-sm-9">{{ $repairService->service_type }}</dd>
    <dt class="col-sm-3">Customer</dt><dd class="col-sm-9">{{ $repairService->customer_name }} {{ $repairService->customer_phone }} {{ $repairService->customer_address }}</dd>
    <dt class="col-sm-3">Charged</dt><dd class="col-sm-9">Rs. {{ number_format($repairService->charged_price, 2) }}</dd>
    <dt class="col-sm-3">Profit</dt><dd class="col-sm-9">Rs. {{ number_format($repairService->profit, 2) }}</dd>
    <dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $repairService->description }}</dd>
</dl></div></div>@endsection
