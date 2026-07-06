@extends('layouts.app')
@section('page_title', 'Company Settings')
@section('content')
<div class="card content-card"><div class="card-body">
    @include('partials.errors')
    <form method="POST" action="{{ route('company-settings.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Company Name</label><input class="form-control" name="company_name" value="{{ old('company_name', $setting->company_name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Owner Name</label><input class="form-control" name="owner_name" value="{{ old('owner_name', $setting->owner_name) }}"></div>
            <div class="col-md-6"><label class="form-label">Logo</label><input class="form-control" type="file" name="company_logo" accept=".jpg,.jpeg,.png,.webp"></div>
            <div class="col-md-4"><label class="form-label">Phone</label><input class="form-control" name="phone" value="{{ old('phone', $setting->phone) }}"></div>
            <div class="col-md-4"><label class="form-label">Email</label><input class="form-control" type="email" name="email" value="{{ old('email', $setting->email) }}"></div>
            <div class="col-md-4"><label class="form-label">Address</label><input class="form-control" name="address" value="{{ old('address', $setting->address) }}"></div>
            <div class="col-12"><label class="form-label">Footer Text</label><input class="form-control" name="footer_text" value="{{ old('footer_text', $setting->footer_text) }}"></div>
            @if($setting->company_logo)<div class="col-12"><img class="thumb" src="{{ asset('storage/'.$setting->company_logo) }}" alt="Company logo"></div>@endif
        </div>
        <div class="mt-3"><button class="btn btn-primary">Save Settings</button></div>
    </form>
</div></div>
@endsection
