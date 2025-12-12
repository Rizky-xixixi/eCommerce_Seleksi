<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'username',
        'password',
        'name',
        'email',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'admin_id');
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class, 'admin_id');
    }
}