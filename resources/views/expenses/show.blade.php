@extends('layouts.app')
@section('page_title', 'Expense Details')
@section('actions')<a class="btn btn-primary" href="{{ route('expenses.edit', $expense) }}">Edit</a> <a class="btn btn-light" href="{{ route('expenses.index') }}">Back</a>@endsection
@section('content')
<div class="card content-card"><div class="card-body"><dl class="row mb-0">
    <dt class="col-sm-3">Date</dt><dd class="col-sm-9">{{ \App\Support\DateFormatter::human($expense->date) }}</dd>
    <dt class="col-sm-3">Title</dt><dd class="col-sm-9">{{ $expense->title }}</dd>
    <dt class="col-sm-3">Category</dt><dd class="col-sm-9">{{ $expense->category }}</dd>
    <dt class="col-sm-3">Amount</dt><dd class="col-sm-9">Rs. {{ number_format($expense->amount, 2) }}</dd>
    <dt class="col-sm-3">Payment</dt><dd class="col-sm-9">{{ $expense->payment_mode }}</dd>
    <dt class="col-sm-3">Vendor / Place</dt><dd class="col-sm-9">{{ $expense->vendor }}</dd>
    <dt class="col-sm-3">Notes</dt><dd class="col-sm-9">{{ $expense->notes }}</dd>
</dl></div></div>
@endsection
