@extends('layouts.app')
@section('page_title', 'Add Supplier')
@section('content')
<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('suppliers.store') }}">@csrf @include('suppliers._form')</form></div></div>
@endsection
