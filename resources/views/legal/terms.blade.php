<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl mb-4 shadow-lg">
                    <i class="fas fa-file-contract text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-3">Terms of Service</h1>
                <p class="text-gray-600">Last updated: {{ date('F d, Y') }}</p>
                <a href="{{ url('/') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mt-4 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Home
                </a>
            </div>

            <!-- Content Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-8 md:p-12">
                    <!-- Agreement -->
                    <section class="mb-10">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                                    <i class="fas fa-handshake text-blue-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-2xl font-bold text-gray-900 mb-3">Agreement to Terms</h2>
                                <p class="text-gray-700 leading-relaxed">
                                    These Terms of Service constitute a legally binding agreement between you and <strong>BangunanPro</strong> concerning your access to and use of the application. By accessing or using the application, you agree to be bound by these Terms.
                                </p>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- User Accounts -->
                    <section class="mb-10">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg">
                                    <i class="fas fa-user-circle text-purple-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">User Accounts</h2>
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-key text-yellow-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <p class="text-gray-700">You must keep your password confidential and will be responsible for all use of your account.</p>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-user-check text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                        <p class="text-gray-700">We reserve the right to remove, reclaim, or change usernames that are inappropriate or objectionable.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Subscriptions -->
                    <section class="mb-10">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg">
                                    <i class="fas fa-credit-card text-green-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">Subscriptions & Payment</h2>
                                <div class="bg-blue-50 rounded-lg p-5 border border-blue-200">
                                    <ul class="space-y-3">
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-blue-500 text-xs mt-2 mr-3 flex-shrink-0"></i>
                                            <span class="text-gray-700">Provide current, complete, and accurate purchase information</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-blue-500 text-xs mt-2 mr-3 flex-shrink-0"></i>
                                            <span class="text-gray-700">Update account and payment information promptly</span>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-circle text-blue-500 text-xs mt-2 mr-3 flex-shrink-0"></i>
                                            <span class="text-gray-700">Subscription fees are non-refundable except as required by law</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Prohibited Activities -->
                    <section class="mb-10">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-lg">
                                    <i class="fas fa-ban text-red-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">Prohibited Activities</h2>
                                <p class="text-gray-700 mb-4">You may not use the Application for:</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                                        <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                        <span class="text-sm text-gray-700">Unauthorized commercial use</span>
                                    </div>
                                    <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                                        <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                        <span class="text-sm text-gray-700">Illegal activities</span>
                                    </div>
                                    <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                                        <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                        <span class="text-sm text-gray-700">Hacking or system abuse</span>
                                    </div>
                                    <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                                        <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                        <span class="text-sm text-gray-700">Data scraping or mining</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Liability -->
                    <section class="mb-10">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-yellow-100 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-2xl font-bold text-gray-900 mb-3">Limitation of Liability</h2>
                                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                                    <p class="text-gray-700 text-sm leading-relaxed">
                                        We will not be liable for any direct, indirect, incidental, special, or consequential damages, including lost profit, lost revenue, or loss of data arising from your use of the application.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Termination -->
                    <section class="mb-10">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-gray-100 rounded-lg">
                                    <i class="fas fa-door-open text-gray-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-2xl font-bold text-gray-900 mb-3">Termination</h2>
                                <p class="text-gray-700 leading-relaxed">
                                    We may terminate or suspend your account immediately, without prior notice, for any reason, including if you breach these Terms. Upon termination, your right to use the Application will cease immediately.
                                </p>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Governing Law -->
                    <section class="mb-10">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 bg-indigo-100 rounded-lg">
                                    <i class="fas fa-gavel text-indigo-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-2xl font-bold text-gray-900 mb-3">Governing Law</h2>
                                <p class="text-gray-700 leading-relaxed">
                                    These Terms shall be governed by the laws of <strong>Indonesia</strong>, without regard to its conflict of law provisions.
                                </p>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-gray-200 my-8"></div>

                    <!-- Contact -->
                    <section>
                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-envelope text-2xl mr-3"></i>
                                <h2 class="text-2xl font-bold">Questions?</h2>
                            </div>
                            <p class="mb-4">If you have any questions about these Terms, please contact us at:</p>
                            <a href="mailto:support@bangunanpro.com" class="inline-flex items-center bg-white text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-green-50 transition">
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
                    <a href="{{ route('legal.privacy') }}" class="text-gray-600 hover:text-blue-600 transition">
                        Privacy Policy
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
