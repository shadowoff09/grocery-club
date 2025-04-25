<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @if(Auth::user()->isPendingMember())
            <flux:callout icon="clock" color="yellow" inline>
                <flux:callout.heading>Account Incomplete</flux:callout.heading>
                <flux:callout.text>
                    Your account is currently in a <strong>pending</strong> state.
                    <br>
                    To access all features, you need to pay your membership fee.
                </flux:callout.text>
                <x-slot name="actions" class="@md:h-full m-0!">
                    <a href="{{ route('membership.pending') }}">
                        <flux:button >Pay Fee -></flux:button>
                    </a>
                </x-slot>
            </flux:callout>
        @endif
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>
