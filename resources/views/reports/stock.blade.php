@extends('layouts.app')
@section('page_title', 'Stock Report')
@section('actions')<a class="btn btn-success" href="{{ route('reports.stock.export', request()->query()) }}">CSV Export</a>@endsection
@section('content')
@include('reports._nav')
<form class="card content-card mb-3 no-print"><div class="card-body row g-2"><div class="col-md-3"><input class="form-control" type="date" name="from_date" value="{{ request('from_date') }}"></div><div class="col-md-3"><input class="form-control" type="date" name="to_date" value="{{ request('to_date') }}"></div><div class="col-md-3"><select class="form-select" name="item_id"><option value="">All items</option>@foreach($items as $item)<option value="{{ $item->id }}" @selected(request('item_id') == $item->id)>{{ $item->name }}</option>@endforeach</select></div><div class="col-md-3"><button class="btn btn-primary">Filter</button></div></div></form>
<div class="card content-card"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Date</th><th>Item</th><th>Supplier</th><th>Qty</th><th>Total</th></tr></thead><tbody>@forelse($entries as $entry)<tr><td>{{ \App\Support\DateFormatter::human($entry->date) }}</td><td>{{ $entry->item?->name }}</td><td>{{ $entry->supplier?->name }}</td><td>{{ $entry->quantity }}</td><td>Rs. {{ number_format($entry->total_purchase_price, 2) }}</td></tr>@empty<tr><td colspan="5" class="text-muted">No records.</td></tr>@endforelse</tbody></table></div><div class="card-body">{{ $entries->links() }}</div></div>
@endsection
