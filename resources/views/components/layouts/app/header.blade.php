<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header container class="border-b border-zinc-200 bg-white/95 backdrop-blur-sm dark:border-zinc-700/80 dark:bg-zinc-900/95 h-16 transition-all duration-200">
        <flux:sidebar.toggle class="lg:hidden hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg p-2 transition-colors" icon="bars-2" inset="left"/>

        <a href="{{ route('home') }}" class="ms-2 me-5 flex items-center space-x-2.5 rtl:space-x-reverse lg:ms-0 group" wire:navigate>
            <div class="flex items-center gap-2.5">
                <div class="flex aspect-square size-9 items-center justify-center rounded-xl bg-emerald-600 dark:bg-emerald-500 shadow-sm shadow-emerald-600/10 dark:shadow-emerald-500/10 transition-transform group-hover:scale-105">
                    <x-lucide-shopping-cart class="w-5 h-5 text-white" />
                </div>
                <span class="text-lg font-semibold text-zinc-900 dark:text-white transition-colors">Grocery Club</span>
            </div>
        </a>

        <flux:navbar class="-mb-px max-lg:hidden">
            @if(Auth::check())
                <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" class="transition-colors hover:text-emerald-600 dark:hover:text-emerald-400" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navbar.item>
            @endif

            <flux:navbar.item icon="shopping-bag" :href="route('catalog.index')" :current="request()->routeIs('catalog.index')" class="transition-colors hover:text-emerald-600 dark:hover:text-emerald-400" wire:navigate>
                {{ __('Catalog') }}
            </flux:navbar.item>
        </flux:navbar>

        <flux:spacer/>

        <flux:navbar class="me-1.5 space-x-3 rtl:space-x-reverse py-0!">
            <flux:tooltip :content="__('Toggle Theme')" position="bottom">
                <flux:navbar.item
                    class="h-10 max-lg:hidden [&>div>svg]:size-5 cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors"
                    x-data
                    @click="$flux.appearance = $flux.appearance === 'dark' ? 'light' : 'dark'"
                    :label="__('Toggle Theme')"
                >
                    <template x-if="$flux.appearance === 'dark'">
                        <flux:icon name="moon" class="w-5 h-5"/>
                    </template>
                    <template x-if="$flux.appearance === 'light'">
                        <flux:icon name="sun" class="w-5 h-5"/>
                    </template>
                    <template x-if="$flux.appearance === 'system'">
                        <flux:icon name="computer-desktop" class="w-5 h-5"/>
                    </template>
                </flux:navbar.item>
            </flux:tooltip>

            <flux:tooltip :content="__('Cart')" position="bottom">
                <flux:navbar.item
                    class="h-10 [&>div>svg]:size-5 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors"
                    href="{{ route('cart.index') }}"
                    :current="request()->routeIs('cart.index')"
                    :label="__('Cart')"
                >
                    <div class="relative">
                        <livewire:cart-counter/>
                    </div>
                </flux:navbar.item>
            </flux:tooltip>

            @if(Auth::check())
                <!-- Desktop User Menu -->
                <flux:dropdown position="bottom" align="end">
                    <flux:profile
                        class="cursor-pointer ring-2 ring-transparent hover:ring-emerald-500/20 transition-all"
                        :initials="auth()->user()->initials()"
                    />

                    <flux:menu class="w-64">
                        <flux:menu.radio.group>
                            <div class="p-0.5 text-sm font-normal">
                                <div class="flex items-center gap-3 px-2 py-2 text-start text-sm rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-lg">
                                        <span class="flex h-full w-full items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold text-zinc-900 dark:text-white">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator/>

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('settings.profile')" icon="cog" class="transition-colors hover:text-emerald-600 dark:hover:text-emerald-400" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator/>

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full transition-colors hover:text-red-600 dark:hover:text-red-400">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @else
                <div class="hidden lg:flex items-center gap-3">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white border border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600 rounded-lg transition-all">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 rounded-lg transition-colors shadow-sm shadow-emerald-600/10 dark:shadow-emerald-500/10 hover:shadow-md">
                        Register
                    </a>
                </div>
            @endif
        </flux:navbar>
    </flux:header>

    <!-- Mobile Menu -->
    <flux:sidebar stashable sticky class="lg:hidden border-e border-zinc-200 bg-white/95 backdrop-blur-sm dark:border-zinc-700/80 dark:bg-zinc-900/95">
        <div class="flex justify-between items-center p-4">
            <flux:sidebar.toggle class="lg:hidden hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg p-2 transition-colors" icon="x-mark"/>
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

        <div class="p-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 mb-6 group" wire:navigate>
                <div class="flex aspect-square size-9 items-center justify-center rounded-xl bg-emerald-600 dark:bg-emerald-500 shadow-sm shadow-emerald-600/10 dark:shadow-emerald-500/10 transition-transform group-hover:scale-105">
                    <x-lucide-shopping-cart class="w-5 h-5 text-white" />
                </div>
                <span class="text-lg font-semibold text-zinc-900 dark:text-white">Grocery Club</span>
            </a>

            <nav class="space-y-1.5">
                @if(Auth::check())
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-50 dark:hover:bg-zinc-800' }} rounded-lg transition-colors">
                        <x-lucide-layout-grid class="w-5 h-5" />
                        Dashboard
                    </a>
                @endif

                <a href="{{ route('catalog.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm font-medium {{ request()->routeIs('catalog.index') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-50 dark:hover:bg-zinc-800' }} rounded-lg transition-colors">
                    <x-lucide-shopping-bag class="w-5 h-5" />
                    Catalog
                </a>

                <a href="{{ route('cart.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm font-medium {{ request()->routeIs('cart.index') ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-50 dark:hover:bg-zinc-800' }} rounded-lg transition-colors">
                    <x-lucide-shopping-cart class="w-5 h-5" />
                    Cart
                    @if(Auth::check())
                        <livewire:cart-counter/>
                    @endif
                </a>
            </nav>

            @if(!Auth::check())
                <div class="mt-6 grid gap-3">
                    <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white border border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600 rounded-lg transition-all">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 rounded-lg transition-colors shadow-sm shadow-emerald-600/10 dark:shadow-emerald-500/10 hover:shadow-md">
                        Register
                    </a>
                </div>
            @endif
        </div>
    </flux:sidebar>

    {{ $slot }}

    @fluxScripts
</body>
</html>
