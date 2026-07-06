@include('partials.errors')
<div class="row g-3">
    <div class="col-md-3"><label class="form-label">Order Date</label><input class="form-control" type="date" name="date" value="{{ old('date', isset($serviceOrder) ? $serviceOrder->date->format('Y-m-d') : now()->format('Y-m-d')) }}" required></div>
    <div class="col-md-3"><label class="form-label">Expected Date / Time</label><input class="form-control" type="datetime-local" name="expected_at" value="{{ old('expected_at', isset($serviceOrder) && $serviceOrder->expected_at ? $serviceOrder->expected_at->format('Y-m-d\\TH:i') : '') }}"></div>
    <div class="col-md-6"><label class="form-label">Service Type</label><input class="form-control" name="service_type" value="{{ old('service_type', $serviceOrder->service_type ?? '') }}" required></div>
    <div class="col-md-4"><label class="form-label">Customer Name</label><input class="form-control" name="customer_name" value="{{ old('customer_name', $serviceOrder->customer_name ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Customer Phone</label><input class="form-control" name="customer_phone" value="{{ old('customer_phone', $serviceOrder->customer_phone ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Customer Address</label><input class="form-control" name="customer_address" value="{{ old('customer_address', $serviceOrder->customer_address ?? '') }}"></div>
    <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3">{{ old('description', $serviceOrder->description ?? '') }}</textarea></div>
    <div class="col-12"><label class="form-label">Notes</label><textarea class="form-control" name="notes" rows="2">{{ old('notes', $serviceOrder->notes ?? '') }}</textarea></div>
</div>
<div class="mt-3"><button class="btn btn-primary">Save Order</button><a class="btn btn-light" href="{{ route('repair-services.index') }}">Cancel</a></div>
