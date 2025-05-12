<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// TODO

class Operation extends Model
{

    protected $fillable = [
        'card_id',
        'type',
        'value',
        'date',
        'debit_type',
        'credit_type',
        'payment_type',
        'payment_reference',
        'order_id',
        'custom',
    ];

    // Operation belongsTo card
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    // Operation belongsTo order (if applicable)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

