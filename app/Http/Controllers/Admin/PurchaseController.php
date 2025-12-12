<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Stock;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with('admin')->withCount('items'); // Tambah withCount('items')

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }

        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }

        $purchases = $query->latest()->paginate(15);
        
        $statuses = ['pending', 'completed', 'cancelled'];

        return view('admin.purchases.index', compact('purchases', 'statuses'));
    }

    public function create()
    {
        $products = Product::with('stock')->whereHas('stock', function ($query) {
            $query->where('quantity', '>', 0);
        })->get();

        return view('admin.purchases.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:200',
            'customer_email' => 'nullable|email|max:200',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            $totalAmount = 0;
            $items = [];

            // Validate stock availability and calculate total
            foreach ($request->items as $item) {
                $product = Product::with('stock')->findOrFail($item['product_id']);
                
                if (!$product->stock) {
                    throw new \Exception("No stock record found for {$product->name}");
                }

                if ($product->stock->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->stock->quantity}");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            // Create purchase
            $purchase = Purchase::create([
                'invoice_number' => $invoiceNumber,
                'admin_id' => auth()->guard('admin')->id(),
                'customer_name' => $request->customer_name ?: 'Walk-in Customer',
                'customer_email' => $request->customer_email,
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'purchase_date' => now(),
            ]);

            // Create purchase items and update stock
            foreach ($items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price_at_purchase' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Update stock
                $stock = $item['product']->stock;
                $previousQuantity = $stock->quantity;
                $stock->quantity -= $item['quantity'];
                $stock->save();

                // Record stock history
                StockHistory::create([
                    'product_id' => $item['product']->id,
                    'change_type' => 'purchase',
                    'previous_quantity' => $previousQuantity,
                    'new_quantity' => $stock->quantity,
                    'change_amount' => -$item['quantity'],
                    'notes' => "Purchase: {$invoiceNumber}",
                    'admin_id' => auth()->guard('admin')->id(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.purchases.index')
                ->with('success', "Purchase created successfully. Invoice: {$invoiceNumber}");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create purchase: ' . $e->getMessage())
                         ->withInput();
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['items.product', 'admin']);
        return view('admin.purchases.show', compact('purchase'));
    }

    public function cancel(Purchase $purchase)
    {
        if ($purchase->status === 'cancelled') {
            return back()->with('error', 'Purchase is already cancelled.');
        }

        // Load items dengan product dan stock
        $purchase->load(['items.product.stock']);

        DB::beginTransaction();

        try {
            // Update purchase status TANPA cancelled_by
            $purchase->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                // 'cancelled_by' => auth()->guard('admin')->id(), // HAPUS jika kolom tidak ada
            ]);

            // Restore stock for each item
            foreach ($purchase->items as $item) {
                $stock = Stock::where('product_id', $item->product_id)->first();
                
                if ($stock) {
                    $previousQuantity = $stock->quantity;
                    $stock->quantity += $item->quantity;
                    $stock->save();

                    // Record stock history
                    StockHistory::create([
                        'product_id' => $item->product_id,
                        'change_type' => 'restock',
                        'previous_quantity' => $previousQuantity,
                        'new_quantity' => $stock->quantity,
                        'change_amount' => $item->quantity,
                        'notes' => "Purchase cancelled: {$purchase->invoice_number}",
                        'admin_id' => auth()->guard('admin')->id(),
                    ]);
                }
            }

            DB::commit();

            return back()->with('success', 'Purchase cancelled successfully. Stock has been restored.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel purchase: ' . $e->getMessage());
        }
    }

    public function destroy(Purchase $purchase)
    {
        if ($purchase->status === 'completed') {
            return back()->with('error', 'Cannot delete a completed purchase. Cancel it first.');
        }

        DB::beginTransaction();

        try {
            $purchase->items()->delete();
            $purchase->delete();

            DB::commit();

            return redirect()->route('admin.purchases.index')
                ->with('success', 'Purchase deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete purchase: ' . $e->getMessage());
        }
    }
}