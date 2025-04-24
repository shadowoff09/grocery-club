<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">User Details</h1>
            <a href="{{ route('board.users') }}" class="text-blue-500 hover:text-blue-700">
                <flux:button variant="outline" icon="arrow-left">Back to Users</flux:button>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- User Profile Card -->
            <div
                class="md:col-span-1 bg-white dark:bg-neutral-800 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex flex-col items-center text-center space-y-4">
                    @if($user->photo)
                        <img src="{{ asset('storage/users/' . $user->photo) }}"
                             alt="{{ $user->name }}"
                             class="h-32 w-32 rounded-full object-cover">
                    @else
                        <div
                            class="h-32 w-32 rounded-full bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center text-3xl font-bold">
                            {{ $user->initials() }}
                        </div>
                    @endif

                    <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                    <p class="text-neutral-500 dark:text-neutral-400">{{ $user->email }}</p>

                    <div class="flex items-center justify-center gap-2">
                        <div class="px-3 py-1 text-xs rounded-full
                        @switch($user->type)
                            @case('board')
                                bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                @break
                            @case('employee')
                                bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @break
                            @case('member')
                                bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @break
                            @case('pending_member')
                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @break
                        @endswitch
                    ">
                            @switch($user->type)
                                @case('board')
                                    Board Member
                                    @break
                                @case('employee')
                                    Club Employee
                                    @break
                                @case('member')
                                    Club Member
                                    @break
                                @case('pending_member')
                                    Pending Approval
                                    @break
                            @endswitch


                        </div>

                        @if($user->blocked)
                            <div class="px-3 py-1 text-xs rounded-full text-white bg-red-500">Locked</div>
                        @endif

                        @if(auth()->user()->id === $user->id)
                            <div class="px-3 py-1 text-xs rounded-full text-white bg-blue-500">You</div>
                        @endif
                    </div>

                    <div class="w-full pt-4 border-t border-neutral-200 dark:border-neutral-700">
                        <p class="text-sm flex justify-between">
                            <span class="text-neutral-500 dark:text-neutral-400">Member since:</span>
                            <span>{{ $memberSince }}</span>
                        </p>
                        <p class="text-sm flex justify-between mt-2">
                            <span class="text-neutral-500 dark:text-neutral-400">Last login:</span>
                            <span>{{ $lastLogin }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- User Details and Actions -->
            <div class="md:col-span-2 space-y-6">
                <!-- User Details -->
                <div
                    class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
                    <h3 class="text-lg font-semibold mb-4">Personal Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">Full Name</p>
                            <p>{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">Email</p>
                            <p>{{ $user->email }}</p>
                        </div>

                        @if(isset($user->phone))
                            <div>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Phone</p>
                                <p>{{ $user->phone }}</p>
                            </div>
                        @endif

                        @if(isset($user->gender))
                            <div>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Gender</p>
                                <p>
                                    @if($user->gender === 'F')
                                        Female
                                    @else
                                        Male
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- User Actions -->
                <div
                    class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
                    <h3 class="text-lg font-semibold mb-4">Actions</h3>

                    <div class="flex flex-wrap gap-3">
                        @if($user->type === 'pending_member')
                            <form action="{{ route('board.users.approve', $user) }}" method="POST">
                                @csrf
                                <flux:button type="submit" icon="check" variant="primary">
                                    Approve Membership
                                </flux:button>
                            </form>
                        @endif

                        @if($user->type !== 'board')
                            <form action="{{ route('board.users.promote', $user) }}" method="POST">
                                @csrf
                                <flux:button type="submit" icon="user-plus" variant="outline">
                                    Promote to Board
                                </flux:button>
                            </form>
                        @endif

                        @if($user->type === 'employee')
                            <form action="{{ route('board.users.demote', $user) }}" method="POST">
                                @csrf
                                <flux:button type="submit" icon="user-minus" variant="outline">
                                    Demote to Member
                                </flux:button>
                            </form>
                        @endif

                        <form action="{{ route('board.users.message', $user) }}" method="POST">
                            @csrf
                            <flux:button type="submit" icon="envelope">
                                Send Message
                            </flux:button>
                        </form>

                        <form action="{{ route('board.users.toggle-lock', $user) }}" method="POST">
                            @csrf
                            <flux:button type="submit" icon="lock-closed"
                                         variant="{{ $user->blocked ? 'outline' : 'danger' }}">
                                {{ $user->blocked ? 'Unlock Account' : 'Lock Account' }}
                            </flux:button>
                        </form>

                    </div>

                </div>

                {{--                <!-- Recent Activity -->--}}
                {{--                @if(isset($activities))--}}
                {{--                    <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">--}}
                {{--                        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>--}}

                {{--                        @if($activities->count() > 0)--}}
                {{--                            <div class="space-y-4">--}}
                {{--                                @foreach($activities as $activity)--}}
                {{--                                    <div class="flex items-start gap-3">--}}
                {{--                                        <div class="h-2 w-2 mt-2 rounded-full bg-blue-500"></div>--}}
                {{--                                        <div>--}}
                {{--                                            <p>{{ $activity->description }}</p>--}}
                {{--                                            <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $activity->created_at->diffForHumans() }}</p>--}}
                {{--                                        </div>--}}
                {{--                                    </div>--}}
                {{--                                @endforeach--}}
                {{--                            </div>--}}
                {{--                        @else--}}
                {{--                            <p class="text-neutral-500 dark:text-neutral-400">No recent activity found.</p>--}}
                {{--                        @endif--}}
                {{--                    </div>--}}
                {{--                @endif--}}
            </div>
        </div>
    </div>
</x-layouts.app>
