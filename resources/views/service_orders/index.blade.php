@extends('layouts.app')
@section('page_title', 'Service Orders')
@section('actions')<a class="btn btn-primary" href="{{ route('service-orders.create') }}">Add Order</a>@endsection
@section('content')
<div class="card content-card"><div class="table-responsive"><table class="table align-middle mb-0">
    <thead><tr><th>Date</th><th>Expected</th><th>Service</th><th>Customer</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
    <tbody>@forelse($serviceOrders as $order)<tr>
        <td>{{ \App\Support\DateFormatter::human($order->date) }}</td><td>{{ \App\Support\DateFormatter::human($order->expected_at, true) }}</td><td>{{ $order->service_type }}</td><td>{{ $order->customer_name }}<br><small class="text-muted">{{ $order->customer_phone }}</small></td><td>{{ $order->status }}</td>
        <td class="text-end">@if($order->status === 'Pending')<a class="btn btn-sm btn-success" href="{{ route('service-orders.complete', $order) }}">Complete</a>@endif <a class="btn btn-sm btn-outline-secondary icon-btn" href="{{ route('service-orders.show', $order) }}" title="View" aria-label="View service order #{{ $order->id }}"><svg><use href="#icon-eye"></use></svg></a> <a class="btn btn-sm btn-outline-primary icon-btn" href="{{ route('service-orders.edit', $order) }}" title="Edit" aria-label="Edit service order #{{ $order->id }}"><svg><use href="#icon-pencil"></use></svg></a> <form class="d-inline" method="POST" action="{{ route('service-orders.destroy', $order) }}" onsubmit="return confirm('Delete this service order?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger icon-btn" title="Delete" aria-label="Delete service order #{{ $order->id }}"><svg><use href="#icon-trash"></use></svg></button></form></td>
    </tr>@empty<tr><td colspan="6" class="text-muted">No service orders found.</td></tr>@endforelse</tbody>
</table></div><div class="card-body">{{ $serviceOrders->links() }}</div></div>
@endsection
