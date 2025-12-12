<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalPurchases = Purchase::count();
        $totalRevenue = Purchase::where('status', 'completed')->sum('total_amount');
        
        $lowStockProducts = Product::whereHas('stock', function ($query) {
            $query->where('quantity', '<=', 5);
        })->count();

        $recentPurchases = Purchase::with('admin')
            ->latest()
            ->take(5)
            ->get();

        $lowStockItems = Product::whereHas('stock', function ($query) {
            $query->where('quantity', '<=', 5);
        })
        ->with('stock')
        ->latest()
        ->take(5)
        ->get();

        // Today's statistics
        $todayPurchases = Purchase::whereDate('purchase_date', today())
            ->where('status', 'completed')
            ->count();

        $todayRevenue = Purchase::whereDate('purchase_date', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        // This month's statistics
        $monthlyPurchases = Purchase::whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->where('status', 'completed')
            ->count();

        $monthlyRevenue = Purchase::whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Top selling products this month
        $topProducts = \App\Models\PurchaseItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('purchase', function ($query) {
                $query->whereMonth('purchase_date', now()->month)
                      ->whereYear('purchase_date', now()->year)
                      ->where('status', 'completed');
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalProducts',
            'totalPurchases',
            'totalRevenue',
            'lowStockProducts',
            'recentPurchases',
            'lowStockItems',
            'todayPurchases',
            'todayRevenue',
            'monthlyPurchases',
            'monthlyRevenue',
            'topProducts'
        ));
    }
}