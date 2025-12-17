<div>
    @if ($sale)
        <div class="space-y-5">
            <!-- Print Options Modal -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-print text-blue-600 mr-2"></i>Receipt & Printing Options
                </h2>

                <!-- Sale Summary -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 font-semibold uppercase">Invoice #</p>
                            <p class="text-2xl font-black text-gray-900">#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 font-semibold uppercase">Customer</p>
                            <p class="text-lg font-bold text-gray-900">{{ $sale->customer_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 font-semibold uppercase">Total Amount</p>
                            <p class="text-2xl font-black text-green-600">Rp
                                {{ number_format($sale->total_amount - $sale->discount_value, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Printing Options -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-cog text-gray-600 mr-2"></i>Select Print Method
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Option 1: Thermal Printer (58mm) -->
                        <div class="border-2 border-gray-300 rounded-lg p-4 hover:border-green-500 hover:bg-green-50 transition cursor-pointer group"
                            wire:click="$set('printFormat', 'thermal')">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm uppercase">Thermal Printer (58mm)</h4>
                                    <p class="text-xs text-gray-600 mt-1">POS Receipt</p>
                                </div>
                                <div class="w-5 h-5 border-2 border-green-500 rounded-full flex items-center justify-center"
                                    :class="{ 'bg-green-500': @js($printFormat === 'thermal') }">
                                    @if ($printFormat === 'thermal')
                                        <i class="fas fa-check text-white text-xs"></i>
                                    @endif
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mb-4">Compact receipt for thermal printer. Best for fast POS
                                operations.</p>

                            <button type="button" wire:click="printThermal"
                                class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition">
                                <i class="fas fa-download mr-2"></i>Download TXT
                            </button>
                        </div>

                        <!-- Option 2: PDF (A4) -->
                        <div class="border-2 border-gray-300 rounded-lg p-4 hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer group"
                            wire:click="$set('printFormat', 'pdf')">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm uppercase">PDF Invoice (A4)</h4>
                                    <p class="text-xs text-gray-600 mt-1">Full Invoice</p>
                                </div>
                                <div class="w-5 h-5 border-2 border-blue-500 rounded-full flex items-center justify-center"
                                    :class="{ 'bg-blue-500': @js($printFormat === 'pdf') }">
                                    @if ($printFormat === 'pdf')
                                        <i class="fas fa-check text-white text-xs"></i>
                                    @endif
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mb-4">Professional invoice in PDF format. Great for email &
                                records.</p>

                            <!-- Add this before the PDF download button -->
                            <div class="mb-3">
                                <label class="text-xs font-semibold text-gray-700 mb-2 block">Orientation:</label>
                                <div class="flex gap-2">
                                    <button type="button" wire:click="$set('orientation', 'portrait')"
                                        class="flex-1 px-3 py-2 rounded {{ $orientation === 'portrait' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                                        <i class="fas fa-file mr-1"></i> Portrait
                                    </button>
                                    <button type="button" wire:click="$set('orientation', 'landscape')"
                                        class="flex-1 px-3 py-2 rounded {{ $orientation === 'landscape' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                                        <i class="fas fa-file-alt mr-1"></i> Landscape
                                    </button>
                                </div>
                            </div>

                            <button type="button" wire:click="printPDF"
                                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition">
                                <i class="fas fa-file-pdf mr-2"></i>Download PDF
                            </button>
                        </div>

                        <!-- Option 3: Browser Print -->
                        <div class="border-2 border-gray-300 rounded-lg p-4 hover:border-purple-500 hover:bg-purple-50 transition cursor-pointer group"
                            wire:click="$set('printFormat', 'browser')">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm uppercase">Browser Print</h4>
                                    <p class="text-xs text-gray-600 mt-1">Any Printer</p>
                                </div>
                                <div class="w-5 h-5 border-2 border-purple-500 rounded-full flex items-center justify-center"
                                    :class="{ 'bg-purple-500': @js($printFormat === 'browser') }">
                                    @if ($printFormat === 'browser')
                                        <i class="fas fa-check text-white text-xs"></i>
                                    @endif
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mb-4">Open in browser print dialog. Works with any connected
                                printer.</p>

                            <button type="button" onclick="window.print()"
                                class="w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition">
                                <i class="fas fa-print mr-2"></i>Print Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-gray-500">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-eye text-gray-600 mr-2"></i>Receipt Preview
                </h3>

                <!-- Thermal Preview -->
                @if ($printFormat === 'thermal')
                    <div class="bg-gray-100 p-4 rounded-lg font-mono text-xs overflow-x-auto">
                        <pre class="whitespace-pre-wrap break-words text-gray-800">{{ $this->generateThermalReceipt() }}</pre>
                    </div>
                @endif

                <!-- PDF Preview (HTML version) -->
                @if ($printFormat === 'pdf')
                    <div class="bg-white border-2 border-gray-300 p-6 rounded-lg max-w-2xl mx-auto">
                        <div class="text-center mb-6">
                            <h1 class="text-2xl font-bold text-gray-900">{{ config('app.name', 'HARDWARE STORE') }}</h1>
                            <p class="text-gray-600 text-sm">INVOICE</p>
                        </div>

                        <div class="border-t-2 border-b-2 border-gray-300 py-4 mb-6">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600"><strong>Invoice #:</strong></p>
                                    <p class="text-gray-900 font-bold">#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-600"><strong>Date:</strong></p>
                                    <p class="text-gray-900 font-bold">{{ $sale->date->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600"><strong>Customer:</strong></p>
                                    <p class="text-gray-900 font-bold">{{ $sale->customer_name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-600"><strong>Payment:</strong></p>
                                    <p class="text-gray-900 font-bold">{{ ucfirst($sale->payment_method) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <table class="w-full text-sm mb-6">
                            <thead class="bg-gray-100 border-b-2 border-gray-300">
                                <tr>
                                    <th class="text-left py-2 px-2">Description</th>
                                    <th class="text-center py-2 px-2">Qty</th>
                                    <th class="text-right py-2 px-2">Unit Price</th>
                                    <th class="text-right py-2 px-2">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach ($sale->saleItems as $item)
                                    <tr>
                                        <td class="py-2 px-2">{{ $item->product->name }}</td>
                                        <td class="text-center py-2 px-2">{{ $item->quantity }}</td>
                                        <td class="text-right py-2 px-2">Rp
                                            {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="text-right py-2 px-2">Rp
                                            {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Totals -->
                        <div class="border-t-2 border-gray-300 pt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-bold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                            </div>
                            @if ($sale->discount_value > 0)
                                <div class="flex justify-between text-red-600">
                                    <span>Discount:</span>
                                    <span class="font-bold">- Rp
                                        {{ number_format($sale->discount_value, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-lg font-bold border-t pt-2">
                                <span>Total:</span>
                                <span>Rp
                                    {{ number_format($sale->total_amount - $sale->discount_value, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="text-center mt-6 pt-6 border-t-2 border-gray-300 text-gray-600 text-xs">
                            <p>Thank you for your purchase!</p>
                            <p>{{ now()->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Browser Print Preview -->
                @if ($printFormat === 'browser')
                    <div class="bg-blue-50 border-2 border-blue-200 p-4 rounded-lg text-center">
                        <i class="fas fa-info-circle text-blue-600 text-2xl mb-3 block"></i>
                        <p class="text-gray-900 font-semibold mb-2">Ready to Print</p>
                        <p class="text-gray-600 text-sm">Click the "Print Now" button above to open your browser's
                            print
                            dialog.</p>
                        <p class="text-gray-600 text-sm mt-2">You can then select any printer or print to PDF.</p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('sales.index') }}"
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-bold flex items-center justify-center gap-2 transition">
                    <i class="fas fa-arrow-left"></i> Back to Sales
                </a>
            </div>
        </div>
    @else
        <div class="bg-red-50 border-2 border-red-200 rounded-lg p-8 text-center">
            <i class="fas fa-exclamation-circle text-red-600 text-4xl mb-4 block"></i>
            <p class="text-red-700 font-semibold">Sale not found</p>
            <a href="{{ route('sales.index') }}"
                class="inline-block mt-4 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                Back to Sales
            </a>
        </div>
    @endif

    @push('scripts')
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                .print-area,
                .print-area * {
                    visibility: visible;
                }

                .print-area {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                .no-print {
                    display: none !important;
                }

                @page {
                    size: A4;
                    margin: 10mm;
                }
            }
        </style>
    @endpush
</div>
