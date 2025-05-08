<?php

namespace App\Http\Controllers;

use App\Charts\HighChart;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BoardController extends Controller
{

    public function membershipFee()
    {
        // Ensure only board members can access this
        abort_if(!auth()->user()->isBoardMember(), 403);

        return view('components.board.business-settings');
    }

    public function userManagement()
    {
        // Current data
        $totalUsers = User::count();
        // a user is active if they logged within 24 hours
        $activeUsers = User::where('last_login_at', '>=', now()->subDay())->count();
        $boardMembers = User::where('type', 'board')->count();

        // Get last month's data (30 days ago)
        $lastMonth = now()->subDays(30);
        $lastMonthTotalUsers = User::where('created_at', '<', $lastMonth)->count();
        $lastMonthActiveUsers = User::where('last_login_at', '>=', $lastMonth->copy()->subDay())
            ->where('last_login_at', '<', $lastMonth)
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

        // Get user data and any related information you want to display
        $userData = [
            'user' => $user,
            'operations' => $operations,
            'memberSince' => $user->created_at->diffForHumans(),
            'lastLogin' => $user->last_login_at ? Carbon::createFromTimestamp(strtotime($user->last_login_at))->diffForHumans() : 'Never',
        ];

        // Check if the activities relationship exists before trying to use it
        if (method_exists($user, 'activities')) {
            $userData['activities'] = $user->activities()->latest()->take(5)->get();
        }

        return view('components.board.user-detail', $userData);
    }

    public function statistics()
    {
        $numberOrdersByType = Order::selectRaw('COUNT(*) as count, status')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });

        $chart = new HighChart();

        $chart->labels(['Completed', 'Pending', 'Cancelled']);
        $chart->dataset('Orders', 'pie', [
            $numberOrdersByType['completed'] ?? 0,
            $numberOrdersByType['pending'] ?? 0,
            $numberOrdersByType['cancelled'] ?? 0,
        ])->color([
            'rgb(70, 127, 208)',
            'rgb(66, 186, 150)',
            'rgb(96, 92, 168)',
            'rgb(255, 193, 7)',
        ]);

        $chart->displayAxes(false);
        $chart->displayLegend(true);

        $chart->options([
            'chart' => [
                'type' => 'pie',
                'zooming' => [
                    'type' => 'xy',
                ],
                'panning' => [
                    'enabled' => true,
                    'type' => 'xy',
                ],
                'panKey' => 'shift',
                'backgroundColor' => 'transparent',
            ],
            'title' => [
                'text' => 'Orders Statistics',
                'style' => [
                    'color' => '#fff',
                    'fontSize' => '20px',
                ],
            ],
            'plotOptions' => [
                'pie' => [
                    'allowPointSelect' => true,
                    'cursor' => 'pointer',
                    'dataLabels' => [
                        [
                            'enabled' => true,
                            'distance' => 20,
                        ],
                        [
                            'enabled' => true,
                            'distance' => -40,
                            'format' => '{point.percentage:.1f}%',
                            'style' => [
                                'fontSize' => '1.2em',
                                'textOutline' => 'none',
                                'opacity' => 0.7,
                            ],
                            'filter' => [
                                'operator' => '>',
                                'property' => 'percentage',
                                'value' => 10,
                            ],
                        ],
                    ],
                    'showInLegend' => true,
                ],
                'series' => [
                    'dataLabels' => [
                        'enabled' => true,
                        'color' => '#ffff',
                    ],
                ],
            ],
            'legend' => [
                'align' => 'right',
                'verticalAlign' => 'middle',
                'layout' => 'vertical',
                'itemStyle' => [
                    'color' => '#fff',
                    'fontSize' => '14px',
                ],
            ],
            'colors' => [
                'rgb(70, 127, 208)',
                'rgb(66, 186, 150)',
                'rgb(96, 92, 168)',
                'rgb(255, 193, 7)',
                'rgb(220, 53, 69)',
            ],
            'credits' => [
                'enabled' => false,
            ],
            'responsive' => [
                'rules' => [
                    [
                        'condition' => [
                            'maxWidth' => 500,
                        ],
                        'chartOptions' => [
                            'legend' => [
                                'layout' => 'horizontal',
                                'align' => 'center',
                                'verticalAlign' => 'bottom',
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        return view('statistics.index', [
            'chart' => $chart,
        ]);
    }


}
