<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg sticky top-0 z-40" role="navigation" aria-label="Main navigation">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo and Brand -->
            <div class="flex items-center">
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" wire:navigate
                        class="flex items-center gap-3 hover:opacity-90 transition">
                        <i class="fas fa-hammer text-2xl"></i>
                        <span class="text-2xl font-bold">BangunanPro</span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex md:items-center md:gap-6 md:ms-10">
                    @auth
                        {{-- Dashboard - Admin & Manager only --}}
                        @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                            <a href="{{ route('dashboard') }}" wire:navigate
                                class="hover:text-blue-100 transition flex items-center gap-2 {{ request()->routeIs('dashboard') ? 'border-b-2 border-white pb-1' : '' }}">
                                <i class="fas fa-chart-line"></i> {{ __('Dashboard') }}
                            </a>
                        @endif

                        {{-- Inventory Dropdown - Admin & Manager only --}}
                        @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                            <x-nav-dropdown 
                                :label="__('Inventory')" 
                                icon="box"
                                :active="request()->routeIs('products.*', 'inventory.*')">
                                <x-nav-dropdown-link 
                                    :href="route('products.index')" 
                                    :active="request()->routeIs('products.*')"
                                    icon="box">
                                    {{ __('Products') }}
                                </x-nav-dropdown-link>
                                <x-nav-dropdown-link 
                                    :href="route('inventory.adjust')" 
                                    :active="request()->routeIs('inventory.adjust')"
                                    icon="sliders-h">
                                    {{ __('Stock Adjustment') }}
                                </x-nav-dropdown-link>
                                <x-nav-dropdown-link 
                                    :href="route('inventory.movements')" 
                                    :active="request()->routeIs('inventory.movements')"
                                    icon="history">
                                    {{ __('Movement History') }}
                                </x-nav-dropdown-link>
                            </x-nav-dropdown>
                        @endif

                        {{-- Relationships Dropdown - Admin & Manager only --}}
                        @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                            <x-nav-dropdown 
                                :label="__('Relationships')" 
                                icon="handshake"
                                :active="request()->routeIs('suppliers.*', 'customers.*')">
                                <x-nav-dropdown-link 
                                    :href="route('suppliers.index')" 
                                    :active="request()->routeIs('suppliers.*')"
                                    icon="truck">
                                    {{ __('Suppliers') }}
                                </x-nav-dropdown-link>
                                <x-nav-dropdown-link 
                                    :href="route('customers.index')" 
                                    :active="request()->routeIs('customers.*')"
                                    icon="users">
                                    {{ __('Customers') }}
                                </x-nav-dropdown-link>
                            </x-nav-dropdown>
                        @endif

                        {{-- Transactions Dropdown --}}
                        <x-nav-dropdown 
                            :label="__('Transactions')" 
                            icon="exchange-alt"
                            :active="request()->routeIs('sales.*', 'purchases.*')">
                            <x-nav-dropdown-link 
                                :href="route('sales.index')" 
                                :active="request()->routeIs('sales.*')"
                                icon="cash-register">
                                {{ __('Sales') }}
                            </x-nav-dropdown-link>
                            @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                                <x-nav-dropdown-link 
                                    :href="route('purchases.index')" 
                                    :active="request()->routeIs('purchases.*')"
                                    icon="shopping-cart">
                                    {{ __('Purchases') }}
                                </x-nav-dropdown-link>
                            @endif
                        </x-nav-dropdown>

                        {{-- Reports Dropdown - Admin & Manager only --}}
                        @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                            <x-nav-dropdown 
                                :label="__('Reports')" 
                                icon="chart-bar"
                                :active="request()->routeIs('reports.*', 'sales-report')">
                                <x-nav-dropdown-link 
                                    :href="route('sales-report')" 
                                    :active="request()->routeIs('sales-report')"
                                    icon="chart-line">
                                    {{ __('Sales Report') }}
                                </x-nav-dropdown-link>
                                <x-nav-dropdown-link 
                                    :href="route('reports.index')" 
                                    :active="request()->routeIs('reports.index')"
                                    icon="chart-bar">
                                    {{ __('All Reports') }}
                                </x-nav-dropdown-link>
                            </x-nav-dropdown>
                        @endif

                    @endauth
                </div>
            </div>

            <!-- Desktop User Menu -->
            <div class="hidden md:flex md:items-center md:gap-4">
                <!-- Language Switcher -->
                <livewire:language-switcher />
                
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 hover:text-blue-100 transition" aria-label="User menu" aria-expanded="false" aria-haspopup="true">
                                <i class="fas fa-user-circle text-2xl"></i>
                                <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- User Info -->
                            <div class="px-4 py-3 border-b border-gray-200">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600">{{ Auth::user()->email }}</p>
                                <p class="text-xs text-blue-600 font-semibold mt-1">
                                    @foreach (Auth::user()->roles as $role)
                                        {{ ucfirst($role->name) }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </p>
                            </div>

                            <!-- Profile Link -->
                            <x-dropdown-link :href="route('profile.edit')" wire:navigate>
                                <i class="fas fa-user-edit me-2"></i> {{ __('Edit Profile') }}
                            </x-dropdown-link>

                            <!-- Admin Only: User Management -->
                            @if (Auth::user()->roles()->where('name', 'admin')->exists())
                                <x-dropdown-link href="#users">
                                    <i class="fas fa-users me-2"></i> Manage Users
                                </x-dropdown-link>
                            @endif

                            <!-- Logout -->
                            <button wire:click="logout" class="w-full text-start border-t border-gray-200" aria-label="Log out from your account">
                                <x-dropdown-link>
                                    <i class="fas fa-sign-out-alt me-2 text-red-600"></i> <span class="text-red-600">{{ __('Logout') }}</span>
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                @endauth

                @guest
                    <a href="{{ route('login') }}"
                        class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition text-sm">
                        <i class="fas fa-sign-in-alt"></i> {{ __('Login') }}
                    </a>
                @endguest
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                @auth
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md hover:bg-blue-500 focus:outline-none transition"
                        aria-label="Toggle mobile menu"
                        aria-expanded="false"
                        :aria-expanded="open.toString()">
                        <svg class="h-6 w-6" :class="{ 'hidden': open, 'inline-flex': !open }" stroke="currentColor"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="h-6 w-6" :class="{ 'hidden': !open, 'inline-flex': open }" stroke="currentColor"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-sm font-semibold">
                        <i class="fas fa-sign-in-alt"></i> {{ __('Login') }}
                    </a>
                @endguest
            </div>
        </div>
    </div>

    <!-- Mobile Responsive Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden">
        @auth
            <div class="pt-2 pb-3 space-y-1">
                <!-- Dashboard -->
                @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                    <a href="{{ route('dashboard') }}" wire:navigate
                        class="block px-3 py-2 rounded-md hover:bg-blue-500 transition {{ request()->routeIs('dashboard') ? 'bg-blue-500' : '' }}">
                        <i class="fas fa-chart-line me-2"></i> {{ __('Dashboard') }}
                    </a>
                @endif

                <!-- Products -->
                @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                    <a href="{{ route('products.index') }}" wire:navigate
                        class="block px-3 py-2 rounded-md hover:bg-blue-500 transition {{ request()->routeIs('products.*') ? 'bg-blue-500' : '' }}">
                        <i class="fas fa-box me-2"></i> {{ __('Products') }}
                    </a>
                @endif

                <!-- Suppliers -->
                @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                    <a href="{{ route('suppliers.index') }}" wire:navigate
                        class="block px-3 py-2 rounded-md hover:bg-blue-500 transition {{ request()->routeIs('suppliers.*') ? 'bg-blue-500' : '' }}">
                        <i class="fas fa-truck me-2"></i> {{ __('Suppliers') }}
                    </a>
                @endif

                <!-- Purchases -->
                @if (Auth::user()->roles()->whereIn('name', ['admin', 'manager'])->exists())
                    <a href="{{ route('purchases.index') }}" wire:navigate
                        class="block px-3 py-2 rounded-md hover:bg-blue-500 transition {{ request()->routeIs('purchases.*') ? 'bg-blue-500' : '' }}">
                        <i class="fas fa-shopping-cart me-2"></i> {{ __('Purchases') }}
                    </a>
                @endif

                <!-- Sales -->
                <a href="{{ route('sales.index') }}" wire:navigate
                    class="block px-3 py-2 rounded-md hover:bg-blue-500 transition {{ request()->routeIs('sales.*') ? 'bg-blue-500' : '' }}">
                    <i class="fas fa-cash-register me-2"></i> {{ __('Sales') }}
                </a>
            </div>

            <!-- Mobile User Section -->
            <div class="pt-4 pb-3 border-t border-blue-500">
                <div class="px-4">
                    <div class="font-medium text-base">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-blue-100">{{ Auth::user()->email }}</div>
                    <div class="font-medium text-xs text-blue-100 mt-1">
                        @foreach (Auth::user()->roles as $role)
                            {{ ucfirst($role->name) }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" wire:navigate
                        class="block px-4 py-2 rounded hover:bg-blue-500 transition">
                        <i class="fas fa-user-edit me-2"></i> {{ __('Edit Profile') }}
                    </a>

                    @if (Auth::user()->roles()->where('name', 'admin')->exists())
                        <a href="#users" class="block px-4 py-2 rounded hover:bg-blue-500 transition">
                            <i class="fas fa-users me-2"></i> Manage Users
                        </a>
                    @endif

                    <button wire:click="logout"
                        class="w-full text-start px-4 py-2 rounded hover:bg-blue-500 transition text-red-200"
                        aria-label="Log out from your account">
                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                    </button>
                </div>
            </div>
        @endauth
    </div>
</nav>
