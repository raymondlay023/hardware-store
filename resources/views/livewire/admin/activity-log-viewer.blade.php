<div>
    <!-- Page Header -->
    <x-page-header 
        title="Activity Logs" 
        description="View and filter system activity history"
        icon="fa-history">
        <x-slot name="actions">
            <button wire:click="exportCsv"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-download"></i>
                Export CSV
            </button>
        </x-slot>
    </x-page-header>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Search logs..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <!-- User Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                <select wire:model.live="filterUser"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Users</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Model Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                <select wire:model.live="filterModel"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Models</option>
                    @foreach ($modelTypes as $model)
                        <option value="{{ $model }}">{{ $model }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                <select wire:model.live="filterAction"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Actions</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" class="capitalize">{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Clear Filters -->
            <div class="flex items-end">
                <button wire:click="clearFilters"
                    class="w-full px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition text-sm font-semibold">
                    <i class="fas fa-times mr-1"></i> Clear
                </button>
            </div>
        </div>

        <!-- Date Range -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" wire:model.live="dateFrom"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" wire:model.live="dateTo"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
    </div>

    <!-- Results Count -->
    <div class="mb-4 flex items-center justify-between">
        <p class="text-sm text-gray-600">
            Showing <span class="font-semibold">{{ $logs->firstItem() ?? 0 }}</span> to 
            <span class="font-semibold">{{ $logs->lastItem() ?? 0 }}</span> of 
            <span class="font-semibold">{{ $logs->total() }}</span> entries
        </p>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Model</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IP Address</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ $log->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $log->user ? $log->user->name : 'System' }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $actionColors = [
                                        'created' => 'green',
                                        'updated' => 'blue',
                                        'deleted' => 'red',
                                    ];
                                    $color = $actionColors[$log->action] ?? 'gray';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-{{ $color }}-100 text-{{ $color }}-800 capitalize">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">{{ class_basename($log->model_type) }}</div>
                                <div class="text-xs text-gray-500">#{{ $log->model_id }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $log->ip_address ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($log->changes)
                                    <button wire:click="toggleExpand({{ $log->id }})"
                                        class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                                        @if ($expandedLog === $log->id)
                                            <i class="fas fa-chevron-up mr-1"></i> Hide
                                        @else
                                            <i class="fas fa-chevron-down mr-1"></i> View
                                        @endif
                                    </button>
                                @else
                                    <span class="text-gray-400 text-sm">No details</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Expanded Changes Row -->
                        @if ($expandedLog === $log->id && $log->changes)
                            <tr class="bg-gray-50">
                                <td colspan="6" class="px-4 py-4">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                        @if (isset($log->changes['old']))
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-700 mb-2">
                                                    <i class="fas fa-minus-circle text-red-500 mr-1"></i>
                                                    {{ $log->action === 'deleted' ? 'Deleted Data' : 'Before' }}
                                                </h4>
                                                <pre class="bg-white border border-gray-200 rounded p-3 text-xs overflow-x-auto max-h-48">{{ json_encode($log->changes['old'], JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        @endif
                                        @if (isset($log->changes['new']))
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-700 mb-2">
                                                    <i class="fas fa-plus-circle text-green-500 mr-1"></i>
                                                    {{ $log->action === 'created' ? 'Created Data' : 'After' }}
                                                </h4>
                                                <pre class="bg-white border border-gray-200 rounded p-3 text-xs overflow-x-auto max-h-48">{{ json_encode($log->changes['new'], JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                                <p class="text-gray-500">No activity logs found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($logs->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
