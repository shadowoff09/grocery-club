<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class BoardController extends Controller
{

    public function userManagement()
    {
        // Current data
        $totalUsers = User::count();
        // a user is active if they logged within 24 hours
        $activeUsers = User::whereRaw("JSON_EXTRACT(custom, '$.last_login_at') >= ?", [now()->subDay()->toDateTimeString()])->count();
        $boardMembers = User::where('type', 'board')->count();

        // Get last month's data (30 days ago)
        $lastMonth = now()->subDays(30);
        $lastMonthTotalUsers = User::where('created_at', '<', $lastMonth)->count();
        $lastMonthActiveUsers = User::whereRaw("JSON_EXTRACT(custom, '$.last_login_at') >= ?", [$lastMonth->copy()->subDay()->toDateTimeString()])
            ->whereRaw("JSON_EXTRACT(custom, '$.last_login_at') < ?", [$lastMonth->toDateTimeString()])
            ->count();
        $lastMonthBoardMembers = User::where('type', 'board')
            ->where('created_at', '<', $lastMonth)
            ->count();

        // Calculate percentage trends
        $totalUsersTrend = $lastMonthTotalUsers > 0
            ? round(($totalUsers - $lastMonthTotalUsers) / $lastMonthTotalUsers * 100, 1)
            : 0;

        $activeUsersTrend = $lastMonthActiveUsers > 0
            ? round(($activeUsers - $lastMonthActiveUsers) / $lastMonthActiveUsers * 100, 1)
            : 0;

        $boardMembersTrend = $lastMonthBoardMembers > 0
            ? round(($boardMembers - $lastMonthBoardMembers) / $lastMonthBoardMembers * 100, 1)
            : 0;

        $users = User::paginate(10);

        return view('components.board.user-management', [
            'totalUsers' => $totalUsers,
            'boardMembers' => $boardMembers,
            'activeUsers' => $activeUsers,
            'totalUsersTrend' => $totalUsersTrend,
            'activeUsersTrend' => $activeUsersTrend,
            'boardMembersTrend' => $boardMembersTrend,
            'users' => $users,
        ]);
    }


    public function userDetail(User $user)
    {
        // Ensure only board members can access this
        abort_if(!auth()->user()->isBoardMember(), 403);

        $userCard = $user->card;

        // get operations for the user card with pagination
        $operations = $userCard ? $userCard->operations()->latest()->paginate(10) : null;

        // Get the last login timestamp from the custom field
        $lastLoginAt = null;
        $custom = $user->custom;
        if (is_array($custom) && isset($custom['last_login_at'])) {
            $lastLoginAt = Carbon::parse($custom['last_login_at'])->diffForHumans();
        }

        // Get user data and any related information you want to display
        $userData = [
            'user' => $user,
            'operations' => $operations,
            'memberSince' => $user->created_at->diffForHumans(),
            'lastLogin' => $lastLoginAt ?? 'Never',
        ];

        // Check if the activities relationship exists before trying to use it
        if (method_exists($user, 'activities')) {
            $userData['activities'] = $user->activities()->latest()->take(5)->get();
        }

        return view('components.board.user-detail', $userData);
    }

    public function statistics()
    {
        // 1. Orders by Status for Pie Chart
        $numberOrdersByType = Order::selectRaw('COUNT(*) as count, status')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });

        $pieChartData = [
            'labels' => $numberOrdersByType->keys()->toArray(),
            'values' => $numberOrdersByType->values()->toArray(),
        ];

        // 2. Monthly Orders for Bar Chart
        $monthlyOrders = [];
        $monthLabels = [];

        // Get orders for the last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M');
            $monthLabels[] = $monthName;

            $count = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $monthlyOrders[] = $count;
        }

        $barChartData = [
            'labels' => $monthLabels,
            'values' => $monthlyOrders,
        ];

        // 3. User Growth for Line Chart
        $userGrowth = [];
        $monthsForUsers = [];

        // Get user registration counts for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthsForUsers[] = $date->format('M');

            $count = User::whereYear('created_at', '<=', $date->year)
                ->whereMonth('created_at', '<=', $date->month)
                ->count();

            $userGrowth[] = $count;
        }

        $lineChartData = [
            'labels' => $monthsForUsers,
            'values' => $userGrowth,
        ];

        // 4. User Type Distribution for Radar Chart
        $userTypes = User::selectRaw('COUNT(*) as count, type')
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => $item->count];
            });

        $radarChartData = [
            'indicators' => array_map(function($type) {
                return ['name' => ucfirst($type), 'max' => User::count()];
            }, array_keys($userTypes->toArray())),
            'values' => $userTypes->values()->toArray(),
            'names' => $userTypes->keys()->toArray(),
        ];

        return view('statistics.index', compact(
            'pieChartData', 
            'barChartData', 
            'lineChartData', 
            'radarChartData'
        ));
    }


}
