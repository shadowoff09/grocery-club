<x-layouts.app.header :title="__('Landing Page')">
    <div class="min-h-screen">
        <!-- Hero Section -->
        <div class="relative py-16 md:py-24 bg-gradient-to-b from-emerald-50/50 to-white dark:from-emerald-950/10 dark:to-zinc-800 overflow-hidden">
            <div class="container mx-auto px-4 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                    <div class="space-y-8 md:pr-8">
                        <div class="inline-flex items-center rounded-full px-4 py-1 text-sm bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 font-medium">
                            <x-lucide-sparkles class="w-4 h-4 mr-2" />
                            Premium Quality Groceries
                        </div>
                        <div class="space-y-4">
                            <h1 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white leading-tight">
                                Fresh Food <br>
                                <span class="text-emerald-600 dark:text-emerald-400">Delivered Daily</span>
                            </h1>
                            <p class="text-lg text-zinc-600 dark:text-zinc-300 max-w-lg">
                                Join Grocery Club for premium products, member discounts, and convenient delivery right to your doorstep.
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                                <x-lucide-user-plus class="w-5 h-5 mr-2" />
                                Join Grocery Club
                            </a>
                            <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white border border-zinc-200 dark:border-zinc-700 font-medium rounded-lg transition-colors">
                                <x-lucide-shopping-bag class="w-5 h-5 mr-2" />
                                Browse Products
                            </a>
                        </div>
                    </div>
                    <div class="relative md:h-[500px] flex items-center justify-center">
                        <!-- Decorative elements -->
                        <div class="absolute inset-0 md:-inset-x-12 md:-inset-y-8">
                            <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100 to-emerald-50 dark:from-emerald-900/20 dark:to-emerald-800/10 rounded-[3rem] rotate-3"></div>
                            <div class="absolute inset-0 bg-gradient-to-bl from-emerald-50 to-white dark:from-emerald-800/10 dark:to-transparent rounded-[3rem] -rotate-3 opacity-70"></div>
                        </div>
                        
                        <!-- Image container -->
                        <div class="relative w-full max-w-lg mx-auto">
                            <div class="absolute inset-0 bg-gradient-to-tr from-emerald-500/10 to-emerald-100/20 dark:from-emerald-500/5 dark:to-emerald-900/10 rounded-[2rem] blur-2xl"></div>
                            <div class="relative rounded-[2rem] overflow-hidden shadow-2xl bg-white dark:bg-zinc-900 aspect-[4/3]">
                                <img 
                                    src="https://images.unsplash.com/photo-1550989460-0adf9ea622e2?q=80&w=800&auto=format&fit=crop" 
                                    alt="Fresh groceries" 
                                    class="w-full h-full object-cover"
                                >
                                <!-- Overlay gradient -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-transparent dark:from-black/20"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="py-8 bg-white dark:bg-zinc-800 border-y border-zinc-200 dark:border-zinc-700">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($stats['customers']) }}+</div>
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">Happy Customers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($stats['products']) }}+</div>
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">Products</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['categories'] }}</div>
                        <div class="text-sm text-zinc-600 dark:text-zinc-400">Categories</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-16 bg-zinc-50 dark:bg-zinc-900">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-zinc-900 dark:text-white mb-4">Why Choose Grocery Club</h2>
                    <p class="text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
                        Experience the best in grocery shopping with our premium service
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center mr-4">
                                <x-lucide-sparkles class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Premium Quality</h3>
                        </div>
                        <p class="text-zinc-600 dark:text-zinc-400">
                            Fresh produce and high-quality grocery items guaranteed, sourced from trusted suppliers.
                        </p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center mr-4">
                                <x-lucide-percent class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Member Discounts</h3>
                        </div>
                        <p class="text-zinc-600 dark:text-zinc-400">
                            Exclusive pricing and weekly special promotions for our valued members.
                        </p>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center mr-4">
                                <x-lucide-truck class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Fast Delivery</h3>
                        </div>
                        <p class="text-zinc-600 dark:text-zinc-400">
                            Same-day delivery available, with real-time order tracking.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Categories Preview -->
        <div class="py-16 bg-white dark:bg-zinc-800">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center mb-10">
                    <div>
                        <h2 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Shop by Category</h2>
                        <p class="text-zinc-600 dark:text-zinc-400">Explore our wide selection of products</p>
                    </div>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center text-emerald-600 dark:text-emerald-400 font-medium hover:text-emerald-700 dark:hover:text-emerald-300">
                        View All Categories
                        <x-lucide-arrow-right class="w-4 h-4 ml-1" />
                    </a>
                </div>
                
                <!-- Categories Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($categories as $category)
                        <a href="{{ route('catalog.index', ['category' => $category->id]) }}" class="group">
                            <div class="bg-zinc-50 dark:bg-zinc-900 p-6 rounded-xl text-center hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all duration-300">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 mb-4 group-hover:scale-110 transition-transform">
                                    @if($category->image)
                                        <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" class="w-7 h-7 object-cover">
                                    @else
                                        <x-lucide-grid class="w-7 h-7 text-emerald-600 dark:text-emerald-400" />
                                    @endif
                                </div>
                                <span class="block font-medium text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400">{{ $category->name }}</span>
                                <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ $category->products_count }} products</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Featured Products -->
        <div class="py-16 bg-white dark:bg-zinc-800">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center mb-10">
                    <div>
                        <h2 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Featured Products</h2>
                        <p class="text-zinc-600 dark:text-zinc-400">Fresh picks from our latest collection</p>
                    </div>
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center text-emerald-600 dark:text-emerald-400 font-medium hover:text-emerald-700 dark:hover:text-emerald-300">
                        View All Products
                        <x-lucide-arrow-right class="w-4 h-4 ml-1" />
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featuredProducts as $product)
                        <div class="group bg-white dark:bg-zinc-900 rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-700 hover:shadow-lg transition-all duration-300">
                            <div class="aspect-square bg-zinc-50 dark:bg-zinc-800 relative overflow-hidden">
                                @if($product->photo)
                                    <img 
                                        src="{{ asset('storage/products/' . $product->photo) }}" 
                                        alt="{{ $product->name }}" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-zinc-400 dark:text-zinc-600">
                                        <x-lucide-shopping-bag class="w-12 h-12" />
                                    </div>
                                @endif
                                
                                <!-- Product badges -->
                                @if($product->stock <= 5 && $product->stock > 0)
                                    <div class="absolute top-2 right-2">
                                        <span class="px-2 py-1 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 rounded-full">
                                            Only {{ $product->stock }} left
                                        </span>
                                    </div>
                                @elseif($product->stock === 0)
                                    <div class="absolute top-2 right-2">
                                        <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 rounded-full">
                                            Out of stock
                                        </span>
                                    </div>
                                @endif

                                @if($product->discount > 0)
                                    <div class="absolute top-2 left-2">
                                        <span class="px-2 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 rounded-full">
                                            {{ $product->discount }}% OFF
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4">
                                <div class="mb-1">
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ $product->category->name }}
                                    </span>
                                </div>
                                <h3 class="font-medium text-zinc-900 dark:text-white mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                    {{ $product->name }}
                                </h3>
                                <div class="flex items-center justify-between">
                                    <div class="text-lg font-bold text-zinc-900 dark:text-white">
                                        €{{ number_format($product->price, 2) }}
                                    </div>
                                    <a 
                                        href="{{ route('catalog.index', ['category' => $product->category_id]) }}" 
                                        class="inline-flex items-center justify-center p-2 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900 transition-colors"
                                    >
                                        <x-lucide-shopping-cart class="w-5 h-5" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- CTA Section -->
        <div class="py-16 bg-emerald-600 dark:bg-emerald-700">
            <div class="container mx-auto px-4 text-center">
                <div class="max-w-2xl mx-auto">
                    <h2 class="text-3xl font-bold text-white mb-4">Start Shopping Smarter Today</h2>
                    <p class="text-emerald-100 mb-8">
                        Join thousands of satisfied customers who trust Grocery Club for their daily essentials.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 bg-white hover:bg-zinc-100 text-emerald-600 font-medium rounded-lg transition-colors">
                            <x-lucide-user-plus class="w-5 h-5 mr-2" />
                            Create Account
                        </a>
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-emerald-700 hover:bg-emerald-800 text-white border border-emerald-500 font-medium rounded-lg transition-colors">
                            <x-lucide-shopping-bag class="w-5 h-5 mr-2" />
                            Browse Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app.header>
        