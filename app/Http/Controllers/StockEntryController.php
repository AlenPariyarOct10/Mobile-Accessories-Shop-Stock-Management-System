<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockEntry;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StockEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $search = request('search');
        $supplierId = request('supplier_id');
        $dateFrom = request('date_from');
        $dateTo = request('date_to');

        $query = StockEntry::with(['item', 'supplier']);

        if ($search) {
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        if ($dateFrom) {
            $query->where('date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('date', '<=', $dateTo);
        }

        $stockEntries = $query->latest()->paginate(12)->appends(request()->except('page'));
        $suppliers = Supplier::orderBy('name')->get();

        return view('stock_entries.index', compact('stockEntries', 'suppliers', 'search', 'supplierId', 'dateFrom', 'dateTo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('stock_entries.create', [
            'items' => Item::where('is_active', true)->orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
            'selectedItemId' => request()->integer('item_id') ?: null,
            'selectedSupplierId' => request()->integer('supplier_id') ?: null,
        ]);
    }

    public function quickStoreItem(Request $request): RedirectResponse
    {
        $shouldGenerateCode = $request->boolean('code_auto_generated') || ! $request->filled('code');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'code' => array_filter(['nullable', 'string', 'max:191', $shouldGenerateCode ? null : Rule::unique('items', 'code')]),
            'default_purchase_price' => ['nullable', 'numeric', 'min:0'],
            'default_selling_price' => ['nullable', 'numeric', 'min:0'],
            'low_stock_alert_quantity' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'return_supplier_id' => ['nullable', 'exists:suppliers,id'],
        ]);
        $supplierId = $data['return_supplier_id'] ?? null;
        unset($data['return_supplier_id']);

        if ($shouldGenerateCode) {
            $data['code'] = $this->uniqueItemCodeForName($data['name']);
        }

        $data['default_purchase_price'] = $data['default_purchase_price'] ?? 0;
        $data['default_selling_price'] = $data['default_selling_price'] ?? 0;
        $item = Item::create($data + [
            'current_stock' => 0,
            'is_active' => true,
        ]);

        return redirect()
            ->route('stock-entries.create', array_filter(['item_id' => $item->id, 'supplier_id' => $supplierId]))
            ->with('success', 'Item created. Continue adding stock.');
    }

    public function quickStoreSupplier(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'phone' => ['nullable', 'string', 'max:191'],
            'address' => ['nullable', 'string', 'max:191'],
            'email' => ['nullable', 'email', 'max:191'],
            'notes' => ['nullable', 'string'],
            'return_item_id' => ['nullable', 'exists:items,id'],
        ]);
        $itemId = $data['return_item_id'] ?? null;
        unset($data['return_item_id']);
        $supplier = Supplier::create($data);

        return redirect()
            ->route('stock-entries.create', array_filter(['item_id' => $itemId, 'supplier_id' => $supplier->id]))
            ->with('success', 'Supplier created. Continue adding stock.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['total_purchase_price'] = $data['quantity'] * $data['price_per_item'];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('stock-entries', 'public');
        }

        DB::transaction(function () use ($data): void {
            $entry = StockEntry::create($data);
            $entry->item()->increment('current_stock', $entry->quantity);

            if ($entry->selling_price !== null) {
                $entry->item->update(['default_selling_price' => $entry->selling_price]);
            }
        });

        return redirect()->route('stock-entries.index')->with('success', 'Stock entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StockEntry $stockEntry): View
    {
        $stockEntry->load(['item', 'supplier']);

        return view('stock_entries.show', compact('stockEntry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockEntry $stockEntry): View
    {
        return view('stock_entries.edit', [
            'stockEntry' => $stockEntry,
            'items' => Item::where('is_active', true)->orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockEntry $stockEntry): RedirectResponse
    {
        $data = $this->validated($request);
        $data['total_purchase_price'] = $data['quantity'] * $data['price_per_item'];

        if ($request->hasFile('image')) {
            if ($stockEntry->image) {
                Storage::disk('public')->delete($stockEntry->image);
            }
            $data['image'] = $request->file('image')->store('stock-entries', 'public');
        }

        DB::transaction(function () use ($stockEntry, $data): void {
            $oldItem = $stockEntry->item;
            $oldItem->decrement('current_stock', $stockEntry->quantity);

            $stockEntry->update($data);
            $stockEntry->refresh();
            $stockEntry->item()->increment('current_stock', $stockEntry->quantity);

            if ($stockEntry->selling_price !== null) {
                $stockEntry->item->update(['default_selling_price' => $stockEntry->selling_price]);
            }
        });

        return redirect()->route('stock-entries.index')->with('success', 'Stock entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockEntry $stockEntry): RedirectResponse
    {
        DB::transaction(function () use ($stockEntry): void {
            $stockEntry->item()->decrement('current_stock', $stockEntry->quantity);

            if ($stockEntry->image) {
                Storage::disk('public')->delete($stockEntry->image);
            }

            $stockEntry->delete();
        });

        return redirect()->route('stock-entries.index')->with('success', 'Stock entry deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'date' => ['required', 'date'],
            'item_id' => ['required', 'exists:items,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price_per_item' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function uniqueItemCodeForName(string $name): string
    {
        $base = strtoupper(trim((string) preg_replace('/[^A-Za-z0-9]+/', '-', $name), '-')) ?: 'ITEM';
        $code = $base;
        $number = 2;

        while (Item::where('code', $code)->exists()) {
            $code = $base.'-'.$number;
            $number++;
        }

        return $code;
    }
}
