@extends('layouts.app')
@section('page_title', 'Edit Stock Entry')
@section('content')<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('stock-entries.update', $stockEntry) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('stock_entries._form')</form></div></div>@endsection
