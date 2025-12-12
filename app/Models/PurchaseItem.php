<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use App\Models\Product;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'price_at_purchase',
        'subtotal',
    ];

    protected $casts = [
        'price_at_purchase' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price_at_purchase, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}