@include('partials.errors')
<div class="row g-3">
    <div class="col-md-3"><label class="form-label">Date</label><input class="form-control" type="date" name="date" value="{{ old('date', isset($expense) ? $expense->date->format('Y-m-d') : now()->format('Y-m-d')) }}" required></div>
    <div class="col-md-5"><label class="form-label">Title</label><input class="form-control" name="title" value="{{ old('title', $expense->title ?? '') }}" placeholder="WiFi bill, shop rent, lunch..." required></div>
    <div class="col-md-4"><label class="form-label">Category</label><input class="form-control" name="category" list="expense-categories" value="{{ old('category', $expense->category ?? 'Misc') }}" required><datalist id="expense-categories">@foreach($categories as $category)<option value="{{ $category }}"></option>@endforeach</datalist></div>
    <div class="col-md-3"><label class="form-label">Amount</label><input class="form-control" type="number" step="0.01" min="0" name="amount" value="{{ old('amount', $expense->amount ?? 0) }}" required></div>
    <div class="col-md-3"><label class="form-label">Payment</label><select class="form-select" name="payment_mode">@foreach($paymentModes as $mode)<option value="{{ $mode }}" @selected(old('payment_mode', $expense->payment_mode ?? 'Cash') === $mode)>{{ $mode }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">Vendor / Place</label><input class="form-control" name="vendor" value="{{ old('vendor', $expense->vendor ?? '') }}" placeholder="Landlord, ISP, restaurant..."></div>
    <div class="col-12"><label class="form-label">Notes</label><textarea class="form-control" name="notes" rows="3">{{ old('notes', $expense->notes ?? '') }}</textarea></div>
</div>
<div class="mt-3"><button class="btn btn-primary">Save Expense</button><a class="btn btn-light" href="{{ route('expenses.index') }}">Cancel</a></div>
