@include('partials.errors')
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="name" value="{{ old('name', $supplier->name ?? '') }}" required></div>
    <div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}"></div>
    <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" type="email" name="email" value="{{ old('email', $supplier->email ?? '') }}"></div>
    <div class="col-md-6"><label class="form-label">Address</label><input class="form-control" name="address" value="{{ old('address', $supplier->address ?? '') }}"></div>
    <div class="col-12"><label class="form-label">Notes</label><textarea class="form-control" name="notes" rows="3">{{ old('notes', $supplier->notes ?? '') }}</textarea></div>
</div>
<div class="mt-3"><button class="btn btn-primary">Save</button><a class="btn btn-light" href="{{ route('suppliers.index') }}">Cancel</a></div>
