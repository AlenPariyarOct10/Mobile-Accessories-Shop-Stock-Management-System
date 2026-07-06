<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Item;
use App\Models\RepairService;
use App\Models\Sale;
use App\Models\StockEntry;
use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $monthlySales = Sale::query()
            ->selectRaw('DATE(date) as day, SUM(total_sales_price) as total')
            ->where('date', '>=', $today->copy()->subDays(29))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        return view('dashboard.index', [
            'totalItems' => Item::count(),
            'totalSuppliers' => Supplier::count(),
            'totalStockQuantity' => Item::sum('current_stock'),
            'totalPurchaseValue' => StockEntry::sum('total_purchase_price'),
            'totalSalesValue' => Sale::sum('total_sales_price'),
            'grossProfit' => Sale::sum('profit') + RepairService::sum('profit'),
            'totalExpenses' => Expense::sum('amount'),
            'netProfit' => (Sale::sum('profit') + RepairService::sum('profit')) - Expense::sum('amount'),
            'todaySales' => Sale::whereDate('date', $today)->sum('total_sales_price'),
            'todayExpenses' => Expense::whereDate('date', $today)->sum('amount'),
            'currentMonthSales' => Sale::whereYear('date', $today->year)->whereMonth('date', $today->month)->sum('total_sales_price'),
            'currentMonthExpenses' => Expense::whereYear('date', $today->year)->whereMonth('date', $today->month)->sum('amount'),
            'serviceIncome' => RepairService::sum('charged_price'),
            'serviceProfit' => RepairService::sum('profit'),
            'lowStockItems' => Item::whereColumn('current_stock', '<=', 'low_stock_alert_quantity')->orderBy('current_stock')->limit(8)->get(),
            'highSellingItems' => Sale::select('item_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total_sales_price) as total_sales'))
                ->with('item')
                ->groupBy('item_id')
                ->orderByDesc('total_quantity')
                ->limit(8)
                ->get(),
            'recentSales' => Sale::with('item')->latest()->limit(8)->get(),
            'recentStockEntries' => StockEntry::with(['item', 'supplier'])->latest()->limit(8)->get(),
            'recentRepairServices' => RepairService::latest()->limit(8)->get(),
            'recentExpenses' => Expense::latest()->limit(8)->get(),
            'chartLabels' => $monthlySales->keys()->values(),
            'chartData' => $monthlySales->values(),
        ]);
    }
}
