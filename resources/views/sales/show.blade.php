@extends('layouts.app')
@section('page_title', 'Sale Details')
@section('actions')<a class="btn btn-success" href="{{ route('sales.receipt', $sale) }}">Generate Receipt</a> <a class="btn btn-light" href="{{ route('sales.index') }}">Back</a>@endsection
@section('content')<div class="card content-card"><div class="card-body"><dl class="row mb-0">
    <dt class="col-sm-3">Date</dt><dd class="col-sm-9">{{ \App\Support\DateFormatter::human($sale->sold_at, true) }}</dd>
    <dt class="col-sm-3">Item</dt><dd class="col-sm-9">{{ $sale->item?->name }}</dd>
    <dt class="col-sm-3">Customer</dt><dd class="col-sm-9">{{ $sale->customer_name }} {{ $sale->customer_phone }} {{ $sale->customer_address }}</dd>
    <dt class="col-sm-3">Quantity</dt><dd class="col-sm-9">{{ $sale->quantity }}</dd>
    <dt class="col-sm-3">Total</dt><dd class="col-sm-9">Rs. {{ number_format($sale->total_sales_price, 2) }}</dd>
    <dt class="col-sm-3">Profit</dt><dd class="col-sm-9">Rs. {{ number_format($sale->profit, 2) }}</dd>
</dl></div></div>@endsection
