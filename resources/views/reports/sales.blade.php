@extends('layouts.app')
@section('page_title', 'Sales Report')
@section('actions')<a class="btn btn-success" href="{{ route('reports.sales.export', request()->query()) }}">CSV Export</a>@endsection
@section('content')
@include('reports._nav')
<form class="card content-card mb-3 no-print"><div class="card-body row g-2">
    <div class="col-md-2"><input class="form-control" type="date" name="from_date" value="{{ request('from_date') }}"></div>
    <div class="col-md-2"><input class="form-control" type="date" name="to_date" value="{{ request('to_date') }}"></div>
    <div class="col-md-2"><select class="form-select" name="item_id"><option value="">All items</option>@foreach($items as $item)<option value="{{ $item->id }}" @selected(request('item_id') == $item->id)>{{ $item->name }}</option>@endforeach</select></div>
    <div class="col-md-2"><select class="form-select" name="payment_mode"><option value="">All payments</option>@foreach($paymentModes as $mode)<option value="{{ $mode }}" @selected(request('payment_mode') === $mode)>{{ $mode }}</option>@endforeach</select></div>
    <div class="col-md-2"><input class="form-control" name="customer_name" value="{{ request('customer_name') }}" placeholder="Customer"></div>
    <div class="col-md-2"><input class="form-control" name="customer_phone" value="{{ request('customer_phone') }}" placeholder="Phone"></div>
    <div class="col-12"><button class="btn btn-primary">Filter</button><a class="btn btn-light" href="{{ route('reports.sales') }}">Reset</a></div>
</div></form>
<div class="row g-3 mb-3"><div class="col-md-6"><div class="card content-card"><div class="card-body">Sales Total: <strong>Rs. {{ number_format($totalSales, 2) }}</strong></div></div></div><div class="col-md-6"><div class="card content-card"><div class="card-body">Profit: <strong>Rs. {{ number_format($totalProfit, 2) }}</strong></div></div></div></div>
<div class="card content-card"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Date</th><th>Item</th><th>Customer</th><th>Qty</th><th>Total</th><th>Profit</th><th>Payment</th></tr></thead><tbody>@forelse($sales as $sale)<tr><td>{{ \App\Support\DateFormatter::human($sale->date) }}</td><td>{{ $sale->item?->name }}</td><td>{{ $sale->customer_name }} {{ $sale->customer_phone }}</td><td>{{ $sale->quantity }}</td><td>Rs. {{ number_format($sale->total_sales_price, 2) }}</td><td>Rs. {{ number_format($sale->profit, 2) }}</td><td>{{ $sale->payment_mode }}</td></tr>@empty<tr><td colspan="7" class="text-muted">No records.</td></tr>@endforelse</tbody></table></div><div class="card-body">{{ $sales->links() }}</div></div>
@endsection
