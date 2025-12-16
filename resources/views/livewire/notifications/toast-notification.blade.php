<div class="fixed top-4 right-4 space-y-3 z-50">
    @forelse($notifications as $notification)
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-10"
            @click="show = false; $wire.removeNotification('{{ $notification['id'] }}')" x-init="setTimeout(() => { show = false;
                $wire.removeNotification('{{ $notification['id'] }}'); }, 5000)"
            class="min-w-80 p-4 rounded-lg shadow-lg cursor-pointer flex items-center gap-3 @if ($notification['type'] === 'success')
                bg-green-50 border border-green-200
                @elseif($notification['type'] === 'error')
                bg-red-50 border border-red-200
                @elseif($notification['type'] === 'warning')
                bg-yellow-50 border border-yellow-200
                @else
                bg-blue-50 border border-blue-200
                @endif">

            <!-- Icon -->
            <div>
                @if ($notification['type'] === 'success')
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                @elseif($notification['type'] === 'error')
                    <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                @elseif($notification['type'] === 'warning')
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                @else
                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                @endif
            </div>

            <!-- Message -->
            <div class="flex-1">
                <p
                    class="font-medium @if ($notification['type'] === 'success') text-green-900 @elseif($notification['type'] === 'error') text-red-900 @elseif($notification['type'] === 'warning') text-yellow-900 @else text-blue-900 @endif">
                    {{ $notification['message'] }}
                </p>
            </div>

            <!-- Close button -->
            <button @click.stop="show = false; $wire.removeNotification('{{ $notification['id'] }}')"
                class="@if ($notification['type'] === 'success') text-green-600 hover:text-green-800 @elseif($notification['type'] === 'error') text-red-600 hover:text-red-800 @elseif($notification['type'] === 'warning') text-yellow-600 hover:text-yellow-800 @else text-blue-600 hover:text-blue-800 @endif">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
    @empty
    @endforelse
</div>
