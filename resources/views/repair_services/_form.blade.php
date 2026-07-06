@include('partials.errors')
<div class="row g-3">
    <div class="col-md-3"><label class="form-label">Date</label><input class="form-control" type="date" name="date" value="{{ old('date', isset($repairService) ? $repairService->date->format('Y-m-d') : now()->format('Y-m-d')) }}" required></div>
    <div class="col-md-3"><label class="form-label">Date / Time</label><input class="form-control" type="datetime-local" name="serviced_at" value="{{ old('serviced_at', isset($repairService) ? $repairService->serviced_at->format('Y-m-d\\TH:i') : now()->format('Y-m-d\\TH:i')) }}" required></div>
    <div class="col-md-3"><label class="form-label">Service Type</label><input class="form-control" name="service_type" value="{{ old('service_type', $repairService->service_type ?? '') }}" required></div>
    <div class="col-md-3"><label class="form-label">Payment</label><select class="form-select" name="payment_mode">@foreach($paymentModes as $mode)<option value="{{ $mode }}" @selected(old('payment_mode', $repairService->payment_mode ?? 'Cash') === $mode)>{{ $mode }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Customer Name</label><input class="form-control" name="customer_name" value="{{ old('customer_name', $repairService->customer_name ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Customer Phone</label><input class="form-control" name="customer_phone" value="{{ old('customer_phone', $repairService->customer_phone ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Customer Address</label><input class="form-control" name="customer_address" value="{{ old('customer_address', $repairService->customer_address ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Cost Price</label><input class="form-control service-cost" type="number" step="0.01" min="0" name="cost_price" value="{{ old('cost_price', $repairService->cost_price ?? 0) }}"></div>
    <div class="col-md-4"><label class="form-label">Charged Price</label><input class="form-control service-charged" type="number" step="0.01" min="0" name="charged_price" value="{{ old('charged_price', $repairService->charged_price ?? 0) }}" required></div>
    <div class="col-md-4"><label class="form-label">Profit</label><input class="form-control service-profit" type="number" step="0.01" readonly></div>
    <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="2">{{ old('description', $repairService->description ?? '') }}</textarea></div>
    <div class="col-12"><label class="form-label">Notes</label><textarea class="form-control" name="notes" rows="2">{{ old('notes', $repairService->notes ?? '') }}</textarea></div>
</div>
<div class="mt-3"><button class="btn btn-primary">Save</button><a class="btn btn-light" href="{{ route('repair-services.index') }}">Cancel</a></div>
@push('scripts')<script>function calcService(){const c=parseFloat(document.querySelector('.service-cost')?.value||0),p=parseFloat(document.querySelector('.service-charged')?.value||0);document.querySelector('.service-profit').value=(p-c).toFixed(2)}document.querySelectorAll('.service-cost,.service-charged').forEach(el=>el.addEventListener('input',calcService));calcService();</script>@endpush
