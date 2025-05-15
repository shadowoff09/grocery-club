<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_id',
        'status',
        'date',
        'total_items',
        'shipping_cost',
        'total',
        'nif',
        'delivery_address',
        'pdf_receipt',
        'cancel_reason',
        'custom',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'total_items' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'custom' => 'json',
    ];

    /**
     * Get the member that owns the order.
     */
    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(ItemOrder::class);
    }

    /**
     * Get the operations associated with this order.
     */
    public function operations()
    {
        return $this->hasMany(Operation::class);
    }

    /**
     * Check if the order is cancelable
     * 
     * @return bool
     */
    public function isCancelable()
    {
        // Orders can only be canceled if they are pending
        return $this->status === 'pending';
    }
}
