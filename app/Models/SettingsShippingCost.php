<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingsShippingCost extends Model
{
    protected $fillable = [
        'min_value_threshold',
        'max_value_threshold',
        'shipping_cost',
    ];

    protected $casts = [
        'min_value_threshold' => 'decimal:2',
        'max_value_threshold' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
    ];
}

