<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $search = request('search');
        $paymentMode = request('payment_mode');
        $dateFrom = request('date_from');
        $dateTo = request('date_to');

        $query = Sale::with('item');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhereHas('item', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($paymentMode) {
            $query->where('payment_mode', $paymentMode);
        }

        if ($dateFrom) {
            $query->where('date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('date', '<=', $dateTo);
        }

        $sales = $query->latest()->paginate(12)->appends(request()->except('page'));

        return view('sales.index', [
            'sales' => $sales,
            'search' => $search,
            'paymentMode' => $paymentMode,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'paymentModes' => Sale::PAYMENT_MODES,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $selectedItem = null;

        if ($request->filled('item_id')) {
            $selectedItem = Item::where('is_active', true)->find($request->integer('item_id'));
        }

        return view('sales.create', [
            'items' => Item::where('is_active', true)->orderBy('name')->get(),
            'paymentModes' => Sale::PAYMENT_MODES,
            'selectedItem' => $selectedItem,
            'selectedItemId' => $selectedItem?->id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $item = Item::findOrFail($data['item_id']);

        if ($data['quantity'] > $item->current_stock) {
            throw ValidationException::withMessages(['quantity' => 'Sale quantity cannot exceed current stock.']);
        }

        $data = $this->prepareTotals($data, $item);

        $sale = DB::transaction(function () use ($data, $item): Sale {
            $sale = Sale::create($data);
            $item->decrement('current_stock', $data['quantity']);

            return $sale;
        });

        return redirect()->route('sales.receipt', $sale)->with('success', 'Sale recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale): View
    {
        $sale->load('item');

        return view('sales.show', compact('sale'));
    }

    public function receipt(Sale $sale): View
    {
        $sale->load('item');

        return view('sales.receipt', compact('sale'));
    }

    public function searchReceipt(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'receipt_id' => ['required', 'string', 'max:191'],
        ]);

        $receiptId = ltrim(preg_replace('/\D+/', '', $data['receipt_id']) ?? '', '0');

        if ($receiptId === '') {
            return redirect()->route('sales.index')->with('error', 'Please enter a valid receipt ID.');
        }

        $sale = Sale::find($receiptId);

        if (! $sale) {
            return redirect()->route('sales.index')->with('error', 'Receipt not found.');
        }

        return redirect()->route('sales.receipt', $sale);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale): View
    {
        return view('sales.edit', [
            'sale' => $sale,
            'items' => Item::where('is_active', true)->orderBy('name')->get(),
            'paymentModes' => Sale::PAYMENT_MODES,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale): RedirectResponse
    {
        $data = $this->validated($request);
        $newItem = Item::findOrFail($data['item_id']);

        $availableStock = $newItem->current_stock;
        if ($sale->item_id === $newItem->id) {
            $availableStock += $sale->quantity;
        }

        if ($data['quantity'] > $availableStock) {
            throw ValidationException::withMessages(['quantity' => 'Sale quantity cannot exceed current stock.']);
        }

        $data = $this->prepareTotals($data, $newItem);

        DB::transaction(function () use ($sale, $data, $newItem): void {
            $sale->item()->increment('current_stock', $sale->quantity);
            $sale->update($data);
            $newItem->decrement('current_stock', $data['quantity']);
        });

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale): RedirectResponse
    {
        DB::transaction(function () use ($sale): void {
            $sale->item()->increment('current_stock', $sale->quantity);
            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'date' => ['required', 'date'],
            'sold_at' => ['required', 'date'],
            'item_id' => ['required', 'exists:items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price_per_item' => ['required', 'numeric', 'min:0'],
            'total_sales_price' => ['nullable', 'numeric', 'min:0'],
            'payment_mode' => ['nullable', Rule::in(Sale::PAYMENT_MODES)],
            'customer_name' => ['nullable', 'string', 'max:191'],
            'customer_phone' => ['nullable', 'string', 'max:191'],
            'customer_address' => ['nullable', 'string', 'max:191'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function prepareTotals(array $data, Item $item): array
    {
        $data['payment_mode'] = $data['payment_mode'] ?: 'Cash';
        $data['total_sales_price'] = ($data['total_sales_price'] ?? null) ?: ($data['quantity'] * $data['price_per_item']);
        $latestPrice = $item->latestStockEntry?->price_per_item;
        $purchasePrice = (float) ($item->default_purchase_price ?: $latestPrice ?: 0);
        $data['estimated_purchase_cost'] = $purchasePrice * $data['quantity'];
        $data['profit'] = $data['total_sales_price'] - $data['estimated_purchase_cost'];

        return $data;
    }
}
