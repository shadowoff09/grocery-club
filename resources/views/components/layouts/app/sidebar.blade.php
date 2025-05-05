<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
<flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <div class="flex justify-between items-center p-4 lg:hidden">
        <flux:sidebar.toggle class=" hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg p-2 transition-colors" icon="x-mark"/>

        <button
            class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
            x-data
            @click="$flux.appearance = $flux.appearance === 'dark' ? 'light' : 'dark'"
        >
            <template x-if="$flux.appearance === 'dark'">
                <flux:icon name="sun" class="w-5 h-5"/>
            </template>
            <template x-if="$flux.appearance === 'light'">
                <flux:icon name="moon" class="w-5 h-5"/>
            </template>
            <template x-if="$flux.appearance === 'system'">
                <flux:icon name="computer-desktop" class="w-5 h-5"/>
            </template>
        </button>
    </div>

    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 mb-6 group" wire:navigate>
        <div
            class="flex aspect-square size-9 items-center justify-center rounded-xl bg-emerald-600 dark:bg-emerald-500 shadow-sm shadow-emerald-600/10 dark:shadow-emerald-500/10 transition-transform group-hover:scale-105">
            <x-lucide-shopping-cart class="w-5 h-5 text-white"/>
        </div>
        <span class="text-lg font-semibold text-zinc-900 dark:text-white">Grocery Club</span>
    </a>

    <flux:navlist variant="outline">
        <flux:navlist.group :heading="__('Platform')" class="grid">
            <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
            <flux:navlist.item icon="shopping-bag" :href="route('catalog.index')" :current="request()->routeIs('catalog.index')" wire:navigate>{{ __('Catalog') }}</flux:navlist.item>
        </flux:navlist.group>
        @if(auth()->user()->isBoardMember())
            <flux:navlist.group :heading="__('Board')" class="grid">
                <flux:navlist.item icon="users" :href="route('board.users')" :current="request()->routeIs('board.users')" wire:navigate>{{ __('User Management') }}</flux:navlist.item>
                <flux:navlist.item
                    icon="cog-6-tooth"
                    :href="route('board.business.settings.membership-fee')"
                    :current="request()->routeIs('board.business.settings.membership-fee') || request()->routeIs('board.business.settings.shipping-costs')"
                    wire:navigate
                >
                    {{ __('Business Settings') }}
                </flux:navlist.item>
            </flux:navlist.group>
        @endif
    </flux:navlist>

    <div class="mt-auto hidden lg:flex items-center gap-2">
        <flux:dropdown position="bottom" align="start">
            @if(auth()->user()->photo)
                <flux:profile :name="auth()->user()->name" icon-trailing="chevron-down">
                    <x-slot:avatar>
                        <img
                            src="{{ asset('storage/users/' . auth()->user()->photo) }}"
                            alt="{{ auth()->user()->name }}"
                            class="size-8 aspect-square object-cover rounded-lg"
                        />
                    </x-slot:avatar>
                </flux:profile>

            @else
                <flux:profile
                    :initials="auth()->user()->initials()"
                    :name="auth()->user()->name"
                    icon-trailing="chevron-down"
                />
            @endif

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                     @if(auth()->user()->photo)
                                        <img
                                            src="{{ asset('storage/users/' . auth()->user()->photo) }}"
                                            alt="{{ auth()->user()->name }}"
                                            class="h-full w-full object-cover"
                                        />
                                    @else
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                    {{ auth()->user()->initials() }}
                                </span>
                                    @endif
                                </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                <span class="truncate text-xs text-zinc-500">
                                    @switch(auth()->user()->type)
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
                                    </span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                @if(auth()->user()->type !== 'employee')
                    <flux:menu.separator/>

                    <flux:menu.radio.group>
                        <flux:menu.item
                            icon="banknotes"
                            class="flex items-center justify-start space-x-2"
                        >
                            <span>{{ __('Balance') }}</span>
                            <span class="text-xs text-zinc-500">
                                {{ number_format(optional(auth()->user()->card)->balance ?? 0, 2) }} €
                            </span>
                        </flux:menu.item>
                    </flux:menu.radio.group>
                @endif

                <flux:menu.separator/>

                <flux:menu.radio.group>
                    @if(auth()->user()->type === 'employee')
                        <flux:menu.item :href="route('settings.security')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    @else
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    @endif

                </flux:menu.radio.group>

                <flux:menu.separator/>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
        <button
            class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
            x-data
            @click="$flux.appearance = $flux.appearance === 'dark' ? 'light' : 'dark'"
        >
            <template x-if="$flux.appearance === 'dark'">
                <flux:icon name="sun" class="w-5 h-5"/>
            </template>
            <template x-if="$flux.appearance === 'light'">
                <flux:icon name="moon" class="w-5 h-5"/>
            </template>
            <template x-if="$flux.appearance === 'system'">
                <flux:icon name="computer-desktop" class="w-5 h-5"/>
            </template>
        </button>
    </div>
</flux:sidebar>

<!-- Mobile User Menu -->
<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

    <flux:spacer/>

    <flux:dropdown position="bottom" align="start">
        @if(auth()->user()->photo)
            <flux:profile :name="auth()->user()->name" icon-trailing="chevron-down">
                <x-slot:avatar>
                    <img
                        src="{{ asset('storage/users/' . auth()->user()->photo) }}"
                        alt="{{ auth()->user()->name }}"
                        class="size-8 aspect-square object-cover rounded-lg"
                    />
                </x-slot:avatar>
            </flux:profile>

        @else
            <flux:profile
                :initials="auth()->user()->initials()"
                :name="auth()->user()->name"
                icon-trailing="chevron-down"
            />
        @endif

        <flux:menu class="w-[220px]">
            <flux:menu.radio.group>
                <div class="p-0 text-sm font-normal">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                     @if(auth()->user()->photo)
                                        <img
                                            src="{{ asset('storage/users/' . auth()->user()->photo) }}"
                                            alt="{{ auth()->user()->name }}"
                                            class="h-full w-full object-cover"
                                        />
                                    @else
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                    {{ auth()->user()->initials() }}
                                </span>
                                    @endif
                                </span>

                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                            <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            <span class="truncate text-xs text-zinc-500">
                                    @switch(auth()->user()->type)
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
                                    </span>
                        </div>
                    </div>
                </div>
            </flux:menu.radio.group>

            @if(auth()->user()->type !== 'employee')
                <flux:menu.separator/>

                <flux:menu.radio.group>
                    <flux:menu.item
                        icon="banknotes"
                        class="flex items-center justify-start space-x-2"
                    >
                        <span>{{ __('Balance') }}</span>
                        <span class="text-xs text-zinc-500">
                                    {{ number_format(optional(auth()->user()->card)->balance ?? 0, 2) }} €
                                </span>
                    </flux:menu.item>
                </flux:menu.radio.group>
            @endif

            <flux:menu.separator/>

            <flux:menu.radio.group>
                @if(auth()->user()->type === 'employee')
                    <flux:menu.item :href="route('settings.security')" icon="cog"
                                    wire:navigate>{{ __('Settings') }}</flux:menu.item>
                @else
                    <flux:menu.item :href="route('settings.profile')" icon="cog"
                                    wire:navigate>{{ __('Settings') }}</flux:menu.item>
                @endif

            </flux:menu.radio.group>

            <flux:menu.separator/>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                class="w-full cursor-pointer">
                    {{ __('Log Out') }}
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
</flux:header>

{{ $slot }}

@fluxScripts
<x-toaster-hub />
</body>
</html>
