@extends('layouts.app')
@section('page_title', 'Items')
@section('actions')<a class="btn btn-primary" href="{{ route('items.create') }}">Add Item</a>@endsection
@section('content')
<form method="GET" action="{{ route('items.index') }}" class="card content-card mb-3 no-print">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Search</label>
            <input class="form-control" name="search" value="{{ old('search', $search) }}" placeholder="Name or SKU...">
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status">
                <option value="">All Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Sort By</label>
            <select class="form-select" name="sort_by">
                <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Date Created</option>
                <option value="name" {{ $sortBy === 'name' ? 'selected' : '' }}>Name</option>
                <option value="current_stock" {{ $sortBy === 'current_stock' ? 'selected' : '' }}>Stock</option>
                <option value="default_selling_price" {{ $sortBy === 'default_selling_price' ? 'selected' : '' }}>Price</option>
            </select>
        </div>
        <div class="col-md-auto">
            <button class="btn btn-primary">Filter</button>
            <a class="btn btn-light" href="{{ route('items.index') }}">Reset</a>
        </div>
    </div>
</form>
<div class="card content-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>
                        <a href="{{ route('items.index', array_merge(request()->except('page'), ['sort_by' => 'name', 'sort_direction' => $sortBy === 'name' && $sortDirection === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                            Name
                            @if($sortBy === 'name')<span class="ms-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>@endif
                        </a>
                    </th>
                    <th>SKU</th>
                    <th>
                        <a href="{{ route('items.index', array_merge(request()->except('page'), ['sort_by' => 'current_stock', 'sort_direction' => $sortBy === 'current_stock' && $sortDirection === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                            Available Stock
                            @if($sortBy === 'current_stock')<span class="ms-1">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>@endif
                        </a>
                    </th>
                    <th>Selling Price</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>@if($item->image)<img class="thumb" src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}">@endif</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->code }}</td>
                    <td><span class="badge {{ $item->current_stock <= 0 ? 'bg-danger' : ($item->current_stock <= $item->low_stock_alert_quantity ? 'bg-warning text-dark' : 'bg-success') }}">{{ $item->current_stock }}</span></td>
                    <td>Rs. {{ number_format($item->default_selling_price, 2) }}</td>
                    <td>{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="text-end">
                        @if($item->is_active && $item->current_stock > 0)
                            <a class="btn btn-sm btn-success icon-btn" href="{{ route('sales.create', ['item_id' => $item->id]) }}" title="Sell" aria-label="Sell {{ $item->name }}"><svg><use href="#icon-cart"></use></svg></a>
                        @else
                            <button class="btn btn-sm btn-outline-secondary icon-btn" type="button" disabled title="Sell" aria-label="Sell {{ $item->name }}"><svg><use href="#icon-cart"></use></svg></button>
                        @endif
                        <a class="btn btn-sm btn-outline-secondary icon-btn" href="{{ route('items.show', $item) }}" title="View" aria-label="View {{ $item->name }}"><svg><use href="#icon-eye"></use></svg></a>
                        <a class="btn btn-sm btn-outline-primary icon-btn" href="{{ route('items.edit', $item) }}" title="Edit" aria-label="Edit {{ $item->name }}"><svg><use href="#icon-pencil"></use></svg></a>
                        <form class="d-inline" method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Delete this item?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger icon-btn" title="Delete" aria-label="Delete {{ $item->name }}"><svg><use href="#icon-trash"></use></svg></button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-muted">No items found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $items->links() }}</div>
</div>
@endsection
