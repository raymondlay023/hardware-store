<div>
    @if (!$importComplete)
        <form wire:submit.prevent="import" class="space-y-5">
            <!-- Instructions -->
            <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded">
                <h3 class="font-bold text-purple-900 mb-2 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> How to Import Products
                </h3>
                <ol class="list-decimal list-inside space-y-1 text-sm text-purple-800">
                    <li>Download the Excel template below</li>
                    <li>Fill in your product data (keep the purple header row unchanged)</li>
                    <li>Remove the sample data rows (rows 2-4) before uploading</li>
                    <li><strong>NEW:</strong> Add brand names and aliases (comma-separated) for better search</li>
                    <li>Save the file and upload it here</li>
                    <li>Click Import to add products to your inventory</li>
                </ol>
                <div class="mt-3 bg-purple-100 rounded p-2 text-xs text-purple-900">
                    <strong>💡 Pro Tips:</strong>
                    <ul class="list-disc list-inside mt-1 space-y-1">
                        <li>Keep the template file and reuse it for future imports</li>
                        <li>Use <strong>aliases</strong> for alternative names: "Wall paint, Cat dinding, Avian white"</li>
                        <li>Separate multiple aliases with commas in the same cell</li>
                    </ul>
                </div>
            </div>

            <!-- Download Template Button -->
            <div class="text-center py-4 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <button type="button" wire:click="downloadTemplate"
                    class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-lg flex items-center gap-2 mx-auto">
                    <i class="fas fa-download"></i> Download Excel Template (.xlsx)
                </button>
                <p class="text-xs text-gray-600 mt-2">Template includes sample data and formatting</p>
            </div>

            <!-- File Upload -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-file-upload mr-1 text-purple-600"></i>Select File to Import <span
                        class="text-red-500">*</span>
                </label>

                <div class="flex items-center gap-3">
                    <input type="file" wire:model="file" accept=".csv,.xlsx,.xls"
                        class="flex-1 px-4 py-2 border-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm
                        @if ($fileError) border-red-500 @else border-gray-300 @endif">

                    @if ($file && !$fileError)
                        <div class="flex items-center gap-2 text-green-600">
                            <i class="fas fa-check-circle"></i>
                            <span class="text-sm font-medium">File ready</span>
                        </div>
                    @endif
                </div>

                <!-- Custom Error Display -->
                @if ($fileError)
                    <span class="text-red-600 text-sm mt-1 block flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>{{ $fileError }}
                    </span>
                @endif

                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Supported formats: CSV, Excel (.csv, .xlsx, .xls) | Max: 2MB
                </p>

                <!-- Loading Indicator -->
                <div wire:loading wire:target="file" class="mt-2">
                    <div class="flex items-center gap-2 text-blue-600">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span class="text-sm">Uploading file...</span>
                    </div>
                </div>
            </div>

            <!-- Column Reference - UPDATED -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-bold text-blue-900 mb-3 text-sm flex items-center gap-2">
                    <i class="fas fa-table"></i> Template Columns Guide
                </h4>
                
                <!-- Required Fields -->
                <div class="mb-3">
                    <p class="text-xs font-semibold text-blue-900 mb-2">✅ Required Columns:</p>
                    <div class="grid grid-cols-2 gap-2 text-xs text-blue-800">
                        <div><code class="bg-blue-100 px-1 rounded">name</code> - Product name</div>
                        <div><code class="bg-blue-100 px-1 rounded">category</code> - Product category</div>
                        <div><code class="bg-blue-100 px-1 rounded">unit</code> - piece, bag, box, meter, kg</div>
                        <div><code class="bg-blue-100 px-1 rounded">price</code> - Price in Rupiah</div>
                    </div>
                </div>

                <!-- Optional Fields - NEW SECTION -->
                <div>
                    <p class="text-xs font-semibold text-blue-900 mb-2">⚙️ Optional Columns:</p>
                    <div class="grid grid-cols-2 gap-2 text-xs text-blue-800">
                        <div><code class="bg-blue-100 px-1 rounded">brand</code> - Brand/manufacturer name <span class="text-green-600 font-bold">NEW!</span></div>
                        <div><code class="bg-blue-100 px-1 rounded">stock</code> - Initial stock quantity</div>
                        <div><code class="bg-blue-100 px-1 rounded">supplier</code> - Supplier name</div>
                        <div><code class="bg-blue-100 px-1 rounded">aliases</code> - Alt. names (comma-separated) <span class="text-green-600 font-bold">NEW!</span></div>
                        <div><code class="bg-blue-100 px-1 rounded">low_stock_threshold</code> - Low stock alert level</div>
                        <div><code class="bg-blue-100 px-1 rounded">critical_stock_threshold</code> - Critical alert</div>
                        <div><code class="bg-blue-100 px-1 rounded">auto_reorder</code> - Enable auto-reorder (true/false)</div>
                        <div><code class="bg-blue-100 px-1 rounded">reorder_quantity</code> - Auto-reorder quantity</div>
                    </div>
                </div>

                <!-- Aliases Example -->
                <div class="mt-3 bg-green-50 border border-green-200 rounded p-2">
                    <p class="text-xs font-semibold text-green-900 mb-1">
                        <i class="fas fa-lightbulb"></i> Aliases Example:
                    </p>
                    <code class="text-xs text-green-800 bg-white px-2 py-1 rounded block">
                        "Wall paint, Cat dinding, Avian white"
                    </code>
                    <p class="text-xs text-green-700 mt-1">Separate multiple names with commas</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" wire:loading.attr="disabled" wire:target="import"
                    @if (!$file) disabled @endif
                    class="flex-1 bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-purple-600 hover:to-purple-700 transition font-semibold flex items-center justify-center gap-2 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="import">
                        <i class="fas fa-file-import mr-2"></i>Import Products
                    </span>
                    <span wire:loading wire:target="import">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Importing...
                    </span>
                </button>
                <button type="button" wire:click="cancel" wire:loading.attr="disabled" wire:target="import"
                    class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition font-semibold disabled:opacity-50">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </form>
    @else
        <!-- Success State -->
        <div class="text-center py-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-4xl text-green-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Import Successful!</h3>
            <p class="text-gray-600 mb-6">
                Successfully imported <span class="font-bold text-green-600">{{ $importedCount }}</span> products
            </p>
            <button wire:click="cancel"
                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-lg hover:from-green-600 hover:to-green-700 transition font-semibold">
                <i class="fas fa-check-circle mr-2"></i>Done
            </button>
        </div>
    @endif

    <!-- Display Errors -->
    @if (count($errors) > 0 || count($failures) > 0)
        <div class="mt-5 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <h4 class="font-bold text-red-900 mb-3 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle"></i> Import Errors
            </h4>

            @if (count($errors) > 0)
                <div class="mb-3">
                    <p class="text-sm font-semibold text-red-800 mb-1">General Errors:</p>
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach ($errors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (count($failures) > 0)
                <div>
                    <p class="text-sm font-semibold text-red-800 mb-2">Row-specific Errors ({{ count($failures) }} rows
                        failed):</p>
                    <div class="max-h-48 overflow-y-auto bg-white rounded border border-red-200">
                        <table class="w-full text-xs">
                            <thead class="bg-red-100 sticky top-0">
                                <tr>
                                    <th class="px-3 py-2 text-left font-bold">Row</th>
                                    <th class="px-3 py-2 text-left font-bold">Field</th>
                                    <th class="px-3 py-2 text-left font-bold">Error</th>
                                </tr>
                            </thead>
                            <tbody class="text-red-700 divide-y divide-red-200">
                                @foreach ($failures as $failure)
                                    <tr>
                                        <td class="px-3 py-2 font-semibold">{{ $failure['row'] }}</td>
                                        <td class="px-3 py-2">{{ $failure['attribute'] }}</td>
                                        <td class="px-3 py-2">{{ implode(', ', $failure['errors']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <button wire:click="resetImport"
                class="mt-3 text-sm bg-red-100 hover:bg-red-200 text-red-800 px-4 py-2 rounded transition font-semibold">
                <i class="fas fa-redo mr-1"></i>Try Again
            </button>
        </div>
    @endif
</div>
