<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BangunanPro') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        @php
            // Check if this is a documentation page (full-width) or auth page (centered card)
            $isDocPage = in_array(request()->route()?->getName(), [
                'legal.privacy', 
                'legal.terms', 
                'support.faq'
            ]);
        @endphp

        @if($isDocPage)
            {{-- Full-width layout for documentation pages --}}
            {{ $slot }}
        @else
            {{-- Centered card layout for auth pages --}}
            <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-gray-50 flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
                <!-- Logo Section -->
                <div class="mb-6">
                    <a href="/" class="flex items-center gap-3">
                        <div class="bg-gradient-to-br from-primary-500 to-primary-700 p-3 rounded-xl shadow-lg">
                            <i class="fas fa-hammer text-2xl text-white"></i>
                        </div>
                        <span class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">
                            BangunanPro
                        </span>
                    </a>
                </div>

                <!-- Auth Card -->
                <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-gray-600 text-sm">&copy; 2025 BangunanPro. All rights reserved.</p>
                    <div class="mt-2 flex items-center justify-center gap-4 text-xs text-gray-500">
                        <a href="{{ route('legal.privacy') }}" class="hover:text-blue-600 transition">Privacy</a>
                        <span>•</span>
                        <a href="{{ route('legal.terms') }}" class="hover:text-blue-600 transition">Terms</a>
                        <span>•</span>
                        <a href="{{ route('support.faq') }}" class="hover:text-blue-600 transition">FAQ</a>
                    </div>
                </div>
            </div>
        @endif
    </body>
</html>
