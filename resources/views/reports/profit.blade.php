@extends('layouts.app')
@section('page_title', 'Profit Report')
@section('actions')<a class="btn btn-success" href="{{ route('reports.profit.export', request()->query()) }}">CSV Export</a>@endsection
@section('content')
@include('reports._nav')
<div class="row g-3"><div class="col-md-6"><div class="card content-card"><div class="card-body"><h2 class="h6">Sales</h2><p>Income: Rs. {{ number_format($salesTotal, 2) }}</p><p class="mb-0">Profit: Rs. {{ number_format($salesProfit, 2) }}</p></div></div></div><div class="col-md-6"><div class="card content-card"><div class="card-body"><h2 class="h6">Repair / Service</h2><p>Income: Rs. {{ number_format($serviceTotal, 2) }}</p><p class="mb-0">Profit: Rs. {{ number_format($serviceProfit, 2) }}</p></div></div></div></div>
@endsection
