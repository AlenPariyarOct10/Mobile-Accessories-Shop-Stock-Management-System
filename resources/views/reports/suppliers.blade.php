@extends('layouts.app')
@section('page_title', 'Supplier Report')
@section('actions')<a class="btn btn-success" href="{{ route('reports.suppliers.export') }}">CSV Export</a>@endsection
@section('content')
@include('reports._nav')
<div class="card content-card"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Name</th><th>Phone</th><th>Email</th><th>Address</th><th>Stock Entries</th></tr></thead><tbody>@foreach($suppliers as $supplier)<tr><td>{{ $supplier->name }}</td><td>{{ $supplier->phone }}</td><td>{{ $supplier->email }}</td><td>{{ $supplier->address }}</td><td>{{ $supplier->stock_entries_count }}</td></tr>@endforeach</tbody></table></div><div class="card-body">{{ $suppliers->links() }}</div></div>
@endsection
