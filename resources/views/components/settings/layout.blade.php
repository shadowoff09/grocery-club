<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item
                :href="route('settings.profile')"
                wire:navigate
                icon="user"
                :current="request()->routeIs('settings.profile')"
            >
                {{ __('Profile') }}
            </flux:navlist.item>

            <flux:navlist.item
                :href="route('settings.security')"
                wire:navigate
                icon="lock-closed"
                :current="request()->routeIs('settings.security')"
            >
                {{ __('Security') }}
            </flux:navlist.item>

            <flux:navlist.item
                :href="route('settings.appearance')"
                wire:navigate
                icon="swatch"
                :current="request()->routeIs('settings.appearance')"
            >
                {{ __('Appearance') }}
            </flux:navlist.item>

            <flux:navlist.item
                :href="route('settings.danger-zone')"
                wire:navigate
                icon="exclamation-triangle"
                :current="request()->routeIs('settings.danger-zone')"
            >
                {{ __('Danger Zone') }}
            </flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <h1 class="text-xl font-bold">{{ $heading ?? '' }}</h1>
        <h2 class="mt-2 text-md text-zinc-600 dark:text-zinc-400">{{ $subheading ?? '' }}</h2>
        <flux:separator class="mt-2" />

        <div class="mt-6 w-full max-w-lg space-y-6">
            {{ $slot }}
        </div>
    </div>
</div>
