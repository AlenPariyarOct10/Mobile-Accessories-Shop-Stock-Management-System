@extends('layouts.app')
@section('page_title', 'Edit Expense')
@section('content')
<div class="card content-card"><div class="card-body"><form method="POST" action="{{ route('expenses.update', $expense) }}">@csrf @method('PUT') @include('expenses._form')</form></div></div>
@endsection
