<div class="card content-card h-100">
    <div class="card-header bg-white d-flex justify-content-between align-items-center"><span>Recent Expenses</span><a class="btn btn-sm btn-outline-primary" href="{{ route('expenses.create') }}">Add</a></div>
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            @forelse($recentExpenses as $expense)
                <tr><td>{{ $expense->title }}<br><small class="text-muted">{{ $expense->category }}</small></td><td class="text-end">Rs. {{ number_format($expense->amount, 2) }}</td></tr>
            @empty
                <tr><td class="text-muted">No expenses yet.</td></tr>
            @endforelse
        </table>
    </div>
</div>
