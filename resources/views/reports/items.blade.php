@extends('layouts.app')
@section('page_title', 'Item Report')
@section('actions')<a class="btn btn-success" href="{{ route('reports.items.export') }}">CSV Export</a>@endsection
@section('content')
@include('reports._nav')
<div class="card content-card"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Name</th><th>SKU</th><th>Purchase</th><th>Selling</th><th>Stock</th><th>Active</th></tr></thead><tbody>@foreach($items as $item)<tr><td>{{ $item->name }}</td><td>{{ $item->code }}</td><td>Rs. {{ number_format($item->default_purchase_price, 2) }}</td><td>Rs. {{ number_format($item->default_selling_price, 2) }}</td><td>{{ $item->current_stock }}</td><td>{{ $item->is_active ? 'Yes' : 'No' }}</td></tr>@endforeach</tbody></table></div><div class="card-body">{{ $items->links() }}</div></div>
@endsection
