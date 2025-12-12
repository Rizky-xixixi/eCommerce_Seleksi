<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'last_restocked',
    ];

    protected $casts = [
        'last_restocked' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function restock($quantity, $adminId, $notes = null)
    {
        $previousQuantity = $this->quantity;
        $this->quantity += $quantity;
        $this->last_restocked = now();
        $this->save();

        // Record history
        StockHistory::create([
            'product_id' => $this->product_id,
            'change_type' => 'restock',
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $this->quantity,
            'change_amount' => $quantity,
            'notes' => $notes,
            'admin_id' => $adminId,
        ]);
    }

    public function reduce($quantity, $adminId, $reason = 'purchase', $notes = null)
    {
        $previousQuantity = $this->quantity;
        $this->quantity -= $quantity;
        $this->save();

        // Record history
        StockHistory::create([
            'product_id' => $this->product_id,
            'change_type' => $reason,
            'previous_quantity' => $previousQuantity,
            'new_quantity' => $this->quantity,
            'change_amount' => -$quantity,
            'notes' => $notes,
            'admin_id' => $adminId,
        ]);
    }
}