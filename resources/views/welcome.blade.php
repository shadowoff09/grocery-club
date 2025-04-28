<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Grocery Club') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gradient-to-br from-emerald-50 to-teal-100 min-h-screen">
<div class="relative min-h-screen flex flex-col items-center">
    <!-- Navigation -->
    <nav class="w-full bg-white shadow-sm py-4">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center">
                <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="8" cy="21" r="1"/>
                        <circle cx="19" cy="21" r="1"/>
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                    </svg>
                </div>
                <div class="ms-2 grid flex-1 text-start">
                    <span class="truncate leading-none font-semibold text-lg text-emerald-800">Grocery Club</span>
                </div>
            </div>
            <div class="flex space-x-4">
                @if (Route::has('login'))
                    <div>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-emerald-700 hover:text-emerald-800 font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-emerald-700 hover:text-emerald-800 font-medium">Log in</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ms-4 text-emerald-700 hover:text-emerald-800 font-medium">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20 flex flex-col lg:flex-row items-center gap-10">
        <!-- Left content -->
        <div class="flex-1">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-emerald-900 mb-4">
                Fresh Groceries <br>
                <span class="text-emerald-600">Delivered to Your Door</span>
            </h1>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl">
                Join Grocery Club today and discover the convenience of having quality groceries
                delivered right to your doorstep. Save time, eat well, and enjoy exclusive member benefits.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ Route::has('register') ? route('register') : '#' }}" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition duration-300">
                    Get Started
                </a>
                <a href="#features" class="px-6 py-3 bg-white hover:bg-gray-100 text-emerald-700 font-medium rounded-lg border border-emerald-200 transition duration-300">
                    Learn More
                </a>
            </div>
        </div>

        <!-- Right image -->
        <div class="flex-1 mt-8 lg:mt-0">
            <div class="relative rounded-2xl overflow-hidden shadow-xl">
                <img src="https://images.unsplash.com/photo-1543168256-418811576931?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80"
                     alt="Fresh groceries" class="w-full aspect-[4/3] object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-emerald-900/70 to-transparent p-6">
                    <div class="text-white text-lg font-medium">Fresh, Local & Sustainable</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="w-full bg-white py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-emerald-800 mb-12">Why Choose Grocery Club?</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-emerald-50 p-6 rounded-xl">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-emerald-800 mb-2">Fast Delivery</h3>
                    <p class="text-gray-600">Get your groceries delivered within hours of placing your order.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-emerald-50 p-6 rounded-xl">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-emerald-800 mb-2">Quality Products</h3>
                    <p class="text-gray-600">We source only the freshest and highest quality products for our members.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-emerald-50 p-6 rounded-xl">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-emerald-800 mb-2">Exclusive Savings</h3>
                    <p class="text-gray-600">Club members enjoy special prices, discounts, and seasonal offers.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="w-full bg-emerald-700 py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-6">Ready to join Grocery Club?</h2>
            <p class="text-emerald-100 mb-8 max-w-2xl mx-auto">
                Sign up today and get your first delivery free!
            </p>
            <a href="{{ Route::has('register') ? route('register') : '#' }}"
               class="px-8 py-4 bg-white hover:bg-emerald-50 text-emerald-700 font-medium rounded-lg transition duration-300 inline-block">
                Join Now
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="21" r="1"/>
                                <circle cx="19" cy="21" r="1"/>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                            </svg>
                        </div>
                        <div class="ms-2">
                            <span class="font-semibold text-lg">Grocery Club</span>
                        </div>
                    </div>
                    <p class="text-gray-400">Fresh groceries delivered to your door.</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">How It Works</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">FAQs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            support@groceryclub.com
                        </li>
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            (555) 123-4567
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Grocery Club. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
