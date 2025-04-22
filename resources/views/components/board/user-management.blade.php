<x-layouts.app>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="flex aspect-video items-center justify-center rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div class="text-center">
                    <div class="text-3xl font-bold text-neutral-900 dark:text-white">{{ $totalUsers }}</div>
                    <div class="text-sm text-neutral-500 dark:text-neutral-400">Total Users</div>
                </div>
            </div>
            <div
                class="flex aspect-video items-center justify-center rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div class="text-center">
                    <div class="text-3xl font-bold text-neutral-900 dark:text-white">{{ $activeUsers }}</div>
                    <div class="text-sm text-neutral-500 dark:text-neutral-400">Active Users</div>
                </div>
            </div>
            <div
                class="flex aspect-video items-center justify-center rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
                <div class="text-center">
                    <div class="text-3xl font-bold text-neutral-900 dark:text-white">{{ $boardMembers }}</div>
                    <div class="text-sm text-neutral-500 dark:text-neutral-400">Board Members</div>
                </div>
            </div>
        </div>

        <livewire:user-table/>

    </div>
</x-layouts.app>
