<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js','resources/js/script.js'])
    <script src="http://localhost:5173/resources/js/script.js"></script>
    <script src="http://localhost:5173/resources/js/bootstrap.js"></script>
    

    <!-- Add SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <style>
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .slide-in-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.8s ease-out;
        }
        .slide-in-left.visible {
            opacity: 1;
            transform: translateX(0);
        }
        
        .slide-in-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.8s ease-out;
        }
        .slide-in-right.visible {
            opacity: 1;
            transform: translateX(0);
        }

        .navbar-hidden {
            transform: translateY(-100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .navbar-visible {
            transform: translateY(0);
            transition: transform 0.3s ease-in-out;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Fixed Navigation - Initially Hidden -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 navbar-hidden">
        @include('layouts.navigation')
    </nav>

    <!-- Hero Section -->
    <section class="min-h-screen hero-gradient flex items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8">
            <div class="fade-in">
                <h1 class="text-5xl md:text-7xl font-bold text-white mb-6">
                    Welcome to <span class="text-red-500">Laravel</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-3xl mx-auto">
                    Discover amazing auctions and bid on your favorite items in our secure platform
                </p>
                <div class="space-x-4">
                    @auth
                        <a href="{{ route('auctions.index') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105">
                            Explore Auctions
                        </a>
                        <a href="{{ route('dashboard') }}" class="inline-block border-2 border-white text-white hover:bg-white hover:text-gray-900 font-semibold py-3 px-8 rounded-lg transition duration-300">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105">
                            Get Started
                        </a>
                        <a href="{{ route('login') }}" class="inline-block border-2 border-white text-white hover:bg-white hover:text-gray-900 font-semibold py-3 px-8 rounded-lg transition duration-300">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Why Choose Our Platform?
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Experience the best auction platform with secure bidding and amazing deals
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center slide-in-left">
                    <div class="bg-red-100 dark:bg-red-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Secure Bidding</h3>
                    <p class="text-gray-600 dark:text-gray-400">Your transactions are protected with industry-standard security protocols</p>
                </div>
                
                <div class="text-center fade-in">
                    <div class="bg-red-100 dark:bg-red-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Real-time Updates</h3>
                    <p class="text-gray-600 dark:text-gray-400">Get instant notifications when someone outbids you</p>
                </div>
                
                <div class="text-center slide-in-right">
                    <div class="bg-red-100 dark:bg-red-900 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Premium Items</h3>
                    <p class="text-gray-600 dark:text-gray-400">Discover unique and valuable items from trusted sellers</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    How It Works
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400">
                    Simple steps to start bidding
                </p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center slide-in-left">
                    <div class="bg-red-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Register</h3>
                    <p class="text-gray-600 dark:text-gray-400">Create your account in minutes</p>
                </div>
                
                <div class="text-center fade-in">
                    <div class="bg-red-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Browse</h3>
                    <p class="text-gray-600 dark:text-gray-400">Explore amazing auction items</p>
                </div>
                
                <div class="text-center fade-in">
                    <div class="bg-red-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Bid</h3>
                    <p class="text-gray-600 dark:text-gray-400">Place your bids on favorite items</p>
                </div>
                
                <div class="text-center slide-in-right">
                    <div class="bg-red-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">4</div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Win</h3>
                    <p class="text-gray-600 dark:text-gray-400">Celebrate your winning bids!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    {{-- <section class="py-20 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div class="fade-in">
                    <div class="text-4xl font-bold text-red-600 mb-2">1000+</div>
                    <div class="text-gray-600 dark:text-gray-400">Active Auctions</div>
                </div>
                <div class="fade-in">
                    <div class="text-4xl font-bold text-red-600 mb-2">5000+</div>
                    <div class="text-gray-600 dark:text-gray-400">Happy Users</div>
                </div>
                <div class="fade-in">
                    <div class="text-4xl font-bold text-red-600 mb-2">99.9%</div>
                    <div class="text-gray-600 dark:text-gray-400">Success Rate</div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- CTA Section -->
    <section class="py-20 bg-red-600">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <div class="fade-in">
                <h2 class="text-4xl font-bold text-white mb-4">
                    Ready to Start Bidding?
                </h2>
                <p class="text-xl text-red-100 mb-8">
                    Join thousands of users who trust our platform for their auction needs
                </p>
                @auth
                    <a href="{{ route('auctions.index') }}" class="inline-block bg-white text-red-600 hover:bg-gray-100 font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105">
                        Browse Auctions Now
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-block bg-white text-red-600 hover:bg-gray-100 font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105">
                        Sign Up Today
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="fade-in">
                    <x-application-logo class="h-12 w-auto mx-auto mb-4 fill-current text-red-500" />
                    <p class="text-gray-400 mb-4">
                        Your trusted auction platform for amazing deals
                    </p>
                    <div class="text-sm text-gray-500">
                        © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Navbar show/hide on scroll
        let lastScrollTop = 0;
        const navbar = document.getElementById('navbar');
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Show navbar after first section (around 100vh)
            if (scrollTop > window.innerHeight * 0.8) {
                navbar.classList.remove('navbar-hidden');
                navbar.classList.add('navbar-visible');
            } else {
                navbar.classList.remove('navbar-visible');
                navbar.classList.add('navbar-hidden');
            }
            
            lastScrollTop = scrollTop;
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all animation elements
        document.addEventListener('DOMContentLoaded', function() {
            const animationElements = document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right');
            animationElements.forEach(el => observer.observe(el));
        });

        // Toast notifications
        @if(session('toast_success'))
            sessionStorage.setItem('toast_success', "{{ session('toast_success') }}");
        @endif

        @if(session('toast_error'))
            sessionStorage.setItem('toast_error', "{{ session('toast_error') }}");
        @endif
    </script>
</body>
</html>