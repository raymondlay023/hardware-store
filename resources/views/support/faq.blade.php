<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl mb-4 shadow-lg">
                    <i class="fas fa-question-circle text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Frequently Asked Questions</h1>
                <p class="text-gray-600">Get answers to common questions about BangunanPro</p>
                <a href="{{ url('/') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mt-4 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Home
                </a>
            </div>

            <!-- FAQ Cards -->
            <div class="space-y-6 mb-8">
                <!-- Question 1 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-xl">
                                    <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">How does the pricing work?</h3>
                                <p class="text-gray-700 leading-relaxed">
                                    We offer a flexible pricing model designed to grow with your business. We have a <span class="font-semibold text-blue-600">Free Tier</span> for small shops, and paid tiers (<span class="font-semibold">Starter</span>, <span class="font-semibold">Business</span>, <span class="font-semibold">Professional</span>) for growing businesses with more advanced needs. You can switch plans at any time.
                                </p>
                                <a href="{{ url('/#pricing') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mt-3 font-medium">
                                    View Pricing Plans <i class="fas fa-arrow-right ml-2 text-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question 2 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-xl">
                                    <i class="fas fa-wifi text-green-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Can I use BangunanPro offline?</h3>
                                <p class="text-gray-700 leading-relaxed mb-3">
                                    BangunanPro is a cloud-based application, which means you need an internet connection to access your data. This ensures your inventory and sales are always backed up and accessible from anywhere.
                                </p>
                                <div class="bg-green-50 border-l-4 border-green-500 p-3 rounded-r-lg">
                                    <p class="text-sm text-gray-700">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        <strong>Benefit:</strong> Your data is automatically synced and never lost!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question 3 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-xl">
                                    <i class="fas fa-shield-alt text-red-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Is my data secure?</h3>
                                <p class="text-gray-700 mb-4">
                                    Yes, we take security seriously. We implement industry-standard practices:
                                </p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="flex items-center bg-gray-50 rounded-lg p-3">
                                        <i class="fas fa-lock text-red-500 mr-3"></i>
                                        <span class="text-sm text-gray-700">Encrypted connections</span>
                                    </div>
                                    <div class="flex items-center bg-gray-50 rounded-lg p-3">
                                        <i class="fas fa-key text-yellow-500 mr-3"></i>
                                        <span class="text-sm text-gray-700">Hashed passwords</span>
                                    </div>
                                    <div class="flex items-center bg-gray-50 rounded-lg p-3">
                                        <i class="fas fa-database text-blue-500 mr-3"></i>
                                        <span class="text-sm text-gray-700">Regular backups</span>
                                    </div>
                                    <div class="flex items-center bg-gray-50 rounded-lg p-3">
                                        <i class="fas fa-server text-green-500 mr-3"></i>
                                        <span class="text-sm text-gray-700">Secure servers</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question 4 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-xl">
                                    <i class="fas fa-store text-purple-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Can I manage multiple outlets?</h3>
                                <p class="text-gray-700 leading-relaxed mb-3">
                                    Multi-outlet support is available on our <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded font-semibold text-sm">Professional Plan</span>. This allows you to manage inventory, sales, and reports for different locations from a single admin dashboard.
                                </p>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-info-circle text-purple-500 mr-2"></i>
                                    Perfect for growing businesses with 3+ locations
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question 5 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-xl">
                                    <i class="fas fa-headset text-orange-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">How do I get support?</h3>
                                <p class="text-gray-700 mb-4">We offer multiple support channels based on your plan:</p>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope text-blue-500 mr-3 w-5"></i>
                                        <span class="text-gray-700">Email: <strong>support@bangunanpro.com</strong></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fab fa-whatsapp text-green-500 mr-3 w-5"></i>
                                        <span class="text-gray-700">WhatsApp (Business & Professional plans)</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-bolt text-yellow-500 mr-3 w-5"></i>
                                        <span class="text-gray-700">Priority Support (Professional plan)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Question 6 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 rounded-xl">
                                    <i class="fas fa-barcode text-indigo-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Do you support barcode scanners?</h3>
                                <p class="text-gray-700 leading-relaxed mb-3">
                                    Yes! BangunanPro supports most standard USB and Bluetooth barcode scanners. Just plug it in, and you can start scanning products in the POS and inventory sections.
                                </p>
                                <div class="bg-indigo-50 rounded-lg p-3">
                                    <p class="text-sm text-gray-700">
                                        <i class="fas fa-lightbulb text-indigo-500 mr-2"></i>
                                        <strong>Tip:</strong> Also works with your phone camera in the mobile app!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Still Have Questions CTA -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-8 text-white shadow-xl">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-2xl mb-4">
                        <i class="fas fa-comments text-3xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold mb-3">Still have questions?</h2>
                    <p class="text-purple-100 mb-6 max-w-2xl mx-auto">
                        Our support team is here to help! Reach out and we'll get back to you as soon as possible.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="mailto:support@bangunanpro.com" class="inline-flex items-center justify-center bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-purple-50 transition shadow-lg">
                            <i class="fas fa-envelope mr-2"></i>
                            Email Support
                        </a>
                        <a href="{{ route('support.manual') }}" class="inline-flex items-center justify-center bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-800 transition">
                            <i class="fas fa-book mr-2"></i>
                            Read User Manual
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Navigation -->
            <div class="mt-8 text-center">
                <div class="inline-flex gap-4">
                    <a href="{{ route('legal.privacy') }}" class="text-gray-600 hover:text-blue-600 transition">
                        Privacy Policy
                    </a>
                    <span class="text-gray-400">•</span>
                    <a href="{{ route('legal.terms') }}" class="text-gray-600 hover:text-blue-600 transition">
                        Terms of Service
                    </a>
                    <span class="text-gray-400">•</span>
                    <a href="{{ route('support.manual') }}" class="text-gray-600 hover:text-blue-600 transition">
                        User Manual
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
