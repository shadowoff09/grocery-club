<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body
    class="min-h-screen bg-white font-sans antialiased dark:bg-gradient-to-b dark:from-neutral-950 dark:to-neutral-900 text-neutral-900 dark:text-neutral-100">
<main class="flex min-h-svh flex-col items-center justify-center px-4 py-8 md:py-12">
    <div
        class="w-full max-w-md space-y-8 rounded-2xl bg-background/60 backdrop-blur p-6 shadow-xl md:p-10 border border-white/10">
        <!-- Branding -->
        <a href="{{ route('home') }}"
           class="flex flex-col items-center gap-2 font-semibold text-lg hover:opacity-80 transition" wire:navigate>
            <div
                class="flex aspect-square size-9 items-center justify-center rounded-xl bg-emerald-600 dark:bg-emerald-500 shadow-sm shadow-emerald-600/10 dark:shadow-emerald-500/10 transition-transform group-hover:scale-105">
                <x-lucide-shopping-cart class="w-5 h-5 text-white"/>
            </div>
            <span class="text-sm md:text-lg font-semibold text-zinc-900 dark:text-white transition-colors">
                Grocery Club
            </span>
        </a>

        <!-- Slot content (e.g. forms, login, etc) -->
        <div class="space-y-6">
            {{ $slot }}
        </div>
    </div>
</main>

@fluxScripts
</body>
</html>
