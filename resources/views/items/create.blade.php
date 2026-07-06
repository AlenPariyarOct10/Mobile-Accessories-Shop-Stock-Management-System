@extends('layouts.app')
@section('page_title', 'Add Item')
@section('content')
<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">@csrf @include('items._form')</form></div></div>
@endsection
