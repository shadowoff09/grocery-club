<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-8 p-6 mx-auto">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">User Details</h1>
            <a href="{{ route('board.users') }}">
                <flux:button variant="outline" icon="arrow-left" class="cursor-pointer shadow-sm hover:shadow-md transition-all duration-200">
                    Back to Users
                </flux:button>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- User Profile Card -->
            <div
                class="md:col-span-1 bg-white dark:bg-neutral-800 rounded-2xl shadow-lg border border-neutral-200/50 dark:border-neutral-700 p-8 transition-all duration-200 hover:shadow-xl">
                <div class="flex flex-col items-center text-center space-y-8">
                    @if($user->photo)
                        <img src="{{ asset('storage/users/' . $user->photo) }}"
                             alt="{{ $user->name }}"
                             class="h-44 w-44 rounded-full object-cover ring-4 ring-indigo-100 dark:ring-indigo-900/50 shadow-xl transition-transform duration-300 hover:scale-105">
                    @else
                        <div
                            class="h-44 w-44 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 dark:from-indigo-700 dark:to-indigo-900 flex items-center justify-center text-5xl font-bold text-white shadow-xl ring-4 ring-indigo-100 dark:ring-indigo-900/50 transition-transform duration-300 hover:scale-105">
                            {{ $user->initials() }}
                        </div>
                    @endif

                    <div class="space-y-2">
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $user->name }}</h2>
                        <p class="text-zinc-600 dark:text-zinc-400">{{ $user->email }}</p>
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

                    <div class="w-full pt-6 border-t border-neutral-200 dark:border-neutral-700 space-y-4">
                        <p class="text-sm flex justify-between items-center p-2 hover:bg-zinc-50 dark:hover:bg-zinc-700/30 rounded-lg transition-colors duration-150">
                            <span class="text-zinc-600 dark:text-zinc-400 flex items-center gap-2">
                                <flux:icon name="calendar" class="w-4 h-4" />
                                Member since:
                            </span>
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $memberSince }}</span>
                        </p>
                        <p class="text-sm flex justify-between items-center p-2 hover:bg-zinc-50 dark:hover:bg-zinc-700/30 rounded-lg transition-colors duration-150">
                            <span class="text-zinc-600 dark:text-zinc-400 flex items-center gap-2">
                                <flux:icon name="clock" class="w-4 h-4" />
                                Last login:
                            </span>
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $lastLogin }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- User Details and Actions -->
            <div class="md:col-span-2 space-y-8">
                <!-- User Details -->
                <div
                    class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg border border-neutral-200/50 dark:border-neutral-700 p-8 transition-all duration-200 hover:shadow-xl">
                    <h3 class="text-xl font-bold mb-6 text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                        <flux:icon name="user" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                        Personal Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-zinc-50 dark:bg-zinc-700/20 p-4 rounded-xl">
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1 flex items-center gap-1">
                                <flux:icon name="user-circle" class="w-3.5 h-3.5" />
                                Full Name
                            </p>
                            <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</p>
                        </div>
                        <div class="bg-zinc-50 dark:bg-zinc-700/20 p-4 rounded-xl">
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1 flex items-center gap-1">
                                <flux:icon name="envelope" class="w-3.5 h-3.5" />
                                Email
                            </p>
                            <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $user->email }}</p>
                        </div>

                        @if(isset($user->phone))
                            <div class="bg-zinc-50 dark:bg-zinc-700/20 p-4 rounded-xl">
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1 flex items-center gap-1">
                                    <flux:icon name="phone" class="w-3.5 h-3.5" />
                                    Phone
                                </p>
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $user->phone }}</p>
                            </div>
                        @endif

                        @if(isset($user->gender))
                            <div class="bg-zinc-50 dark:bg-zinc-700/20 p-4 rounded-xl">
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1 flex items-center gap-1">
                                    <flux:icon name="user" class="w-3.5 h-3.5" />
                                    Gender
                                </p>
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">
                                    @if($user->gender === 'F')
                                        Female
                                    @else
                                        Male
                                    @endif
                                </p>
                            </div>
                        @endif

                        @if(isset($user->nif))
                            <div class="bg-zinc-50 dark:bg-zinc-700/20 p-4 rounded-xl">
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1 flex items-center gap-1">
                                    <flux:icon name="identification" class="w-3.5 h-3.5" />
                                    NIF
                                </p>
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $user->nif }}</p>
                            </div>
                        @endif
                    </div>

                    @if(isset($user->default_delivery_address) || isset($user->default_payment_type) || isset($user->default_payment_reference))
                        <flux:separator class="my-8"/>

                        <h3 class="text-xl font-bold mb-6 text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                            <flux:icon name="credit-card" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                            Payment & Delivery Preferences
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(isset($user->default_delivery_address))
                                <div class="bg-zinc-50 dark:bg-zinc-700/20 p-4 rounded-xl">
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1 flex items-center gap-1">
                                        <flux:icon name="map-pin" class="w-3.5 h-3.5" />
                                        Preferred Delivery Address
                                    </p>
                                    <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $user->default_delivery_address }}</p>
                                </div>
                            @endif
                            @if(isset($user->default_payment_type))
                                <div class="bg-zinc-50 dark:bg-zinc-700/20 p-4 rounded-xl">
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1 flex items-center gap-1">
                                        <flux:icon name="currency-euro" class="w-3.5 h-3.5" />
                                        Preferred Payment Type
                                    </p>
                                    <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $user->default_payment_type }}</p>
                                </div>
                            @endif

                            @if(isset($user->default_payment_reference))
                                <div class="bg-zinc-50 dark:bg-zinc-700/20 p-4 rounded-xl">
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1 flex items-center gap-1">
                                        <flux:icon name="credit-card" class="w-3.5 h-3.5" />
                                        Preferred Payment Reference
                                    </p>
                                    <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ $user->default_payment_reference }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- User Actions -->
                @if(
                    $user->type === 'pending_member' ||
                    $user->type !== 'board' ||
                    ($user->type === 'board' && auth()->user()->id !== $user->id) ||
                    in_array($user->type, ['member', 'pending_member'])
                )
                    <div
                        class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg border border-neutral-200/50 dark:border-neutral-700 p-8 transition-all duration-200 hover:shadow-xl">
                        <h3 class="text-xl font-bold mb-6 text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                            <flux:icon name="cog" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                            Actions
                        </h3>

                        <div class="flex flex-wrap gap-4">
                            @if($user->type === 'pending_member')
                                <form action="{{ route('board.users.approve', $user) }}" method="POST">
                                    @csrf
                                    <flux:button type="submit" icon="check" variant="primary"
                                                 class="shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer">
                                        Approve Membership
                                    </flux:button>
                                </form>
                            @endif

                            @if($user->type !== 'board')
                                <form action="{{ route('board.users.promote', $user) }}" method="POST">
                                    @csrf
                                    <flux:button type="submit" icon="user-plus"
                                                 class="shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer" variant="outline">
                                        Promote to Board
                                    </flux:button>
                                </form>
                            @endif

                            @if($user->type === 'board' && auth()->user()->id !== $user->id)
                                <form action="{{ route('board.users.demote', $user) }}" method="POST">
                                    @csrf
                                    <flux:button type="submit" icon="user-minus"
                                                 class="shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer" variant="outline">
                                        Demote to Member
                                    </flux:button>
                                </form>
                            @endif

                            @if($user->type === 'member' || $user->type === 'pending_member')
                                <form action="{{ route('board.users.toggle-lock', $user) }}" method="POST">
                                    @csrf
                                    <flux:button type="submit" icon="lock-closed"
                                                 class="shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer"
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
                                                 class="shadow-sm hover:shadow-md transition-all duration-200 cursor-pointer">
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
                        class="bg-white dark:bg-neutral-800 rounded-2xl shadow-lg border border-neutral-200/50 dark:border-neutral-700 p-8 transition-all duration-200 hover:shadow-xl">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                                    <flux:icon name="credit-card" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                                    Card Details
                                </h3>
                                <div
                                    class="bg-gradient-to-br from-indigo-600 to-indigo-800 text-white px-4 py-2 rounded-lg shadow-md">
                                    Balance: {{ number_format($user->card->balance, 2) }} €
                                </div>
                            </div>
                            <span
                                class="text-sm font-medium text-zinc-600 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-700/50 px-3 py-1 rounded-lg shadow-sm">Card #{{ $user->card->card_number }}</span>
                        </div>

                        @if($operations->count() > 0)
                            <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
                                <table class="w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                                    <thead class="bg-zinc-50 dark:bg-zinc-900/50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                                {{ __('Date') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                                {{ __('Type') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                                {{ __('Description') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                                {{ __('Amount') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-800">
                                        @foreach($operations as $operation)
                                            <tr class="{{ $loop->even ? 'bg-zinc-50 dark:bg-zinc-700/30' : 'bg-white dark:bg-zinc-800' }} hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-400">
                                                    {{ \Carbon\Carbon::parse($operation->date)->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($operation->type === 'credit')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-400">
                                                            {{ __('Credit') }}
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-400">
                                                            {{ __('Debit') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-400">
                                                    @if($operation->debit_type === 'membership_fee')
                                                        {{ __('Membership Fee Payment') }}
                                                    @elseif($operation->debit_type === 'order')
                                                        {{ __('Order Purchase') }} <span class="text-neutral-600 dark:text-neutral-400">#{{$operation->order_id}}</span>
                                                    @elseif($operation->credit_type === 'payment')
                                                        {{ __('Payment') }}
                                                        @if($operation->payment_type)
                                                            <span class="text-xs text-zinc-500 dark:text-zinc-500 ml-1">
                                                                via {{ $operation->payment_type }}
                                                            </span>
                                                        @endif
                                                        @if($operation->payment_reference)
                                                            <span class="text-xs text-zinc-500 dark:text-zinc-500 ml-1">
                                                                Ref: {{$operation->payment_reference}}
                                                            </span>
                                                        @endif
                                                    @elseif($operation->credit_type === 'order_cancellation')
                                                        {{ __('Order Cancellation') }} <span class="text-neutral-600 dark:text-neutral-400">#{{$operation->order_id}}</span>
                                                    @else
                                                        {{ __('Transaction') }}
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $operation->type === 'credit' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                                    {{ $operation->type === 'credit' ? '+' : '-' }}{{ number_format($operation->value, 2) }} €
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            @if($operations->hasPages())
                                <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800">
                                    {{ $operations->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12 bg-zinc-50 dark:bg-zinc-800/30 rounded-xl border border-zinc-200 dark:border-zinc-700">
                                <flux:icon name="credit-card" class="w-12 h-12 mx-auto text-zinc-300 dark:text-zinc-700 mb-4" />
                                <p class="text-zinc-600 dark:text-zinc-400">No transaction history available</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
