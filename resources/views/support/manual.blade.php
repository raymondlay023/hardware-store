<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg mr-3">
                    <i class="fas fa-book text-white"></i>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('User Manual') }}
                </h2>
            </div>
            <a href="{{ route('support.faq') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                <i class="fas fa-question-circle mr-1"></i> FAQ
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-8 mb-8 text-white shadow-lg">
                <div class="flex items-center mb-4">
                    <i class="fas fa-rocket text-3xl mr-4"></i>
                    <div>
                        <h1 class="text-3xl font-bold">Getting Started with BangunanPro</h1>
                        <p class="text-blue-100 mt-2">Your complete guide to managing your hardware store efficiently</p>
                    </div>
                </div>
            </div>

            <!-- Quick Navigation -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                <a href="#dashboard" class="bg-white rounded-lg p-4 shadow hover:shadow-lg transition text-center">
                    <i class="fas fa-chart-line text-blue-500 text-2xl mb-2"></i>
                    <p class="font-semibold text-sm">Dashboard</p>
                </a>
                <a href="#products" class="bg-white rounded-lg p-4 shadow hover:shadow-lg transition text-center">
                    <i class="fas fa-box text-green-500 text-2xl mb-2"></i>
                    <p class="font-semibold text-sm">Products</p>
                </a>
                <a href="#pos" class="bg-white rounded-lg p-4 shadow hover:shadow-lg transition text-center">
                    <i class="fas fa-cash-register text-purple-500 text-2xl mb-2"></i>
                    <p class="font-semibold text-sm">POS</p>
                </a>
                <a href="#relationships" class="bg-white rounded-lg p-4 shadow hover:shadow-lg transition text-center">
                    <i class="fas fa-handshake text-orange-500 text-2xl mb-2"></i>
                    <p class="font-semibold text-sm">Contacts</p>
                </a>
                <a href="#reports" class="bg-white rounded-lg p-4 shadow hover:shadow-lg transition text-center">
                    <i class="fas fa-chart-bar text-red-500 text-2xl mb-2"></i>
                    <p class="font-semibold text-sm">Reports</p>
                </a>
            </div>

            <!-- Main Content -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <!-- Section 1: Dashboard -->
                    <section id="dashboard" class="mb-12">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-xl mr-4">
                                <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900">Dashboard Overview</h2>
                        </div>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            The dashboard provides a quick snapshot of your business performance. Here's what you'll see:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <h3 class="font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-dollar-sign text-blue-500 mr-2"></i>
                                    Daily Sales
                                </h3>
                                <p class="text-sm text-gray-600">Real-time tracking of today's revenue and transactions</p>
                            </div>
                            <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                <h3 class="font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                    Low Stock Alerts
                                </h3>
                                <p class="text-sm text-gray-600">Products that need reordering</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                <h3 class="font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-receipt text-green-500 mr-2"></i>
                                    Recent Transactions
                                </h3>
                                <p class="text-sm text-gray-600">Latest sales and purchases</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                <h3 class="font-semibold text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-chart-bar text-purple-500 mr-2"></i>
                                    Performance Metrics
                                </h3>
                                <p class="text-sm text-gray-600">Sales trends and analytics</p>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Section 2: Products -->
                    <section id="products" class="mb-12">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-xl mr-4">
                                <i class="fas fa-box text-green-600 text-xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900">Managing Products</h2>
                        </div>

                        <h3 class="text-xl font-bold text-gray-800 mb-3">Adding a New Product</h3>
                        <ol class="space-y-3 mb-6">
                            <li class="flex items-start">
                                <span class="flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full mr-3 flex-shrink-0 font-bold">1</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Navigate to Products</p>
                                    <p class="text-sm text-gray-600">Go to <strong>Inventory > Products</strong> in the main navigation.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full mr-3 flex-shrink-0 font-bold">2</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Click "Add New Product"</p>
                                    <p class="text-sm text-gray-600">Located at the top right of the products list.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full mr-3 flex-shrink-0 font-bold">3</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Fill in Product Details</p>
                                    <p class="text-sm text-gray-600">Name, SKU, Category, Price, Stock Quantity, and optionally an image.</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full mr-3 flex-shrink-0 font-bold">4</span>
                                <div>
                                    <p class="font-semibold text-gray-800">Save Product</p>
                                    <p class="text-sm text-gray-600">Your product is now ready for sale!</p>
                                </div>
                            </li>
                        </ol>

                        <h3 class="text-xl font-bold text-gray-800 mb-3">Stock Adjustments</h3>
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                            <p class="text-gray-700">
                                <i class="fas fa-info-circle text-yellow-500 mr-2"></i>
                                Use <strong>Inventory > Stock Adjustment</strong> to correct inventory levels for damaged goods, stock takes, or manual corrections.
                            </p>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Section 3: POS -->
                    <section id="pos" class="mb-12">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-xl mr-4">
                                <i class="fas fa-cash-register text-purple-600 text-xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900">Point of Sale (POS)</h2>
                        </div>
                        <p class="text-gray-700 mb-4">The POS is where you process daily sales quickly and efficiently.</p>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg mr-4 flex-shrink-0">
                                    <i class="fas fa-barcode text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">Scan or Search Products</h3>
                                    <p class="text-sm text-gray-600">Use the barcode scanner or search bar to quickly find items.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg mr-4 flex-shrink-0">
                                    <i class="fas fa-shopping-cart text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">Add to Cart</h3>
                                    <p class="text-sm text-gray-600">Click products to add them to the current sale. Adjust quantities as needed.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg mr-4 flex-shrink-0">
                                    <i class="fas fa-money-bill-wave text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">Process Payment</h3>
                                    <p class="text-sm text-gray-600">Enter payment amount, and the system calculates change automatically.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg mr-4 flex-shrink-0">
                                    <i class="fas fa-receipt text-purple-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">Generate Receipt</h3>
                                    <p class="text-sm text-gray-600">Print or send via WhatsApp to customer. Stock updates automatically.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Section 4: Relationships -->
                    <section id="relationships" class="mb-12">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-xl mr-4">
                                <i class="fas fa-handshake text-orange-600 text-xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900">Suppliers & Customers</h2>
                        </div>
                        <p class="text-gray-700 mb-4">Keep track of who you buy from and sell to.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 rounded-lg p-5 border border-blue-200">
                                <h3 class="font-bold text-lg text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-truck text-blue-500 mr-2"></i>
                                    Suppliers
                                </h3>
                                <ul class="space-y-2 text-sm text-gray-700">
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Store contact details and payment terms</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Track purchase history</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Manage outstanding balances</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                                <h3 class="font-bold text-lg text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-users text-green-500 mr-2"></i>
                                    Customers
                                </h3>
                                <ul class="space-y-2 text-sm text-gray-700">
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Track loyal customers</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Monitor purchase patterns</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                        <span>Manage credit limits (if applicable)</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Section 5: Reports -->
                    <section id="reports" class="mb-8">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-xl mr-4">
                                <i class="fas fa-chart-bar text-red-600 text-xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900">Reports & Analytics</h2>
                        </div>
                        <p class="text-gray-700 mb-6">Make data-driven decisions with powerful reporting tools.</p>

                        <div class="space-y-4">
                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-chart-line text-blue-500 text-xl mr-3"></i>
                                    <h3 class="font-bold text-gray-900">Sales Report</h3>
                                </div>
                                <p class="text-sm text-gray-600 ml-9">View sales by date range, product, or category. Identify best-sellers and slow-moving items.</p>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-boxes text-green-500 text-xl mr-3"></i>
                                    <h3 class="font-bold text-gray-900">Inventory Report</h3>
                                </div>
                                <p class="text-sm text-gray-600 ml-9">Stock valuation, movement history, and reorder recommendations.</p>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-dollar-sign text-purple-500 text-xl mr-3"></i>
                                    <h3 class="font-bold text-gray-900">Financial Report</h3>
                                </div>
                                <p class="text-sm text-gray-600 ml-9">Profit & loss, cash flow, and financial summaries.</p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Need Help CTA -->
            <div class="mt-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white text-center shadow-lg">
                <h3 class="text-2xl font-bold mb-2">Need More Help?</h3>
                <p class="text-blue-100 mb-4">Check our FAQ or contact support for personalized assistance.</p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('support.faq') }}" class="inline-flex items-center bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                        <i class="fas fa-question-circle mr-2"></i>
                        View FAQ
                    </a>
                    <a href="mailto:support@bangunanpro.com" class="inline-flex items-center bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800 transition">
                        <i class="fas fa-envelope mr-2"></i>
                        Email Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
