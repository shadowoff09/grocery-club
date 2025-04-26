<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    protected $fillable = [
        'id', 'card_number', 'balance'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class, 'card_id');
    }
}

