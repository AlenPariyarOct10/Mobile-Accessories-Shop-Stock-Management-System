@extends('layouts.app')
@section('page_title', 'Add Expense')
@section('content')
<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('expenses.store') }}">@csrf @include('expenses._form')</form></div></div>
@endsection
