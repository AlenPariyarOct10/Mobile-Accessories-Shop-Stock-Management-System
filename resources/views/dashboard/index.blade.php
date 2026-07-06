@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="row g-3">
    @foreach([
        'Total Items' => $totalItems,
        'Suppliers' => $totalSuppliers,
        'Stock Qty' => $totalStockQuantity,
        'Purchase Value' => 'Rs. '.number_format($totalPurchaseValue, 2),
        'Sales Value' => 'Rs. '.number_format($totalSalesValue, 2),
        'Gross Profit' => 'Rs. '.number_format($grossProfit, 2),
        'Expenses' => 'Rs. '.number_format($totalExpenses, 2),
        'Net Profit' => 'Rs. '.number_format($netProfit, 2),
        "Today's Sales" => 'Rs. '.number_format($todaySales, 2),
        "Today's Expenses" => 'Rs. '.number_format($todayExpenses, 2),
        'Monthly Sales' => 'Rs. '.number_format($currentMonthSales, 2),
        'Monthly Expenses' => 'Rs. '.number_format($currentMonthExpenses, 2),
        'Service Income' => 'Rs. '.number_format($serviceIncome, 2),
        'Service Profit' => 'Rs. '.number_format($serviceProfit, 2),
    ] as $label => $value)
        <div class="col-xl-3 col-md-4 col-sm-6">
            <div class="card content-card">
                <div class="card-body">
                    <div class="text-muted small">{{ $label }}</div>
                    <div class="h4 mb-0">{{ $value }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3 mt-1">
    <div class="col-lg-8">
        <div class="card content-card">
            <div class="card-header bg-white">Sales Trend</div>
            <div class="card-body"><canvas id="salesChart" height="110"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card content-card">
            <div class="card-header bg-white">Low Stock Items</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    @forelse($lowStockItems as $item)
                        <tr><td>{{ $item->name }}</td><td class="text-end">{{ $item->current_stock }}</td></tr>
                    @empty
                        <tr><td class="text-muted">No low stock items.</td></tr>
                    @endforelse
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-lg-3 col-md-6">@include('dashboard.partials.recent_sales')</div>
    <div class="col-lg-3 col-md-6">@include('dashboard.partials.recent_stock')</div>
    <div class="col-lg-3 col-md-6">@include('dashboard.partials.recent_services')</div>
    <div class="col-lg-3 col-md-6">@include('dashboard.partials.recent_expenses')</div>
</div>

<div class="card content-card mt-3">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <div class="text-muted small">Developer Info</div>
            <div>Developed by <strong>Alen Pariyar</strong></div>
        </div>
        <a class="btn btn-outline-primary" href="https://alenpariyar.com.np" target="_blank" rel="noopener">alenpariyar.com.np</a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [{ label: 'Sales', data: @json($chartData), borderColor: '#0ea5e9', backgroundColor: 'rgba(14, 165, 233, .12)', tension: .25, fill: true }]
    }
});
</script>
@endpush
