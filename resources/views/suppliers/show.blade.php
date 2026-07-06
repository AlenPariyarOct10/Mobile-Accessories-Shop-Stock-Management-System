@extends('layouts.app')
@section('page_title', 'Supplier Details')
@section('actions')<a class="btn btn-primary" href="{{ route('stock-entries.create', ['supplier_id' => $supplier->id]) }}">Add Stock</a> <a class="btn btn-light" href="{{ route('suppliers.edit', $supplier) }}">Edit</a>@endsection
@section('content')
<div class="card content-card mb-3"><div class="card-body">
    <dl class="row mb-0">
        <dt class="col-sm-3">Name</dt><dd class="col-sm-9">{{ $supplier->name }}</dd>
        <dt class="col-sm-3">Phone</dt><dd class="col-sm-9">{{ $supplier->phone }}</dd>
        <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $supplier->email }}</dd>
        <dt class="col-sm-3">Address</dt><dd class="col-sm-9">{{ $supplier->address }}</dd>
        <dt class="col-sm-3">Notes</dt><dd class="col-sm-9">{{ $supplier->notes }}</dd>
    </dl>
</div></div>

<form method="GET" action="{{ route('suppliers.show', $supplier) }}" class="card content-card mb-3 no-print">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Search Received Logs</label>
            <input class="form-control" name="search" value="{{ $search }}" placeholder="Item, SKU, notes">
        </div>
        <div class="col-md-3">
            <label class="form-label">Item</label>
            <select class="form-select" name="item_id">
                <option value="">All items</option>
                @foreach($receivedItems as $item)
                    <option value="{{ $item->id }}" @selected($itemId == $item->id)>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">From Date</label>
            <input class="form-control" type="date" name="date_from" value="{{ $dateFrom }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">To Date</label>
            <input class="form-control" type="date" name="date_to" value="{{ $dateTo }}">
        </div>
        <div class="col-md-auto">
            <button class="btn btn-primary">Filter</button>
            <a class="btn btn-light" href="{{ route('suppliers.show', $supplier) }}">Reset</a>
        </div>
    </div>
</form>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card content-card"><div class="card-body">
            <div class="text-muted small">Filtered Quantity Received</div>
            <div class="h4 mb-0">{{ number_format((int) $receivedTotals->quantity) }}</div>
        </div></div>
    </div>
    <div class="col-md-6">
        <div class="card content-card"><div class="card-body">
            <div class="text-muted small">Filtered Purchase Amount</div>
            <div class="h4 mb-0">Rs. {{ number_format((float) $receivedTotals->amount, 2) }}</div>
        </div></div>
    </div>
</div>

<div class="card content-card">
    <div class="card-header">Received Logs</div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Date</th><th>Item</th><th>SKU</th><th>Qty</th><th>Price</th><th>Total</th><th>Notes</th><th class="text-end">Actions</th></tr></thead>
            <tbody>@forelse($receivedLogs as $entry)<tr>
                <td>{{ \App\Support\DateFormatter::human($entry->date) }}</td>
                <td>{{ $entry->item?->name }}</td>
                <td>{{ $entry->item?->code }}</td>
                <td>{{ $entry->quantity }}</td>
                <td>Rs. {{ number_format($entry->price_per_item, 2) }}</td>
                <td>Rs. {{ number_format($entry->total_purchase_price, 2) }}</td>
                <td>{{ $entry->notes }}</td>
                <td class="text-end">
                    <a class="btn btn-sm btn-outline-secondary icon-btn" href="{{ route('stock-entries.show', $entry) }}" title="View" aria-label="View stock entry #{{ $entry->id }}"><svg><use href="#icon-eye"></use></svg></a>
                    <a class="btn btn-sm btn-outline-primary icon-btn" href="{{ route('stock-entries.edit', $entry) }}" title="Edit" aria-label="Edit stock entry #{{ $entry->id }}"><svg><use href="#icon-pencil"></use></svg></a>
                </td>
            </tr>@empty<tr><td colspan="8" class="text-muted">No received logs found for this supplier.</td></tr>@endforelse</tbody>
        </table>
    </div>
    <div class="card-body">{{ $receivedLogs->links() }}</div>
</div>
@endsection
