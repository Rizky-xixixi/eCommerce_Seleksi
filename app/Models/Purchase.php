<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'admin_id',
        'customer_name',
        'customer_email',
        'total_amount',
        'status',
        'purchase_date',
        'cancelled_at',
        // 'cancelled_by', // HAPUS dari fillable jika kolom tidak ada
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'cancelled_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    // HAPUS atau COMMENT method cancelledBy() jika kolom tidak ada
    // public function cancelledBy()
    // {
    //     return $this->belongsTo(Admin::class, 'cancelled_by');
    // }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function cancel($adminId)
    {
        if ($this->status === 'cancelled') {
            return $this;
        }

        // Load items dengan product dan stock untuk memastikan data tersedia
        $this->load(['items.product.stock']);

        // Update HANYA kolom yang ada di database
        $updateData = [
            'status' => 'cancelled',
            'cancelled_at' => now(),
            // 'cancelled_by' => $adminId, // HAPUS jika kolom tidak ada
        ];

        $this->update($updateData);

        // Restore stock for each item
        foreach ($this->items as $item) {
            $product = $item->product;
            if ($product && $product->stock) {
                $product->stock->increment('quantity', $item->quantity);
            }
        }

        return $this;
    }
}