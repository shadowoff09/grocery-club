<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-6 md:grid-cols-3">
            <div class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-md transition-all hover:shadow-lg dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
                <div class="mb-2 flex items-center justify-between">
                    <div class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Total Users</div>
                    <div class="rounded-full bg-blue-100 p-2 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="text-3xl font-bold text-neutral-900 dark:text-white">{{ number_format($totalUsers) }}</div>
                    @if(isset($totalUsersTrend))
                        <div class="mt-1 flex items-center text-xs {{ $totalUsersTrend > 0 ? 'text-green-500' : ($totalUsersTrend < 0 ? 'text-red-500' : 'text-neutral-500') }}">
                            @if($totalUsersTrend > 0)
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                </svg>
                            @elseif($totalUsersTrend < 0)
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1v-5a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586l-4.293-4.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                            @endif
                            <span>{{ abs($totalUsersTrend) }}% from last month</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-md transition-all hover:shadow-lg dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
                <div class="mb-2 flex items-center justify-between">
                    <div class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Active Users</div>
                    <div class="rounded-full bg-green-100 p-2 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="text-3xl font-bold text-neutral-900 dark:text-white">{{ number_format($activeUsers) }}</div>
                    @if(isset($activeUsersTrend))
                        <div class="mt-1 flex items-center text-xs {{ $activeUsersTrend > 0 ? 'text-green-500' : ($activeUsersTrend < 0 ? 'text-red-500' : 'text-neutral-500') }}">
                            @if($activeUsersTrend > 0)
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                </svg>
                            @elseif($activeUsersTrend < 0)
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1v-5a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586l-4.293-4.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                            @endif
                            <span>{{ abs($activeUsersTrend) }}% from last month</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex flex-col justify-between rounded-xl border border-neutral-200 bg-white p-6 shadow-md transition-all hover:shadow-lg dark:border-neutral-700 dark:bg-neutral-800 dark:hover:border-neutral-600">
                <div class="mb-2 flex items-center justify-between">
                    <div class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Board Members</div>
                    <div class="rounded-full bg-purple-100 p-2 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="text-3xl font-bold text-neutral-900 dark:text-white">{{ number_format($boardMembers) }}</div>
                    @if(isset($boardMembersTrend))
                        <div class="mt-1 flex items-center text-xs {{ $boardMembersTrend > 0 ? 'text-green-500' : ($boardMembersTrend < 0 ? 'text-red-500' : 'text-neutral-500') }}">
                            @if($boardMembersTrend > 0)
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                </svg>
                            @elseif($boardMembersTrend < 0)
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1v-5a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586l-4.293-4.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                            @endif
                            <span>{{ abs($boardMembersTrend) }}% from last month</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>



        <livewire:user-table/>

    </div>
</x-layouts.app>
