<?php

namespace App\Livewire\Employee;

use App\Models\Product;
use App\Models\SupplyOrder;
use App\Models\StockAdjustment;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class Inventory extends Component
{
    public function getStockCounts()
    {
        $noStock = Product::where('stock', 0)->count();
        $lowStock = Product::whereBetween('stock', [1, 5])->count();
        $healthyStock = Product::where('stock', '>', 5)->count();
        $total = Product::count();

        return [
            'noStock' => $noStock,
            'lowStock' => $lowStock,
            'healthyStock' => $healthyStock,
            'total' => $total
        ];
    }

    public function getRecentProducts()
    {
        // Get out of stock products
        $outOfStock = Product::with('category')
            ->where('stock', 0)
            ->latest()
            ->take(5)
            ->get();

        // Get low stock products
        $lowStock = Product::with('category')
            ->whereBetween('stock', [1, 5])
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        return [
            'outOfStock' => $outOfStock,
            'lowStock' => $lowStock
        ];
    }

    public function getTopProducts()
    {
        return Product::with('category')
            ->withCount('itemOrders')
            ->having('item_orders_count', '>', 0)
            ->orderBy('item_orders_count', 'desc')
            ->take(5)
            ->get();
    }

    public function getLowStockAlerts()
    {
        return Product::with('category')
            ->where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->take(8)
            ->get();
    }

    public function getNewProducts()
    {
        return Product::with('category')
            ->latest()
            ->take(5)
            ->get();
    }

    public function getPendingSupplyOrdersProperty()
    {
        return SupplyOrder::with(['product.category', 'registeredBy'])
            ->where('status', 'requested')
            ->latest()
            ->take(5)
            ->get();
    }

    public function getRecentStockAdjustments()
    {
        return StockAdjustment::with(['product.category', 'registeredBy'])
            ->latest()
            ->take(10)
            ->get();
    }

    public function calculateStats()
    {
        $products = Product::all();
        
        $totalValue = 0;
        $totalStock = 0;
        $productCount = 0;

        foreach ($products as $product) {
            $totalValue += $product->price * $product->stock;
            $totalStock += $product->stock;
            $productCount++;
        }

        $avgStock = $productCount > 0 ? round($totalStock / $productCount, 1) : 0;

        // Count categories that have products with stock
        $categoriesWithStock = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.stock', '>', 0)
            ->distinct()
            ->count('products.category_id');

        return [
            'totalValue' => $totalValue,
            'avgStock' => $avgStock,
            'categoriesWithStock' => $categoriesWithStock
        ];
    }



    public function render()
    {
        $stockCounts = $this->getStockCounts();
        $recentProducts = $this->getRecentProducts();
        $stats = $this->calculateStats();
        $topProducts = $this->getTopProducts();
        $alerts = $this->getLowStockAlerts();
        $newProducts = $this->getNewProducts();
        $pendingSupplyOrders = $this->getPendingSupplyOrdersProperty();
        $stockAdjustments = $this->getRecentStockAdjustments();

        return view('livewire.employee.inventory', [
            'noStockProducts' => $stockCounts['noStock'],
            'lowStockProducts' => $stockCounts['lowStock'], 
            'healthyStockProducts' => $stockCounts['healthyStock'],
            'totalProducts' => $stockCounts['total'],
            'recentNoStockProducts' => $recentProducts['outOfStock'],
            'recentLowStockProducts' => $recentProducts['lowStock'],
            'inventoryStats' => $stats,
            'topSellingProducts' => $topProducts,
            'lowStockAlerts' => $alerts,
            'recentlyAddedProducts' => $newProducts,
            'pendingSupplyOrders' => $pendingSupplyOrders->count(),
            'recentStockAdjustments' => $stockAdjustments
        ]);
    }
}
