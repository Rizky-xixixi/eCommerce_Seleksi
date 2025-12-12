<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // PASTIKAN tidak ada protected $table = 'stock_history';
    // HAPUS atau KOMENTARI jika ada:
    // protected $table = 'stock_history'; // ❌ SALAH - HAPUS BARIS INI
    
    // Atau ganti dengan ini jika tabel produk bukan 'products':
    // protected $table = 'products'; // ✅ BENAR

    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // RELASI ke Stock - PASTIKAN ada
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    // RELASI ke StockHistory
    public function stockHistory()
    {
        return $this->hasMany(StockHistory::class, 'product_id');
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    // ACCESSOR untuk stock_quantity - FIXED VERSION
    public function getStockQuantityAttribute()
    {
        // Cek cache pertama
        if (isset($this->attributes['stock_quantity'])) {
            return $this->attributes['stock_quantity'];
        }
        
        // Cek apakah relasi sudah dimuat
        if ($this->relationLoaded('stock') && $this->stock) {
            return $this->stock->quantity;
        }
        
        // Jika belum, query langsung
        return Stock::where('product_id', $this->id)->value('quantity') ?? 0;
    }

    // ACCESSOR untuk stock_status - SIMPLE VERSION
    public function getStockStatusAttribute()
    {
        $quantity = $this->stock_quantity;
        
        if ($quantity <= 0) {
            return 'Out of Stock';
        } elseif ($quantity <= 5) {
            return 'Low Stock';
        } else {
            return 'In Stock';
        }
    }

    // ACCESSOR untuk stock_status_class - SIMPLE VERSION
    public function getStockStatusClassAttribute()
    {
        $quantity = $this->stock_quantity;
        
        if ($quantity <= 0) {
            return 'danger';
        } elseif ($quantity <= 5) {
            return 'warning';
        } else {
            return 'success';
        }
    }
    
    // SCOPE untuk produk dengan stok > 0
    public function scopeInStock($query)
    {
        return $query->whereHas('stock', function($q) {
            $q->where('quantity', '>', 0);
        });
    }
    
    // SCOPE untuk produk low stock
    public function scopeLowStock($query)
    {
        return $query->whereHas('stock', function($q) {
            $q->where('quantity', '<=', 5)->where('quantity', '>', 0);
        });
    }
}