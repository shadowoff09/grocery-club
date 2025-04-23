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
}
