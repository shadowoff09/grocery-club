<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// TODO

class Operation extends Model
{
    use SoftDeletes;

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

    // Liga ao cartão
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    // Liga à encomenda (se aplicável)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

