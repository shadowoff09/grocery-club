<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'registered_by_user_id',
        'quantity_changed',
    ];

    protected $casts = [
        'quantity_changed' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }

    public static function createAdjustment($productId, $quantityChanged, $userId)
    {
        $adjustment = self::create([
            'product_id' => $productId,
            'registered_by_user_id' => $userId,
            'quantity_changed' => $quantityChanged,
        ]);

        // Apply the adjustment to the product stock
        Product::find($productId)->increment('stock', $quantityChanged);

        return $adjustment;
    }
} 