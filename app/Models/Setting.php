<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['membership_fee'];

    // Add this static method to easily get settings
    public static function getMembershipFee()
    {
        return self::first()->membership_fee ?? 0;
    }
}
