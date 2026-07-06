@extends('layouts.app')
@section('page_title', 'Edit Item')
@section('content')
<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('items._form')</form></div></div>
@endsection
