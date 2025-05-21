<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the order.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Order $order
     * @return bool
     */
    public function view(User $user, Order $order)
    {
        // Only allow users to view their own orders
        return $user->id === $order->member_id || $user->isBoardMember();
    }
}
