<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
<flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>

    <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
        <x-app-logo/>
    </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>
                @if(auth()->user()->isBoardMember())
                    <flux:navlist.group :heading="__('Board')" class="grid">
                        <flux:navlist.item icon="users" :href="route('board.users')" :current="request()->routeIs('board.users')" wire:navigate>{{ __('User Management') }}</flux:navlist.item>
                        <flux:navlist.item
                            icon="cog-6-tooth"
                            :href="route('board.business.settings.membership-fee')"
                            :current="request()->routeIs('board.business.settings.membership-fee') | request()->routeIs('board.business.settings.shipping-costs')"
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
                                {{ number_format(auth()->user()->card->balance, 2) }} €
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
        <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle"
                     aria-label="Toggle dark mode"/>
    </div>
</flux:sidebar>

<!-- Mobile User Menu -->
<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>

    <flux:spacer/>

    <flux:dropdown position="top" align="end">
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
                icon-trailing="chevron-down"
            />
        @endif

        <flux:menu>
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
                        </div>
                    </div>
                </div>
            </flux:menu.radio.group>

            <flux:menu.separator/>

            <flux:menu.radio.group>
                <flux:menu.item :href="route('settings.profile')" icon="cog"
                                wire:navigate>{{ __('Settings') }}</flux:menu.item>
            </flux:menu.radio.group>

            <flux:menu.separator/>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
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
