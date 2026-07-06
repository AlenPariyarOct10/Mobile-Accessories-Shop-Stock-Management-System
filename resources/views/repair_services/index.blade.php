@extends('layouts.app')
@section('page_title', 'Repair / Services')
@section('actions')<a class="btn btn-primary" href="{{ route('service-orders.create') }}">Add Order</a> <a class="btn btn-light" href="{{ route('repair-services.create') }}">Add Service</a>@endsection
@section('content')
<div class="card content-card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Pending Service Orders</span>
        <a class="btn btn-sm btn-outline-primary" href="{{ route('service-orders.index') }}">All Orders</a>
    </div>
    <div class="table-responsive"><table class="table align-middle mb-0">
        <thead><tr><th>Order Date</th><th>Expected</th><th>Service</th><th>Customer</th><th>Notes</th><th class="text-end">Actions</th></tr></thead>
        <tbody>@forelse($pendingServiceOrders as $order)<tr>
            <td>{{ \App\Support\DateFormatter::human($order->date) }}</td>
            <td>{{ \App\Support\DateFormatter::human($order->expected_at, true) }}</td>
            <td>{{ $order->service_type }}</td>
            <td>{{ $order->customer_name }}<br><small class="text-muted">{{ $order->customer_phone }}</small></td>
            <td>{{ $order->notes }}</td>
            <td class="text-end"><a class="btn btn-sm btn-success" href="{{ route('service-orders.complete', $order) }}">Complete</a> <a class="btn btn-sm btn-outline-secondary icon-btn" href="{{ route('service-orders.show', $order) }}" title="View" aria-label="View service order #{{ $order->id }}"><svg><use href="#icon-eye"></use></svg></a> <a class="btn btn-sm btn-outline-primary icon-btn" href="{{ route('service-orders.edit', $order) }}" title="Edit" aria-label="Edit service order #{{ $order->id }}"><svg><use href="#icon-pencil"></use></svg></a></td>
        </tr>@empty<tr><td colspan="6" class="text-muted">No pending service orders.</td></tr>@endforelse</tbody>
    </table></div>
</div>

<div class="card content-card"><div class="table-responsive"><table class="table align-middle mb-0">
    <thead><tr><th>Date</th><th>Service</th><th>Customer</th><th>Charged</th><th>Profit</th><th>Payment</th><th class="text-end">Actions</th></tr></thead>
    <tbody>@forelse($repairServices as $service)<tr>
        <td>{{ \App\Support\DateFormatter::human($service->date) }}</td><td>{{ $service->service_type }}</td><td>{{ $service->customer_name }}<br><small class="text-muted">{{ $service->customer_phone }}</small></td><td>Rs. {{ number_format($service->charged_price, 2) }}</td><td>Rs. {{ number_format($service->profit, 2) }}</td><td>{{ $service->payment_mode }}</td>
        <td class="text-end"><a class="btn btn-sm btn-outline-secondary icon-btn" href="{{ route('repair-services.show', $service) }}" title="View" aria-label="View service #{{ $service->id }}"><svg><use href="#icon-eye"></use></svg></a> <a class="btn btn-sm btn-outline-primary icon-btn" href="{{ route('repair-services.edit', $service) }}" title="Edit" aria-label="Edit service #{{ $service->id }}"><svg><use href="#icon-pencil"></use></svg></a> <form class="d-inline" method="POST" action="{{ route('repair-services.destroy', $service) }}" onsubmit="return confirm('Delete this service record?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger icon-btn" title="Delete" aria-label="Delete service #{{ $service->id }}"><svg><use href="#icon-trash"></use></svg></button></form></td>
    </tr>@empty<tr><td colspan="7" class="text-muted">No service records found.</td></tr>@endforelse</tbody>
</table></div><div class="card-body">{{ $repairServices->links() }}</div></div>
@endsection
