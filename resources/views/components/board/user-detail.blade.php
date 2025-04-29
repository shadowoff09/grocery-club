<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">User Details</h1>
            <a href="{{ route('board.users') }}" class="text-blue-500 hover:text-blue-700">
                <flux:button variant="outline" icon="arrow-left" class="cursor-pointer">Back to Users</flux:button>
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

                        @if($user->deleted_at)
                            <div class="px-3 py-1 text-xs rounded-full text-white bg-red-500">Membership Cancelled</div>
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

                        @if(isset($user->nif))
                            <div>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">NIF</p>
                                <p>{{ $user->nif }}</p>
                            </div>
                        @endif
                    </div>
                    <flux:separator class="mt-6"/>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-6">
                        @if(isset($user->default_delivery_address))
                            <div>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Preferred Delivery Address</p>
                                <p>{{ $user->default_delivery_address }}</p>
                            </div>
                        @endif
                        @if(isset($user->default_payment_type))
                            <div>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Preferred Payment Type</p>
                                <p>{{ $user->default_payment_type }}</p>
                            </div>
                        @endif

                        @if(isset($user->default_payment_reference))
                            <div>
                                <p class="text-sm text-neutral-500 dark:text-neutral-400">Preferred Payment
                                    Reference</p>
                                <p>{{ $user->default_payment_reference }}</p>
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
                        class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
                        <h3 class="text-lg font-semibold mb-4">Actions</h3>

                        <div class="flex flex-wrap gap-3">
                            @if($user->type === 'pending_member')
                                <form action="{{ route('board.users.approve', $user) }}" method="POST">
                                    @csrf
                                    <flux:button type="submit" icon="check" variant="primary" class="cursor-pointer">
                                        Approve Membership
                                    </flux:button>
                                </form>
                            @endif

                            @if($user->type !== 'board')
                                <form action="{{ route('board.users.promote', $user) }}" method="POST">
                                    @csrf
                                    <flux:button type="submit" icon="user-plus" class="cursor-pointer" variant="outline">
                                        Promote to Board
                                    </flux:button>
                                </form>
                            @endif

                            @if($user->type === 'board' && auth()->user()->id !== $user->id)
                                <form action="{{ route('board.users.demote', $user) }}" method="POST">
                                    @csrf
                                    <flux:button type="submit" icon="user-minus" class="cursor-pointer" variant="outline">
                                        Demote to Member
                                    </flux:button>
                                </form>
                            @endif

                            @if($user->type === 'member' || $user->type === 'pending_member')
                                <form action="{{ route('board.users.toggle-lock', $user) }}" method="POST">
                                    @csrf
                                    <flux:button type="submit" icon="lock-closed" class="cursor-pointer"
                                                 variant="{{ $user->blocked ? 'outline' : 'danger' }}">
                                        {{ $user->blocked ? 'Unlock Account' : 'Lock Account' }}
                                    </flux:button>
                                </form>
                            @endif

                                @if($user->type === 'member')
                                    <form action="{{ route('board.users.toggle-membership', $user) }}" method="POST">
                                        @csrf
                                        <flux:button type="submit" icon="{{ $user->deleted_at ? 'lock-open' : 'lock-closed' }}"
                                                     variant="{{ $user->deleted_at ? 'outline' : 'danger' }}" class="cursor-pointer">
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
                        class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <h3 class="text-lg font-semibold">Card Details</h3>
                                <div class="bg-stone-900 text-white px-3 py-1 rounded-md">
                                    Balance: {{ number_format($user->card->balance, 2) }} €
                                </div>
                            </div>
                            <span class="text-sm text-neutral-500">Card #{{ $user->card->card_number }}</span>
                        </div>
                        @if($operations->count() > 0)
                            <div class="space-y-4">
                                @foreach($operations as $operation)
                                    <div
                                        class="flex items-start justify-between gap-3 p-3 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="h-2 w-2 mt-2 rounded-full {{ $operation->type === 'debit' ? 'bg-red-500' : 'bg-green-500' }}"></div>
                                            <div>
                                                @if($operation->type === 'debit')
                                                    @if($operation->debit_type === 'membership_fee')
                                                        <p class="font-medium">Membership Fee Payment</p>
                                                    @elseif($operation->debit_type === 'order')
                                                        <p class="font-medium">Order Purchase <span
                                                                class="text-neutral-500">#{{$operation->order_id}}</span>
                                                        </p>
                                                    @else
                                                        <p class="font-medium">Unknown Debit</p>
                                                    @endif
                                                @elseif($operation->type === 'credit')
                                                    @if($operation->credit_type === 'payment')
                                                        <p class="font-medium">
                                                            Payment ({{ $operation->payment_type ?? 'Unknown' }})
                                                            @if($operation->payment_reference)
                                                                <span class="text-neutral-500">Ref: {{$operation->payment_reference}}</span>
                                                            @endif
                                                        </p>
                                                    @elseif($operation->credit_type === 'order_cancellation')
                                                        <p class="font-medium">Order Cancellation <span
                                                                class="text-neutral-500">#{{$operation->order_id}}</span>
                                                        </p>
                                                    @else
                                                        <p class="font-medium">Unknown Credit</p>
                                                    @endif
                                                @else
                                                    <p class="font-medium">Unknown Transaction</p>
                                                @endif
                                                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ \Carbon\Carbon::parse($operation->date)->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="{{ $operation->type === 'debit' ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $operation->type === 'debit' ? '-' : '+' }}{{ number_format($operation->value, 2) }} €
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-neutral-500 dark:text-neutral-400">No transaction history available</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
