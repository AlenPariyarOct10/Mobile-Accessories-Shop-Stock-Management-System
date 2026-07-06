@extends('layouts.app')
@section('page_title', 'Stock Entries')
@section('actions')<a class="btn btn-primary" href="{{ route('stock-entries.create') }}">Add Stock</a>@endsection
@section('content')
<form method="GET" action="{{ route('stock-entries.index') }}" class="card content-card mb-3 no-print">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Search Item</label>
            <input class="form-control" name="search" value="{{ old('search', request('search')) }}" placeholder="Item name...">
        </div>
        <div class="col-md-3">
            <label class="form-label">Supplier</label>
            <select class="form-select" name="supplier_id">
                <option value="">All Suppliers</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
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
            <a class="btn btn-light" href="{{ route('stock-entries.index') }}">Reset</a>
        </div>
    </div>
</form>
<div class="card content-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Available Stock</th>
                    <th>Supplier</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($stockEntries as $entry)
                <tr>
                    <td>{{ \App\Support\DateFormatter::human($entry->date) }}</td>
                    <td>{{ $entry->item?->name }}</td>
                    <td>{{ $entry->item?->current_stock ?? '-' }}</td>
                    <td>{{ $entry->supplier?->name }}</td>
                    <td>{{ $entry->quantity }}</td>
                    <td>Rs. {{ number_format($entry->price_per_item, 2) }}</td>
                    <td>Rs. {{ number_format($entry->total_purchase_price, 2) }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-secondary icon-btn" href="{{ route('stock-entries.show', $entry) }}" title="View" aria-label="View stock entry #{{ $entry->id }}"><svg><use href="#icon-eye"></use></svg></a>
                        <a class="btn btn-sm btn-outline-primary icon-btn" href="{{ route('stock-entries.edit', $entry) }}" title="Edit" aria-label="Edit stock entry #{{ $entry->id }}"><svg><use href="#icon-pencil"></use></svg></a>
                        <form class="d-inline" method="POST" action="{{ route('stock-entries.destroy', $entry) }}" onsubmit="return confirm('Delete this stock entry?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger icon-btn" title="Delete" aria-label="Delete stock entry #{{ $entry->id }}"><svg><use href="#icon-trash"></use></svg></button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-muted">No stock entries found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $stockEntries->links() }}</div>
</div>
@endsection
