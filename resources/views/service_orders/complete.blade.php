@extends('layouts.app')
@section('page_title', 'Complete Service Order')
@section('content')
<div class="card content-card mb-3"><div class="card-body">
    <h2 class="h6 mb-3">{{ $serviceOrder->service_type }}</h2>
    <div class="row g-2 small text-muted">
        <div class="col-md-4">Customer: {{ $serviceOrder->customer_name ?: '-' }}</div>
        <div class="col-md-4">Phone: {{ $serviceOrder->customer_phone ?: '-' }}</div>
        <div class="col-md-4">Expected: {{ \App\Support\DateFormatter::human($serviceOrder->expected_at, true) }}</div>
    </div>
    @if($serviceOrder->description)<p class="mb-0 mt-3">{{ $serviceOrder->description }}</p>@endif
</div></div>
<div class="card content-card"><div class="card-body">
    @include('partials.errors')
    <form method="POST" action="{{ route('service-orders.complete.store', $serviceOrder) }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-3"><label class="form-label">Service Date</label><input class="form-control" type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required></div>
            <div class="col-md-3"><label class="form-label">Completed At</label><input class="form-control" type="datetime-local" name="serviced_at" value="{{ old('serviced_at', now()->format('Y-m-d\\TH:i')) }}" required></div>
            <div class="col-md-3"><label class="form-label">Cost Price</label><input class="form-control service-cost" type="number" step="0.01" min="0" name="cost_price" value="{{ old('cost_price', 0) }}"></div>
            <div class="col-md-3"><label class="form-label">Charged Price</label><input class="form-control service-charged" type="number" step="0.01" min="0" name="charged_price" value="{{ old('charged_price', 0) }}" required></div>
            <div class="col-md-3"><label class="form-label">Profit</label><input class="form-control service-profit" type="number" step="0.01" readonly></div>
            <div class="col-md-3"><label class="form-label">Payment</label><select class="form-select" name="payment_mode">@foreach($paymentModes as $mode)<option value="{{ $mode }}" @selected(old('payment_mode', 'Cash') === $mode)>{{ $mode }}</option>@endforeach</select></div>
            <div class="col-md-6"><label class="form-label">Completion Notes</label><input class="form-control" name="notes" value="{{ old('notes', $serviceOrder->notes) }}"></div>
        </div>
        <div class="mt-3"><button class="btn btn-success">Complete and Move to Services</button><a class="btn btn-light" href="{{ route('repair-services.index') }}">Cancel</a></div>
    </form>
</div></div>
@push('scripts')<script>function calcService(){const c=parseFloat(document.querySelector('.service-cost')?.value||0),p=parseFloat(document.querySelector('.service-charged')?.value||0);document.querySelector('.service-profit').value=(p-c).toFixed(2)}document.querySelectorAll('.service-cost,.service-charged').forEach(el=>el.addEventListener('input',calcService));calcService();</script>@endpush
@endsection
