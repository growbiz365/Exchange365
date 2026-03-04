<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ExchangeHub') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            
            /* Responsive adjustments */
            @media (max-width: 640px) {
                body {
                    overflow-y: auto;
                }
            }
            
            /* Prevent zoom on iOS */
            @media (max-width: 768px) {
                input[type="text"],
                input[type="password"],
                input[type="email"],
                select,
                textarea {
                    font-size: 16px !important;
                }
            }
            
            html {
                scroll-behavior: smooth;
            }
            
            /* Animated gradient background */
            @keyframes gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            .animate-gradient {
                background-size: 200% 200%;
                animation: gradient 15s ease infinite;
            }
            
            /* Floating animation */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased overflow-hidden">
        <div class="h-screen flex bg-white">
            <!-- Left Column - Login Form -->
            <div class="flex-1 flex items-center justify-center p-6 lg:p-8">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>

            <!-- Right Column - Branding -->
            <div class="hidden lg:flex lg:flex-1 bg-gradient-to-br from-blue-600 via-blue-700 to-amber-600 animate-gradient relative overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-20 right-20 w-72 h-72 bg-amber-400 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-20 left-20 w-96 h-96 bg-blue-400 rounded-full blur-3xl"></div>
                </div>

                <!-- Floating Icons -->
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute top-1/4 left-1/4 animate-float opacity-20">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="absolute top-1/2 right-1/3 animate-float opacity-20" style="animation-delay: 2s;">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-center p-10 xl:p-12 text-white max-w-xl">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl mb-4 shadow-2xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                        <h1 class="text-4xl font-bold mb-2 leading-tight">
                            Exchange<span class="text-amber-300">365</span>
                        </h1>
                        <p class="text-lg text-blue-100 mb-1 font-medium">
                            Currency Exchange Platform
                        </p>
                        <p class="text-sm text-blue-200 opacity-90">
                            Secure, fast, and reliable currency exchange management system
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3 bg-white/10 backdrop-blur-sm rounded-xl p-3.5">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-9 h-9 bg-amber-500/30 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold mb-0.5">Bank-Level Security</h3>
                                <p class="text-xs text-blue-100">
                                    Military-grade encryption protecting every transaction
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 bg-white/10 backdrop-blur-sm rounded-xl p-3.5">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-9 h-9 bg-amber-500/30 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold mb-0.5">Real-Time Rates</h3>
                                <p class="text-xs text-blue-100">
                                    Live exchange rates updated every second
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 bg-white/10 backdrop-blur-sm rounded-xl p-3.5">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-9 h-9 bg-amber-500/30 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold mb-0.5">Advanced Analytics</h3>
                                <p class="text-xs text-blue-100">
                                    Comprehensive reports and profit tracking
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-white/20">
                        <p class="text-xs text-blue-200">
                            &copy; {{ date('Y') }} Exchange 365. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>