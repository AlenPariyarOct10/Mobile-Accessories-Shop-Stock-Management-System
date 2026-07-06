<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Expense::query()
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->search;
                $query->where(function ($inner) use ($search): void {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('vendor', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->category))
            ->when($request->filled('payment_mode'), fn ($query) => $query->where('payment_mode', $request->payment_mode))
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($query) => $query->whereDate('date', '<=', $request->date_to));

        $totalExpenses = (clone $query)->sum('amount');
        $expenses = $query->latest()->paginate(12)->withQueryString();

        return view('expenses.index', [
            'expenses' => $expenses,
            'totalExpenses' => $totalExpenses,
            'categories' => Expense::CATEGORIES,
            'paymentModes' => Expense::PAYMENT_MODES,
        ]);
    }

    public function create(): View
    {
        return view('expenses.create', [
            'categories' => Expense::CATEGORIES,
            'paymentModes' => Expense::PAYMENT_MODES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Expense::create($this->validated($request));

        return redirect()->route('expenses.index')->with('success', 'Expense logged successfully.');
    }

    public function show(Expense $expense): View
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense): View
    {
        return view('expenses.edit', [
            'expense' => $expense,
            'categories' => Expense::CATEGORIES,
            'paymentModes' => Expense::PAYMENT_MODES,
        ]);
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $expense->update($this->validated($request));

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:191'],
            'category' => ['required', 'string', 'max:191'],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_mode' => ['nullable', Rule::in(Expense::PAYMENT_MODES)],
            'vendor' => ['nullable', 'string', 'max:191'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['payment_mode'] = ($data['payment_mode'] ?? null) ?: 'Cash';

        return $data;
    }
}
