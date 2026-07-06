<div class="card content-card h-100">
    <div class="card-header bg-white">Recent Repairs / Services</div>
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            @forelse($recentRepairServices as $service)
                <tr><td>{{ $service->service_type }}</td><td class="text-end">Rs. {{ number_format($service->charged_price, 2) }}</td></tr>
            @empty
                <tr><td class="text-muted">No service records yet.</td></tr>
            @endforelse
        </table>
    </div>
</div>
