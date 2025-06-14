<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'category_id',
        'description',
        'photo',
        'stock',
        'discount_min_qty',
        'discount',
        'deleted_at'
    ];

    // Product belongsTo category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Product has many ItemOrders
    public function itemOrders()
    {
        return $this->hasMany(ItemOrder::class);
    }

    // Product appears in many Orders through ItemOrders
    public function orders()
    {
        return $this->hasManyThrough(Order::class, ItemOrder::class, 'product_id', 'id', 'id', 'order_id')
                    ->distinct()
                    ->orderBy('orders.created_at', 'desc');
    }

    // Product has many SupplyOrders
    public function supplyOrders()
    {
        return $this->hasMany(SupplyOrder::class);
    }

    // Get active supply orders
    public function activeSupplyOrders()
    {
        return $this->hasMany(SupplyOrder::class)->where('status', 'requested');
    }
}

