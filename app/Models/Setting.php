<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['membership_fee'];

    protected $casts = [
        'membership_fee' => 'decimal:2',
    ];

    public static function getMembershipFee()
    {
        return self::first()->membership_fee ?? 0;
    }
}
