<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>

            <flux:navlist.item
                :href="route('board.business.settings.membership-fee')"
                wire:navigate
                icon="puzzle-piece"
                :current="request()->routeIs('board.business.settings.membership-fee')"
            >
                {{ __('Membership') }}
            </flux:navlist.item>

            <flux:navlist.item
                :href="route('board.business.settings.shipping-costs')"
                wire:navigate
                icon="globe-asia-australia"
                :current="request()->routeIs('board.business.settings.shipping-costs')"
            >
                {{ __('Shipping') }}
            </flux:navlist.item>
            <flux:navlist.item
                :href="route('board.business.settings.caching')"
                wire:navigate
                icon="cog-6-tooth"
                :current="request()->routeIs('board.business.settings.caching')"
            >
                {{ __('Caching') }}
            </flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden"/>

    <div class="flex-1 self-stretch max-md:pt-6">
        <h1 class="text-xl font-bold">{{ $heading ?? '' }}</h1>
        <h2 class="mt-2 text-md text-zinc-600 dark:text-zinc-400">{{ $subheading ?? '' }}</h2>
        <flux:separator class="mt-2"/>

        <div class="mt-6 w-full space-y-6">
            {{ $slot }}
        </div>
    </div>
</div>
