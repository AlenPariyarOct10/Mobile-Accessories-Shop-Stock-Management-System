<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $search = request('search');
        $status = request('status');
        $sortBy = request('sort_by', 'created_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Item::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '') {
            $query->where('is_active', $status === 'active');
        }

        $allowedSorts = ['created_at', 'name', 'current_stock', 'default_selling_price'];
        $sortBy = in_array($sortBy, $allowedSorts) ? $sortBy : 'created_at';

        $query->orderBy($sortBy, $sortDirection === 'asc' ? 'asc' : 'desc');

        $items = $query->paginate(12)->appends(request()->except('page'));

        return view('items.index', compact('items', 'search', 'status', 'sortBy', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('items.create', [
            'existingItemCodes' => Item::query()
                ->whereNotNull('code')
                ->pluck('code')
                ->values(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->boolean('code_auto_generated') || ! $request->filled('code')) {
            $request->merge(['code' => $this->uniqueCodeForName((string) $request->input('name'))]);
        }

        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['current_stock'] = 0;
        $data['default_purchase_price'] = $data['default_purchase_price'] ?? 0;
        $data['default_selling_price'] = $data['default_selling_price'] ?? 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($data);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item): View
    {
        $item->load([
            'stockEntries' => fn ($query) => $query->with('supplier')->latest(),
            'sales' => fn ($query) => $query->latest(),
        ]);

        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item): View
    {
        return view('items.edit', [
            'item' => $item,
            'existingItemCodes' => Item::query()
                ->whereNotNull('code')
                ->whereKeyNot($item->id)
                ->pluck('code')
                ->values(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item): RedirectResponse
    {
        if ($request->boolean('code_auto_generated') || ! $request->filled('code')) {
            $request->merge(['code' => $this->uniqueCodeForName((string) $request->input('name'), $item)]);
        }

        $data = $this->validated($request, $item);
        $data['is_active'] = $request->boolean('is_active');
        $data['default_purchase_price'] = $data['default_purchase_price'] ?? 0;
        $data['default_selling_price'] = $data['default_selling_price'] ?? 0;
        unset($data['current_stock']);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item): RedirectResponse
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    private function validated(Request $request, ?Item $item = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'code' => ['nullable', 'string', 'max:191', Rule::unique('items', 'code')->ignore($item)],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'default_purchase_price' => ['nullable', 'numeric', 'min:0'],
            'default_selling_price' => ['nullable', 'numeric', 'min:0'],
            'low_stock_alert_quantity' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function uniqueCodeForName(string $name, ?Item $item = null): string
    {
        $base = strtoupper(trim((string) preg_replace('/[^A-Za-z0-9]+/', '-', $name), '-')) ?: 'ITEM';
        $code = $base;
        $number = 2;

        while (
            Item::query()
                ->where('code', $code)
                ->when($item, fn ($query) => $query->whereKeyNot($item->id))
                ->exists()
        ) {
            $code = $base.'-'.$number;
            $number++;
        }

        return $code;
    }
}
