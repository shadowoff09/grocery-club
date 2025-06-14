<div class="min-h-screen">
    <div class="container mx-auto px-2 py-2">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-8">
            <a href="{{ route('board.catalog.categories') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Categories</a>
            <x-lucide-chevron-right class="w-4 h-4" />
            <span class="text-gray-900 dark:text-white font-medium">New Category</span>
        </div>

        <form wire:submit="createCategory">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Category Image -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Image Card -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Category Image</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload a category image</p>
                                </div>
                            </div>

                            <div class="relative group">
                                <div class="aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-zinc-800 border-2 border-dashed border-gray-200 dark:border-zinc-700">
                                    @if($image)
                                        <img src="{{ $image->temporaryUrl() }}"
                                             alt="Category preview"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-600">
                                            <x-lucide-image class="w-12 h-12 mb-2" />
                                            <span class="text-sm">No image available</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-4">
                                    <flux:input type="file" 
                                            wire:model.live="image" 
                                            id="image" 
                                            accept="image/*"
                                            :label="__('Choose Image')" />
                                    <div class="text-xs text-gray-500 mt-1">{{ __('JPG, PNG or GIF. 2MB max.') }}</div>
                                    @error('image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Category Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Category name</p>
                                </div>
                            </div>

                            <div>
                                <flux:label>Name</flux:label>
                                <flux:input type="text" wire:model.live="name" required class="mt-1" placeholder="Enter category name" />
                                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary" icon="plus" class="cursor-pointer">
                            {{ __('Create Category') }}
                        </flux:button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div> 