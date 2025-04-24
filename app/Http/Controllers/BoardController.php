<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function userManagement()
    {
        $totalUsers = User::count();
        // a user is active if they logged within 24 hours
        $activeUsers = User::where('last_login_at', '>=', now()->subDay())->count();
        $boardMembers = User::where('type', 'board')->count();
        $users = User::paginate(10);

        return view('components.board.user-management', [
            'totalUsers' => $totalUsers,
            'boardMembers' => $boardMembers,
            'activeUsers' => $activeUsers,
            'users' => $users,
        ]);
    }

    public function userDetail(User $user)
    {
        // Ensure only board members can access this
        abort_if(!auth()->user()->isBoardMember(), 403);

        // Get user data and any related information you want to display
        $userData = [
            'user' => $user,
            'memberSince' => $user->created_at->diffForHumans(),
            'lastLogin' => $user->last_login_at ? \Carbon\Carbon::createFromTimestamp(strtotime($user->last_login_at))->diffForHumans() : 'Never',
        ];

        // Check if the activities relationship exists before trying to use it
        if (method_exists($user, 'activities')) {
            $userData['activities'] = $user->activities()->latest()->take(5)->get();
        }

        return view('components.board.user-detail', $userData);
    }


}
