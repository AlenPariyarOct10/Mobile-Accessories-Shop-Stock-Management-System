@extends('layouts.app')
@section('page_title', 'Edit Supplier')
@section('content')
<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('suppliers.update', $supplier) }}">@csrf @method('PUT') @include('suppliers._form')</form></div></div>
@endsection
