<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl mb-4 shadow-lg">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Privacy Policy</h1>
                <p class="text-gray-600">Last updated: {{ date('F d, Y') }}</p>
                <a href="{{ url('/') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mt-4 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Home
                </a>
            </div>

            <!-- Content Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-8 md:p-12">
                    <!-- Introduction -->
                    <section class="mb-10">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                                    <i class="fas fa-info-circle text-blue-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-2xl font-bold text-gray-900 mb-3">Introduction</h2>
                                <p class="text-gray-700 leading-relaxed">
                                    Welcome to <strong>BangunanPro</strong> ("we," "us," or "our"). We respect your privacy and are committed to protecting your personal data. This privacy policy explains how we collect, use, and safeguard your information when you use our application.
                                </p>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Data Collection -->
                    <section class="mb-10">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg">
                                    <i class="fas fa-database text-green-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">Data We Collect</h2>
                                <p class="text-gray-700 mb-4">We may collect information about you in a variety of ways:</p>
                                
                                <div class="space-y-4">
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <h3 class="font-semibold text-gray-900 mb-2 flex items-center">
                                            <i class="fas fa-user text-blue-500 mr-2"></i>
                                            Personal Data
                                        </h3>
                                        <p class="text-gray-600 text-sm">Name, email address, phone number (for WhatsApp notifications).</p>
                                    </div>

                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <h3 class="font-semibold text-gray-900 mb-2 flex items-center">
                                            <i class="fas fa-briefcase text-green-500 mr-2"></i>
                                            Business Data
                                        </h3>
                                        <p class="text-gray-600 text-sm">Product inventory, sales records, customer and supplier information.</p>
                                    </div>

                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <h3 class="font-semibold text-gray-900 mb-2 flex items-center">
                                            <i class="fas fa-chart-line text-purple-500 mr-2"></i>
                                            Usage Data
                                        </h3>
                                        <p class="text-gray-600 text-sm">Information about how you use the application, log data, and error reports.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- How We Use Data -->
                    <section class="mb-10">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg">
                                    <i class="fas fa-cogs text-purple-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">How We Use Your Information</h2>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span class="text-gray-700">Provide, operate, and maintain our application</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span class="text-gray-700">Process your transactions and manage your inventory</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span class="text-gray-700">Send WhatsApp notifications for receipts and alerts</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span class="text-gray-700">Monitor and analyze usage to improve user experience</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <span class="text-gray-700">Prevent fraudulent transactions and monitor security</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Data Sharing -->
                    <section class="mb-10">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-orange-100 rounded-lg">
                                    <i class="fas fa-share-alt text-orange-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">Data Disclosure</h2>
                                <p class="text-gray-700 mb-4">We may share your information in the following situations:</p>
                                
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg mb-4">
                                    <h3 class="font-semibold text-gray-900 mb-2">Third-Party Service Providers</h3>
                                    <p class="text-gray-600 text-sm">We use trusted partners like Fonnte (WhatsApp), payment processors, and error tracking services.</p>
                                </div>

                                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                                    <h3 class="font-semibold text-gray-900 mb-2">Legal Requirements</h3>
                                    <p class="text-gray-600 text-sm">If required by law or in response to valid requests by public authorities.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Security -->
                    <section class="mb-10">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-lg">
                                    <i class="fas fa-lock text-red-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-2xl font-bold text-gray-900 mb-3">Security</h2>
                                <p class="text-gray-700 leading-relaxed">
                                    We use administrative, technical, and physical security measures to protect your personal information. While we take reasonable steps to secure your data, please be aware that no security measures are perfect or impenetrable.
                                </p>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Contact -->
                    <section>
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-envelope text-2xl mr-3"></i>
                                <h2 class="text-2xl font-bold">Contact Us</h2>
                            </div>
                            <p class="mb-4">If you have questions about this Privacy Policy, please contact us at:</p>
                            <a href="mailto:support@bangunanpro.com" class="inline-flex items-center bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                                <i class="fas fa-paper-plane mr-2"></i>
                                support@bangunanpro.com
                            </a>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Footer Navigation -->
            <div class="mt-8 text-center">
                <div class="inline-flex gap-4">
                    <a href="{{ route('legal.terms') }}" class="text-gray-600 hover:text-blue-600 transition">
                        Terms of Service
                    </a>
                    <span class="text-gray-400">•</span>
                    <a href="{{ route('support.faq') }}" class="text-gray-600 hover:text-blue-600 transition">
                        FAQ
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
