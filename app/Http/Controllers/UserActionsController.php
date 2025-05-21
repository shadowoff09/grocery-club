<?php

namespace App\Http\Controllers;

use App\Models\User;
use Masmerise\Toaster\Toaster;

class UserActionsController extends Controller
{
    public function approveMembership(User $user)
    {
        $user->update([
            'type' => 'member'
        ]);

        Toaster::success('Membership approved successfully!');
        return back()->with('success', 'Membership approved successfully!');
    }

    public function promoteToBoard(User $user)
    {
        $user->update([
            'type' => 'board'
        ]);

        Toaster::success('User promoted to board member successfully!');
        return back()->with('success', 'User promoted to board member successfully!');
    }

    public function demoteToMember(User $user)
    {
        $user->update([
            'type' => 'member'
        ]);

        Toaster::success('User demoted to member successfully!');
        return back()->with('success', 'User demoted to member successfully!');
    }

    public function toggleLock(User $user)
    {

        if (auth()->id() === $user->id) {
            Toaster::error('You cannot block/unblock your own account.');
            return back();
        }
        if ($user->type === 'board') {
            Toaster::error('You cannot block/unblock a board member.');
            return back();
        }
        $user->update([
            'blocked' => $user->blocked == 1 ? 0 : 1
        ]);

        $user->refresh();

        $message = $user->blocked == 1 ?
            'Account blocked successfully!' :
            'Account unblocked successfully!';

        Toaster::success($message);
        return back()->with('success', $message);
    }

    public function toggleMembership(User $user)
    {
        if ($user->deleted_at === null) {
            $user->update([
                'deleted_at' => now()
            ]);
            Toaster::success('Membership canceled successfully!');
        } else {
            $user->update([
                'deleted_at' => null
            ]);
            Toaster::success('Membership reactivated successfully!');
        }

        return back()->with('success', 'Membership status updated successfully!');
    }

}
