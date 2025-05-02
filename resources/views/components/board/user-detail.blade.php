<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold tracking-tight">User Details</h1>
            <a href="{{ route('board.users') }}">
                <flux:button variant="outline" icon="arrow-left" class="shadow-sm hover:shadow-md transition-shadow">
                    Back to Users
                </flux:button>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- User Profile Card -->
            <div
                class="md:col-span-1 bg-white dark:bg-neutral-800 rounded-2xl shadow-lg border border-neutral-200/50 dark:border-neutral-700 p-8">
                <div class="flex flex-col items-center text-center space-y-6">
                    @if($user->photo)
                        <img src="{{ asset('storage/users/' . $user->photo) }}"
                             alt="{{ $user->name }}"
                             class="h-40 w-40 rounded-full object-cover ring-4 ring-neutral-100 dark:ring-neutral-700 shadow-xl">
                    @else
                        <div
                            class="h-40 w-40 rounded-full bg-gradient-to-br from-neutral-100 to-neutral-200 dark:from-neutral-700 dark:to-neutral-800 flex items-center justify-center text-4xl font-bold shadow-xl ring-4 ring-neutral-100 dark:ring-neutral-700">
                            {{ $user->initials() }}
                        </div>
                    @endif

                    <div class="space-y-2">
                        <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                        <p class="text-neutral-600 dark:text-neutral-400">{{ $user->email }}</p>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-2">
                        <div class="px-4 py-1.5 text-sm font-medium rounded-full
                        @switch($user->type)
                            @case('board')
                                bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200
                                @break
                            @case('employee')
                                bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200
                                @break
                            @case('member')
                                bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                                @break
                            @case('pending_member')
                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200
                                @break
                        @endswitch
                        shadow-sm">
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
                            <div
                                class="px-4 py-1.5 text-sm font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200 shadow-sm">
                                Locked
                            </div>
                        @endif

                        @if($user->deleted_at)
                            <div
                                class="px-4 py-1.5 text-sm font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200 shadow-sm">
                                Membership Cancelled
                            </div>
                        @endif

                        @if(auth()->user()->id === $user->id)
                            <div
                                class="px-4 py-1.5 text-sm font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 shadow-sm">
                                You
                            </div>
                        @endif
                    </div>

                    <div class="w-full pt-6 border-t border-neutral-200 dark:border-neutral-700 space-y-3">
                        <p class="text-sm flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Member since:</span>
                            <span class="font-medium">{{ $memberSince }}</span>
                        </p>
                        <p class="text-sm flex justify-between">
                            <span class="text-neutral-600 dark:text-neutral-400">Last login:</span>
                            <span class="font-medium">{{ $lastLogin }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- User Details and Actions -->
            <div class="md:col-span-2 space-y-8">
                <!-- User Details -->
                <div
                    class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg border border-neutral-200/50 dark:border-neutral-700 p-8">
                    <h3 class="text-xl font-bold mb-6">Personal Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-1">Full Name</p>
                            <p class="font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-1">Email</p>
                            <p class="font-medium">{{ $user->email }}</p>
                        </div>

                        @if(isset($user->phone))
                            <div>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-1">Phone</p>
                                <p class="font-medium">{{ $user->phone }}</p>
                            </div>
                        @endif

                        @if(isset($user->gender))
                            <div>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-1">Gender</p>
                                <p class="font-medium">
                                    @if($user->gender === 'F')
                                        Female
                                    @else
                                        Male
                                    @endif
                                </p>
                            </div>
                        @endif

                        @if(isset($user->nif))
                            <div>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-1">NIF</p>
                                <p class="font-medium">{{ $user->nif }}</p>
                            </div>
                        @endif
                    </div>

                    <flux:separator class="my-8"/>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if(isset($user->default_delivery_address))
                            <div>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-1">Preferred Delivery
                                    Address</p>
                                <p class="font-medium">{{ $user->default_delivery_address }}</p>
                            </div>
                        @endif
                        @if(isset($user->default_payment_type))
                            <div>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-1">Preferred Payment
                                    Type</p>
                                <p class="font-medium">{{ $user->default_payment_type }}</p>
                            </div>
                        @endif

                        @if(isset($user->default_payment_reference))
                            <div>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-1">Preferred Payment
                                    Reference</p>
                                <p class="font-medium">{{ $user->default_payment_reference }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- User Actions -->
                @if(
                    $user->type === 'pending_member' ||
                    $user->type !== 'board' ||
                    ($user->type === 'board' && auth()->user()->id !== $user->id) ||
                    in_array($user->type, ['member', 'pending_member'])
                )
                    <div
                        class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg border border-neutral-200/50 dark:border-neutral-700 p-8">
                        <h3 class="text-xl font-bold mb-6">Actions</h3>

                        <div class="flex flex-wrap gap-4">
                            @if($user->type === 'pending_member')
                                <form action="{{ route('board.users.approve', $user) }}" method="POST">
                                    @csrf
                                    <flux:button type="submit" icon="check" variant="primary"
                                                 class="shadow-sm hover:shadow-md transition-shadow">
                                        Approve Membership
                                    </flux:button>
                                </form>
                            @endif

                            @if($user->type !== 'board')
                                <form action="{{ route('board.users.promote', $user) }}" method="POST">
                                    @csrf
                                    <flux:button type="submit" icon="user-plus"
                                                 class="shadow-sm hover:shadow-md transition-shadow" variant="outline">
                                        Promote to Board
                                    </flux:button>
                                </form>
                            @endif

                            @if($user->type === 'board' && auth()->user()->id !== $user->id)
                                <form action="{{ route('board.users.demote', $user) }}" method="POST">
                                    @csrf
                                    <flux:button type="submit" icon="user-minus"
                                                 class="shadow-sm hover:shadow-md transition-shadow" variant="outline">
                                        Demote to Member
                                    </flux:button>
                                </form>
                            @endif

                            @if($user->type === 'member' || $user->type === 'pending_member')
                                <form action="{{ route('board.users.toggle-lock', $user) }}" method="POST">
                                    @csrf
                                    <flux:button type="submit" icon="lock-closed"
                                                 class="shadow-sm hover:shadow-md transition-shadow"
                                                 variant="{{ $user->blocked ? 'outline' : 'danger' }}">
                                        {{ $user->blocked ? 'Unlock Account' : 'Lock Account' }}
                                    </flux:button>
                                </form>
                            @endif

                            @if($user->type === 'member')
                                <form action="{{ route('board.users.toggle-membership', $user) }}" method="POST">
                                    @csrf
                                    <flux:button type="submit"
                                                 icon="{{ $user->deleted_at ? 'lock-open' : 'lock-closed' }}"
                                                 variant="{{ $user->deleted_at ? 'outline' : 'danger' }}"
                                                 class="shadow-sm hover:shadow-md transition-shadow">
                                        {{ $user->deleted_at ? 'Reactivate Membership' : 'Cancel Membership' }}
                                    </flux:button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Recent Activity -->
                @if(isset($operations))
                    <div
                        class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg border border-neutral-200/50 dark:border-neutral-700 p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <h3 class="text-xl font-bold">Card Details</h3>
                                <div
                                    class="bg-gradient-to-br from-neutral-900 to-neutral-800 text-white px-4 py-2 rounded-lg shadow-sm">
                                    Balance: {{ number_format($user->card->balance, 2) }} €
                                </div>
                            </div>
                            <span
                                class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Card #{{ $user->card->card_number }}</span>
                        </div>

                        @if($operations->count() > 0)
                            <div class="space-y-4">
                                @foreach($operations as $operation)
                                    <div
                                        class="flex items-start justify-between gap-4 p-4 rounded-xl hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-all hover:shadow-sm">
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="h-3 w-3 mt-2 rounded-full {{ $operation->type === 'debit' ? 'bg-red-500' : 'bg-green-500' }} shadow-sm"></div>
                                            <div>
                                                @if($operation->type === 'debit')
                                                    @if($operation->debit_type === 'membership_fee')
                                                        <p class="font-semibold">Membership Fee Payment</p>
                                                    @elseif($operation->debit_type === 'order')
                                                        <p class="font-semibold">Order Purchase <span
                                                                class="text-neutral-600 dark:text-neutral-400">#{{$operation->order_id}}</span>
                                                        </p>
                                                    @else
                                                        <p class="font-semibold">Unknown Debit</p>
                                                    @endif
                                                @elseif($operation->type === 'credit')
                                                    @if($operation->credit_type === 'payment')
                                                        <p class="font-semibold">
                                                            Payment ({{ $operation->payment_type ?? 'Unknown' }})
                                                            @if($operation->payment_reference)
                                                                <span class="text-neutral-600 dark:text-neutral-400">Ref: {{$operation->payment_reference}}</span>
                                                            @endif
                                                        </p>
                                                    @elseif($operation->credit_type === 'order_cancellation')
                                                        <p class="font-semibold">Order Cancellation <span
                                                                class="text-neutral-600 dark:text-neutral-400">#{{$operation->order_id}}</span>
                                                        </p>
                                                    @else
                                                        <p class="font-semibold">Unknown Credit</p>
                                                    @endif
                                                @else
                                                    <p class="font-semibold">Unknown Transaction</p>
                                                @endif
                                                <p class="text-sm text-neutral-600 dark:text-neutral-400">{{ \Carbon\Carbon::parse($operation->date)->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="font-semibold {{ $operation->type === 'debit' ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $operation->type === 'debit' ? '-' : '+' }}{{ number_format($operation->value, 2) }} €
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <p class="text-neutral-600 dark:text-neutral-400">No transaction history available</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
