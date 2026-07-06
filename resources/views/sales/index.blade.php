@extends('layouts.app')
@section('page_title', 'Sales')
@section('actions')<a class="btn btn-primary" href="{{ route('sales.create') }}">Add Sale</a>@endsection
@section('content')
<form method="GET" action="{{ route('sales.index') }}" class="card content-card mb-3 no-print">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Search</label>
            <input class="form-control" name="search" value="{{ old('search', request('search')) }}" placeholder="Receipt ID, Customer, Item...">
        </div>
        <div class="col-md-3">
            <label class="form-label">Payment Mode</label>
            <select class="form-select" name="payment_mode">
                <option value="">All Payments</option>
                @foreach($paymentModes as $mode)
                    <option value="{{ $mode }}" @selected(request('payment_mode') === $mode)>{{ $mode }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">From Date</label>
            <input class="form-control" type="date" name="date_from" value="{{ old('date_from', request('date_from')) }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">To Date</label>
            <input class="form-control" type="date" name="date_to" value="{{ old('date_to', request('date_to')) }}">
        </div>
        <div class="col-md-auto">
            <button class="btn btn-primary">Filter</button>
            <a class="btn btn-light" href="{{ route('sales.index') }}">Reset</a>
        </div>
    </div>
</form>
<div class="card content-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Receipt #</th>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Available Stock</th>
                    <th>Customer</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>#{{ str_pad((string) $sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \App\Support\DateFormatter::human($sale->date) }}</td>
                    <td>{{ $sale->item?->name }}</td>
                    <td>{{ $sale->item?->current_stock ?? '-' }}</td>
                    <td>{{ $sale->customer_name }}<br><small class="text-muted">{{ $sale->customer_phone }}</small></td>
                    <td>{{ $sale->quantity }}</td>
                    <td>Rs. {{ number_format($sale->total_sales_price, 2) }}</td>
                    <td>{{ $sale->payment_mode }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-secondary icon-btn" href="{{ route('sales.show', $sale) }}" title="View" aria-label="View sale #{{ $sale->id }}"><svg><use href="#icon-eye"></use></svg></a>
                        <a class="btn btn-sm btn-outline-success icon-btn" href="{{ route('sales.receipt', $sale) }}" title="Receipt" aria-label="Receipt for sale #{{ $sale->id }}"><svg><use href="#icon-receipt"></use></svg></a>
                        <a class="btn btn-sm btn-outline-primary icon-btn" href="{{ route('sales.edit', $sale) }}" title="Edit" aria-label="Edit sale #{{ $sale->id }}"><svg><use href="#icon-pencil"></use></svg></a>
                        <form class="d-inline" method="POST" action="{{ route('sales.destroy', $sale) }}" onsubmit="return confirm('Delete this sale? Stock will be restored.')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger icon-btn" title="Delete" aria-label="Delete sale #{{ $sale->id }}"><svg><use href="#icon-trash"></use></svg></button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-muted">No sales found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $sales->links() }}</div>
</div>
@endsection
