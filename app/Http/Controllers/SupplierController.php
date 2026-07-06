<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $search = request('search');

        $query = Supplier::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->latest()->paginate(12)->appends(request()->except('page'));

        return view('suppliers.index', compact('suppliers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Supplier::create($this->validated($request));

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Supplier $supplier): View
    {
        $itemId = $request->input('item_id');
        $search = $request->input('search');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $receivedQuery = $supplier->stockEntries()
            ->with('item')
            ->when($itemId, fn ($query) => $query->where('item_id', $itemId))
            ->when($search, function ($query) use ($search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner->where('notes', 'like', "%{$search}%")
                        ->orWhereHas('item', function ($itemQuery) use ($search): void {
                            $itemQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        });
                });
            })
            ->when($dateFrom, fn ($query) => $query->where('date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->where('date', '<=', $dateTo));

        $receivedTotals = (clone $receivedQuery)
            ->selectRaw('COALESCE(SUM(quantity), 0) as quantity, COALESCE(SUM(total_purchase_price), 0) as amount')
            ->first();

        $receivedLogs = $receivedQuery
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $receivedItems = $supplier->stockEntries()
            ->with('item')
            ->get()
            ->pluck('item')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values();

        return view('suppliers.show', compact(
            'supplier',
            'receivedLogs',
            'receivedTotals',
            'receivedItems',
            'itemId',
            'search',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($this->validated($request));

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'phone' => ['nullable', 'string', 'max:191'],
            'address' => ['nullable', 'string', 'max:191'],
            'email' => ['nullable', 'email', 'max:191'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
