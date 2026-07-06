@extends('layouts.app')
@section('page_title', 'Item Details')
@section('actions')
<a class="btn btn-primary" href="{{ route('items.edit', $item) }}">Edit Item</a>
<a class="btn btn-light" href="{{ route('items.index') }}">Back to Items</a>
@endsection
@section('content')
<div class="card content-card mb-3">
    <div class="card-body">
        @if($item->image)<img class="thumb mb-3" src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}">@endif
        <dl class="row mb-0">
            <dt class="col-sm-3">Name</dt><dd class="col-sm-9">{{ $item->name }}</dd>
            <dt class="col-sm-3">SKU</dt><dd class="col-sm-9">{{ $item->code }}</dd>
            <dt class="col-sm-3">Available Stock</dt><dd class="col-sm-9"><span class="badge {{ $item->current_stock <= 0 ? 'bg-danger' : ($item->current_stock <= $item->low_stock_alert_quantity ? 'bg-warning text-dark' : 'bg-success') }}">{{ $item->current_stock }}</span></dd>
            <dt class="col-sm-3">Purchase Price</dt><dd class="col-sm-9">Rs. {{ number_format($item->default_purchase_price, 2) }}</dd>
            <dt class="col-sm-3">Selling Price</dt><dd class="col-sm-9">Rs. {{ number_format($item->default_selling_price, 2) }}</dd>
            <dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $item->description }}</dd>
        </dl>
    </div>
</div>

@if($item->sales->isNotEmpty())
<div class="card content-card mb-3">
    <div class="card-header bg-white">Sales History</div>
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            <thead>
                <tr>
                    <th>Receipt #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item->sales as $sale)
                <tr>
                    <td>#{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \App\Support\DateFormatter::human($sale->date) }}</td>
                    <td>{{ $sale->customer_name ?: 'Walk-in Customer' }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>Rs. {{ number_format($sale->price_per_item, 2) }}</td>
                    <td>Rs. {{ number_format($sale->total_sales_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($item->stockEntries->isNotEmpty())
<div class="card content-card">
    <div class="card-header bg-white">Stock Entries History</div>
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Supplier</th>
                    <th>Quantity</th>
                    <th>Purchase Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item->stockEntries as $entry)
                <tr>
                    <td>{{ \App\Support\DateFormatter::human($entry->date) }}</td>
                    <td>{{ $entry->supplier->name ?? 'N/A' }}</td>
                    <td>{{ $entry->quantity }}</td>
                    <td>Rs. {{ number_format($entry->price_per_item, 2) }}</td>
                    <td>Rs. {{ number_format($entry->total_purchase_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
