<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // DEBUG: Cek apakah ada produk di database
        $totalProducts = Product::count();
        Log::info('Total products in database: ' . $totalProducts);
        
        // Load products dengan stock relation
        $products = Product::with('stock')->latest()->paginate(10);
        
        // DEBUG: Cek data yang diload
        Log::info('Products loaded: ' . $products->count());
        foreach ($products as $product) {
            Log::info('Product: ' . $product->name . ', Stock: ' . ($product->stock ? $product->stock->quantity : 'null'));
        }
        
        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('stock', 'stockHistory.admin');
        return view('admin.products.show', compact('product'));
    }

    public function create()
    {
        $categories = ['Living Room', 'Dining Room', 'Bedroom', 'Home Office', 'Kitchen', 'Lighting', 'Outdoor'];
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'initial_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $imageUrl = null;
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $imageUrl = Storage::url($path);
            }

            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'category' => $validated['category'],
                'price' => $validated['price'],
                'image_url' => $imageUrl,
            ]);

            $stock = Stock::create([
                'product_id' => $product->id,
                'quantity' => $validated['initial_stock'],
                'last_restocked' => now(),
            ]);

            StockHistory::create([
                'product_id' => $product->id,
                'change_type' => 'restock',
                'previous_quantity' => 0,
                'new_quantity' => $validated['initial_stock'],
                'change_amount' => $validated['initial_stock'],
                'notes' => 'Initial stock',
                'admin_id' => auth()->guard('admin')->id(),
            ]);

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $categories = ['Living Room', 'Dining Room', 'Bedroom', 'Home Office', 'Kitchen', 'Lighting', 'Outdoor'];
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image_url) {
                    $oldPath = str_replace('/storage/', '', $product->image_url);
                    Storage::disk('public')->delete($oldPath);
                }

                // Upload new image
                $path = $request->file('image')->store('products', 'public');
                $validated['image_url'] = Storage::url($path);
            } else {
                $validated['image_url'] = $product->image_url;
            }

            $product->update($validated);

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            // Delete image if exists
            if ($product->image_url) {
                $path = str_replace('/storage/', '', $product->image_url);
                Storage::disk('public')->delete($path);
            }

            // Delete related stock
            $product->stock()->delete();
            
            // Delete related stock history
            StockHistory::where('product_id', $product->id)->delete();

            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    public function stock()
    {
        // Load semua produk dengan stock
        $products = Product::with('stock')->get();
        
        // Load stock history
        $stockHistory = StockHistory::with(['product', 'admin'])->latest()->paginate(10);
        
        // FIX: Cegah error ketika tidak ada produk
        $lowStockProducts = collect([]);
        if (Product::count() > 0) {
            $lowStockProducts = Product::whereHas('stock', function ($query) {
                $query->where('quantity', '<=', 5);
            })->with('stock')->get();
        }

        return view('admin.products.stock', compact('products', 'stockHistory', 'lowStockProducts'));
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'change_type' => 'required|in:restock,manual_adjust',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $stock = $product->stock;
            
            if (!$stock) {
                $stock = Stock::create([
                    'product_id' => $product->id,
                    'quantity' => 0,
                ]);
            }

            $previousQuantity = $stock->quantity;

            if ($validated['change_type'] === 'restock') {
                $stock->quantity += $validated['quantity'];
                $stock->last_restocked = now();
                $changeAmount = $validated['quantity'];
            } else {
                // Manual adjustment - set exact quantity
                $stock->quantity = $validated['quantity'];
                $changeAmount = $validated['quantity'] - $previousQuantity;
            }

            $stock->save();

            StockHistory::create([
                'product_id' => $product->id,
                'change_type' => $validated['change_type'],
                'previous_quantity' => $previousQuantity,
                'new_quantity' => $stock->quantity,
                'change_amount' => $changeAmount,
                'notes' => $validated['notes'] ?? 'Stock adjustment',
                'admin_id' => auth()->guard('admin')->id(),
            ]);

            DB::commit();

            // Untuk AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Stock updated successfully.',
                    'stock_quantity' => $stock->quantity,
                    'product_id' => $product->id
                ]);
            }

            return back()->with('success', 'Stock updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Untuk AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update stock: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to update stock: ' . $e->getMessage());
        }
    }
}