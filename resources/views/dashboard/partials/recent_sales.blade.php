<div class="card content-card h-100">
    <div class="card-header bg-white">Recent Sales</div>
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            @forelse($recentSales as $sale)
                <tr><td>{{ $sale->item?->name }}</td><td class="text-end">Rs. {{ number_format($sale->total_sales_price, 2) }}</td></tr>
            @empty
                <tr><td class="text-muted">No sales yet.</td></tr>
            @endforelse
        </table>
    </div>
</div>
