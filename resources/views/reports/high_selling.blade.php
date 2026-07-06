@extends('layouts.app')
@section('page_title', 'High-Selling Items')
@section('actions')<a class="btn btn-success" href="{{ route('reports.high_selling.export') }}">CSV Export</a>@endsection
@section('content')
@include('reports._nav')
<div class="card content-card"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Item</th><th>Quantity Sold</th><th>Sales Amount</th><th>Estimated Profit</th></tr></thead><tbody>@forelse($rows as $row)<tr><td>{{ $row->item_name }}</td><td>{{ $row->total_quantity }}</td><td>Rs. {{ number_format($row->total_sales, 2) }}</td><td>Rs. {{ number_format($row->estimated_profit, 2) }}</td></tr>@empty<tr><td colspan="4" class="text-muted">No records.</td></tr>@endforelse</tbody></table></div><div class="card-body">{{ $rows->links() }}</div></div>
@endsection
