<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// TODO

class Card extends Model implements Builder
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'card_number', 'balance'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id');
    }

    public function operations() {
        return $this->hasMany(Operation::class, 'card_id');
    }
}

