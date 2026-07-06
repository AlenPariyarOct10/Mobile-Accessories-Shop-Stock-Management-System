<div class="card content-card h-100">
    <div class="card-header bg-white">Recent Stock Entries</div>
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            @forelse($recentStockEntries as $entry)
                <tr><td>{{ $entry->item?->name }}</td><td class="text-end">{{ $entry->quantity }}</td></tr>
            @empty
                <tr><td class="text-muted">No stock entries yet.</td></tr>
            @endforelse
        </table>
    </div>
</div>
