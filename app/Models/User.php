<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_at',
        'gender',
        'default_delivery_address',
        'nif',
        'default_payment_type',
        'default_payment_reference',
        'photo',
        'type',
        'blocked',
        'deleted_at'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked' => 'boolean',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function isBoardMember(): bool
    {
        return $this->type === 'board';
    }

    public function isEmployee(): bool
    {
        return $this->type === 'employee';
    }

    public function isMember(): bool
    {
        return $this->type === 'member';
    }

    public function isPendingMember(): bool
    {
        return $this->type === 'pending_member';
    }

    public function card(): \Illuminate\Database\Eloquent\Relations\HasOne|User
    {
        return $this->hasOne(Card::class, 'id', 'id');
    }


}
