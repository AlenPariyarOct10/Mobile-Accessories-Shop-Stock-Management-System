<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\RepairService;
use App\Models\Sale;
use App\Models\StockEntry;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function sales(Request $request): View
    {
        $sales = $this->salesQuery($request)->paginate(25)->withQueryString();

        return view('reports.sales', [
            'sales' => $sales,
            'items' => Item::orderBy('name')->get(),
            'paymentModes' => Sale::PAYMENT_MODES,
            'totalSales' => $this->salesQuery($request)->sum('total_sales_price'),
            'totalProfit' => $this->salesQuery($request)->sum('profit'),
        ]);
    }

    public function exportSales(Request $request)
    {
        $rows = $this->salesQuery($request)->get()->map(fn (Sale $sale) => [
            'Date' => $sale->date->format('Y-m-d'),
            'Item' => $sale->item?->name,
            'Quantity' => $sale->quantity,
            'Price Per Item' => $sale->price_per_item,
            'Total Sales' => $sale->total_sales_price,
            'Profit' => $sale->profit,
            'Payment Mode' => $sale->payment_mode,
            'Customer Name' => $sale->customer_name,
            'Customer Phone' => $sale->customer_phone,
            'Customer Address' => $sale->customer_address,
        ]);

        return $this->csv('sales-report.csv', $rows);
    }

    public function stock(Request $request): View
    {
        $entries = StockEntry::with(['item', 'supplier'])
            ->when($request->filled('from_date'), fn ($query) => $query->whereDate('date', '>=', $request->from_date))
            ->when($request->filled('to_date'), fn ($query) => $query->whereDate('date', '<=', $request->to_date))
            ->when($request->filled('item_id'), fn ($query) => $query->where('item_id', $request->item_id))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('reports.stock', [
            'entries' => $entries,
            'items' => Item::orderBy('name')->get(),
            'totalQuantity' => $entries->sum('quantity'),
            'totalPurchase' => $entries->sum('total_purchase_price'),
        ]);
    }

    public function exportStock(Request $request)
    {
        $rows = StockEntry::with(['item', 'supplier'])
            ->when($request->filled('from_date'), fn ($query) => $query->whereDate('date', '>=', $request->from_date))
            ->when($request->filled('to_date'), fn ($query) => $query->whereDate('date', '<=', $request->to_date))
            ->when($request->filled('item_id'), fn ($query) => $query->where('item_id', $request->item_id))
            ->latest()
            ->get()
            ->map(fn (StockEntry $entry) => [
                'Date' => $entry->date->format('Y-m-d'),
                'Item' => $entry->item?->name,
                'Supplier' => $entry->supplier?->name,
                'Quantity' => $entry->quantity,
                'Price Per Item' => $entry->price_per_item,
                'Total Purchase' => $entry->total_purchase_price,
            ]);

        return $this->csv('stock-report.csv', $rows);
    }

    public function suppliers(): View
    {
        return view('reports.suppliers', ['suppliers' => Supplier::withCount('stockEntries')->orderBy('name')->paginate(25)]);
    }

    public function exportSuppliers()
    {
        return $this->csv('suppliers-report.csv', Supplier::withCount('stockEntries')->orderBy('name')->get()->map(fn (Supplier $supplier) => [
            'Name' => $supplier->name,
            'Phone' => $supplier->phone,
            'Email' => $supplier->email,
            'Address' => $supplier->address,
            'Stock Entries' => $supplier->stock_entries_count,
        ]));
    }

    public function items(): View
    {
        return view('reports.items', ['items' => Item::orderBy('name')->paginate(25)]);
    }

    public function exportItems()
    {
        return $this->csv('items-report.csv', Item::orderBy('name')->get()->map(fn (Item $item) => [
            'Name' => $item->name,
            'SKU' => $item->code,
            'Purchase Price' => $item->default_purchase_price,
            'Selling Price' => $item->default_selling_price,
            'Current Stock' => $item->current_stock,
            'Low Stock Alert' => $item->low_stock_alert_quantity,
            'Active' => $item->is_active ? 'Yes' : 'No',
        ]));
    }

    public function repairServices(Request $request): View
    {
        $services = $this->repairQuery($request)->paginate(25)->withQueryString();

        return view('reports.repair_services', [
            'services' => $services,
            'paymentModes' => RepairService::PAYMENT_MODES,
            'totalIncome' => $this->repairQuery($request)->sum('charged_price'),
            'totalProfit' => $this->repairQuery($request)->sum('profit'),
        ]);
    }

    public function exportRepairServices(Request $request)
    {
        return $this->csv('repair-services-report.csv', $this->repairQuery($request)->get()->map(fn (RepairService $service) => [
            'Date' => $service->date->format('Y-m-d'),
            'Service Type' => $service->service_type,
            'Charged Price' => $service->charged_price,
            'Cost Price' => $service->cost_price,
            'Profit' => $service->profit,
            'Payment Mode' => $service->payment_mode,
            'Customer Name' => $service->customer_name,
            'Customer Phone' => $service->customer_phone,
            'Customer Address' => $service->customer_address,
        ]));
    }

    public function profit(Request $request): View
    {
        return view('reports.profit', [
            'salesProfit' => $this->salesQuery($request)->sum('profit'),
            'serviceProfit' => $this->repairQuery($request)->sum('profit'),
            'salesTotal' => $this->salesQuery($request)->sum('total_sales_price'),
            'serviceTotal' => $this->repairQuery($request)->sum('charged_price'),
        ]);
    }

    public function exportProfit(Request $request)
    {
        return $this->csv('profit-report.csv', collect([
            ['Type' => 'Sales', 'Income' => $this->salesQuery($request)->sum('total_sales_price'), 'Profit' => $this->salesQuery($request)->sum('profit')],
            ['Type' => 'Repair / Service', 'Income' => $this->repairQuery($request)->sum('charged_price'), 'Profit' => $this->repairQuery($request)->sum('profit')],
        ]));
    }

    public function highSelling(): View
    {
        return view('reports.high_selling', ['rows' => $this->highSellingRows()->paginate(25)]);
    }

    public function exportHighSelling()
    {
        return $this->csv('high-selling-report.csv', $this->highSellingRows()->get()->map(fn ($row) => [
            'Item' => $row->item_name,
            'Quantity Sold' => $row->total_quantity,
            'Sales Amount' => $row->total_sales,
            'Estimated Profit' => $row->estimated_profit,
        ]));
    }

    public function lowStock(): View
    {
        return view('reports.low_stock', [
            'items' => Item::whereColumn('current_stock', '<=', 'low_stock_alert_quantity')->orderBy('current_stock')->paginate(25),
        ]);
    }

    public function exportLowStock()
    {
        return $this->csv('low-stock-report.csv', Item::whereColumn('current_stock', '<=', 'low_stock_alert_quantity')->orderBy('current_stock')->get()->map(fn (Item $item) => [
            'Name' => $item->name,
            'SKU' => $item->code,
            'Current Stock' => $item->current_stock,
            'Low Stock Alert' => $item->low_stock_alert_quantity,
        ]));
    }

    private function salesQuery(Request $request)
    {
        return Sale::with('item')
            ->when($request->filled('from_date'), fn ($query) => $query->whereDate('date', '>=', $request->from_date))
            ->when($request->filled('to_date'), fn ($query) => $query->whereDate('date', '<=', $request->to_date))
            ->when($request->filled('item_id'), fn ($query) => $query->where('item_id', $request->item_id))
            ->when($request->filled('payment_mode'), fn ($query) => $query->where('payment_mode', $request->payment_mode))
            ->when($request->filled('customer_name'), fn ($query) => $query->where('customer_name', 'like', '%'.$request->customer_name.'%'))
            ->when($request->filled('customer_phone'), fn ($query) => $query->where('customer_phone', 'like', '%'.$request->customer_phone.'%'))
            ->latest();
    }

    private function repairQuery(Request $request)
    {
        return RepairService::query()
            ->when($request->filled('from_date'), fn ($query) => $query->whereDate('date', '>=', $request->from_date))
            ->when($request->filled('to_date'), fn ($query) => $query->whereDate('date', '<=', $request->to_date))
            ->when($request->filled('service_type'), fn ($query) => $query->where('service_type', 'like', '%'.$request->service_type.'%'))
            ->when($request->filled('payment_mode'), fn ($query) => $query->where('payment_mode', $request->payment_mode))
            ->when($request->filled('customer_name'), fn ($query) => $query->where('customer_name', 'like', '%'.$request->customer_name.'%'))
            ->when($request->filled('customer_phone'), fn ($query) => $query->where('customer_phone', 'like', '%'.$request->customer_phone.'%'))
            ->latest();
    }

    private function highSellingRows()
    {
        return Sale::join('items', 'sales.item_id', '=', 'items.id')
            ->select('items.name as item_name', DB::raw('SUM(sales.quantity) as total_quantity'), DB::raw('SUM(sales.total_sales_price) as total_sales'), DB::raw('SUM(sales.profit) as estimated_profit'))
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total_quantity');
    }

    private function csv(string $fileName, $rows)
    {
        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'w');
            $first = $rows->first();

            if ($first) {
                fputcsv($handle, array_keys($first));
                foreach ($rows as $row) {
                    fputcsv($handle, $row);
                }
            }

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }
}
