<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'stock_history';

    protected $fillable = [
        'product_id',
        'change_type',
        'previous_quantity',
        'new_quantity',
        'change_amount',
        'notes',
        'admin_id',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}