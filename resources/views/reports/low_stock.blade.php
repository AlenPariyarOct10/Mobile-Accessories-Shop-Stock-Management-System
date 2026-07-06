@extends('layouts.app')
@section('page_title', 'Low-Stock Items')
@section('actions')<a class="btn btn-success" href="{{ route('reports.low_stock.export') }}">CSV Export</a>@endsection
@section('content')
@include('reports._nav')
<div class="card content-card"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Item</th><th>SKU</th><th>Current Stock</th><th>Alert Quantity</th></tr></thead><tbody>@forelse($items as $item)<tr><td>{{ $item->name }}</td><td>{{ $item->code }}</td><td>{{ $item->current_stock }}</td><td>{{ $item->low_stock_alert_quantity }}</td></tr>@empty<tr><td colspan="4" class="text-muted">No low-stock items.</td></tr>@endforelse</tbody></table></div><div class="card-body">{{ $items->links() }}</div></div>
@endsection
