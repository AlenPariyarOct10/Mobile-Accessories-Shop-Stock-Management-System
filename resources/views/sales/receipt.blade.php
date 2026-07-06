@extends('layouts.app')
@section('title', 'Receipt #'.$sale->id)
@section('page_title', 'Sales Receipt')
@section('actions')<button class="btn btn-success" type="button" onclick="window.print()">Print Receipt</button> <a class="btn btn-light" href="{{ route('sales.show', $sale) }}">Back</a>@endsection
@section('content')
<div class="receipt-wrap">
    <div class="receipt-card">
        <div class="receipt-header">
            <div class="d-flex align-items-center gap-3">
                @if($companySetting?->company_logo)
                    <img class="receipt-logo" src="{{ asset('storage/'.$companySetting->company_logo) }}" alt="Logo">
                @else
                    <span class="receipt-mark">{{ strtoupper(substr($companySetting?->company_name ?? config('app.name', 'S'), 0, 1)) }}</span>
                @endif
                <div>
                    <h2 class="h5 mb-1">{{ $companySetting?->company_name ?? config('app.name', 'Stock Management') }}</h2>
                    @if($companySetting?->address)<div class="text-muted small">{{ $companySetting->address }}</div>@endif
                    @if($companySetting?->phone || $companySetting?->email)
                        <div class="text-muted small">{{ $companySetting->phone }}{{ $companySetting?->email ? ' | '.$companySetting->email : '' }}</div>
                    @endif
                </div>
            </div>
            <div class="text-end">
                <div class="receipt-label">Receipt</div>
                <div class="fw-bold">#{{ str_pad((string) $sale->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="text-muted small">{{ \App\Support\DateFormatter::human($sale->sold_at, true) }}</div>
            </div>
        </div>

        <div class="receipt-section">
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="receipt-meta-label">Customer</div>
                    <div class="fw-semibold">{{ $sale->customer_name ?: 'Walk-in Customer' }}</div>
                    @if($sale->customer_phone)<div class="text-muted">{{ $sale->customer_phone }}</div>@endif
                    @if($sale->customer_address)<div class="text-muted">{{ $sale->customer_address }}</div>@endif
                </div>
                <div class="col-sm-6 text-sm-end">
                    <div class="receipt-meta-label">Payment</div>
                    <div class="fw-semibold">{{ $sale->payment_mode }}</div>
                    <div class="text-muted">Sale date: {{ \App\Support\DateFormatter::human($sale->date) }}</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table receipt-table">
                <thead><tr><th>Item</th><th class="text-end">Qty</th><th class="text-end">Rate</th><th class="text-end">Amount</th></tr></thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $sale->item?->name ?? 'Item removed' }}</div>
                            @if($sale->item?->code)<small class="text-muted">{{ $sale->item->code }}</small>@endif
                        </td>
                        <td class="text-end">{{ $sale->quantity }}</td>
                        <td class="text-end">Rs. {{ number_format($sale->price_per_item, 2) }}</td>
                        <td class="text-end">Rs. {{ number_format($sale->total_sales_price, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr><th colspan="3" class="text-end">Total</th><th class="text-end">Rs. {{ number_format($sale->total_sales_price, 2) }}</th></tr>
                </tfoot>
            </table>
        </div>

        @if($sale->notes)
            <div class="receipt-section">
                <div class="receipt-meta-label">Notes</div>
                <div>{{ $sale->notes }}</div>
            </div>
        @endif
        @if($companySetting?->owner_name)<div class="text-muted small">Billing By : {{ $companySetting->owner_name }}</div>@endif

    </div>
</div>
@endsection
