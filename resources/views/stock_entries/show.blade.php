@extends('layouts.app')
@section('page_title', 'Stock Entry Details')
@section('content')<div class="card content-card"><div class="card-body"><dl class="row mb-0">
    <dt class="col-sm-3">Date</dt><dd class="col-sm-9">{{ \App\Support\DateFormatter::human($stockEntry->date) }}</dd>
    <dt class="col-sm-3">Item</dt><dd class="col-sm-9">{{ $stockEntry->item?->name }}</dd>
    <dt class="col-sm-3">Available Stock</dt><dd class="col-sm-9">{{ $stockEntry->item?->current_stock ?? '-' }}</dd>
    <dt class="col-sm-3">Supplier</dt><dd class="col-sm-9">{{ $stockEntry->supplier?->name }}</dd>
    <dt class="col-sm-3">Quantity</dt><dd class="col-sm-9">{{ $stockEntry->quantity }}</dd>
    <dt class="col-sm-3">Price Per Item</dt><dd class="col-sm-9">Rs. {{ number_format($stockEntry->price_per_item, 2) }}</dd>
    <dt class="col-sm-3">Total</dt><dd class="col-sm-9">Rs. {{ number_format($stockEntry->total_purchase_price, 2) }}</dd>
    <dt class="col-sm-3">Notes</dt><dd class="col-sm-9">{{ $stockEntry->notes }}</dd>
</dl></div></div>@endsection
