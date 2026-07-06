@extends('layouts.app')
@section('page_title', 'Expenses')
@section('actions')<a class="btn btn-primary" href="{{ route('expenses.create') }}">Add Expense</a>@endsection
@section('content')
<form method="GET" action="{{ route('expenses.index') }}" class="card content-card mb-3 no-print">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label">Search</label><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Title, vendor, notes"></div>
        <div class="col-md-2"><label class="form-label">Category</label><select class="form-select" name="category"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>@endforeach</select></div>
        <div class="col-md-2"><label class="form-label">Payment</label><select class="form-select" name="payment_mode"><option value="">All payments</option>@foreach($paymentModes as $mode)<option value="{{ $mode }}" @selected(request('payment_mode') === $mode)>{{ $mode }}</option>@endforeach</select></div>
        <div class="col-md-2"><label class="form-label">From Date</label><input class="form-control" type="date" name="date_from" value="{{ request('date_from') }}"></div>
        <div class="col-md-2"><label class="form-label">To Date</label><input class="form-control" type="date" name="date_to" value="{{ request('date_to') }}"></div>
        <div class="col-md-auto"><button class="btn btn-primary">Filter</button><a class="btn btn-light" href="{{ route('expenses.index') }}">Reset</a></div>
    </div>
</form>

<div class="card content-card mb-3"><div class="card-body d-flex justify-content-between align-items-center">
    <div><div class="text-muted small">Filtered Expenses</div><div class="h4 mb-0">Rs. {{ number_format($totalExpenses, 2) }}</div></div>
    <a class="btn btn-outline-primary" href="{{ route('expenses.create') }}">Log Expense</a>
</div></div>

<div class="card content-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Date</th><th>Title</th><th>Category</th><th>Vendor</th><th>Payment</th><th>Amount</th><th class="text-end">Actions</th></tr></thead>
            <tbody>@forelse($expenses as $expense)<tr>
                <td>{{ \App\Support\DateFormatter::human($expense->date) }}</td>
                <td>{{ $expense->title }}<br><small class="text-muted">{{ $expense->notes }}</small></td>
                <td>{{ $expense->category }}</td>
                <td>{{ $expense->vendor }}</td>
                <td>{{ $expense->payment_mode }}</td>
                <td>Rs. {{ number_format($expense->amount, 2) }}</td>
                <td class="text-end"><a class="btn btn-sm btn-outline-secondary icon-btn" href="{{ route('expenses.show', $expense) }}" title="View" aria-label="View expense #{{ $expense->id }}"><svg><use href="#icon-eye"></use></svg></a> <a class="btn btn-sm btn-outline-primary icon-btn" href="{{ route('expenses.edit', $expense) }}" title="Edit" aria-label="Edit expense #{{ $expense->id }}"><svg><use href="#icon-pencil"></use></svg></a> <form class="d-inline" method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('Delete this expense?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger icon-btn" title="Delete" aria-label="Delete expense #{{ $expense->id }}"><svg><use href="#icon-trash"></use></svg></button></form></td>
            </tr>@empty<tr><td colspan="7" class="text-muted">No expenses found.</td></tr>@endforelse</tbody>
        </table>
    </div>
    <div class="card-body">{{ $expenses->links() }}</div>
</div>
@endsection
