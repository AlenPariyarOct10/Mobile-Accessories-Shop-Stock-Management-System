@extends('layouts.app')
@section('page_title', 'Suppliers')
@section('actions')<a class="btn btn-primary" href="{{ route('suppliers.create') }}">Add Supplier</a>@endsection
@section('content')
<form method="GET" action="{{ route('suppliers.index') }}" class="card content-card mb-3 no-print">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Search</label>
            <input class="form-control" name="search" value="{{ old('search', request('search')) }}" placeholder="Name, Phone, Email...">
        </div>
        <div class="col-md-auto">
            <button class="btn btn-primary">Filter</button>
            <a class="btn btn-light" href="{{ route('suppliers.index') }}">Reset</a>
        </div>
    </div>
</form>
<div class="card content-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>{{ $supplier->email }}</td>
                    <td>{{ $supplier->address }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-secondary icon-btn" href="{{ route('suppliers.show', $supplier) }}" title="View" aria-label="View {{ $supplier->name }}"><svg><use href="#icon-eye"></use></svg></a>
                        <a class="btn btn-sm btn-outline-primary icon-btn" href="{{ route('suppliers.edit', $supplier) }}" title="Edit" aria-label="Edit {{ $supplier->name }}"><svg><use href="#icon-pencil"></use></svg></a>
                        <form class="d-inline" method="POST" action="{{ route('suppliers.destroy', $supplier) }}" onsubmit="return confirm('Delete this supplier?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger icon-btn" title="Delete" aria-label="Delete {{ $supplier->name }}"><svg><use href="#icon-trash"></use></svg></button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">No suppliers found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $suppliers->links() }}</div>
</div>
@endsection
