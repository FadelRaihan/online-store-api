<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_price',
    ];

    /**
     * Relasi ke OrderItem
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi many-to-many ke Product melalui order_items
     */
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'order_items'
        )->withPivot([
                    'quantity',
                    'price',
                ]);
    }
}