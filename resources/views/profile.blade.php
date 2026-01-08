<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                    <i class="fas fa-user-circle text-primary-600"></i>
                    Profile Settings
                </h1>
                <p class="text-gray-600">Manage your account information and preferences</p>
            </div>

            <div class="space-y-6">
                <!-- Profile Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-id-card"></i> Profile Information
                        </h2>
                        <p class="text-primary-100 text-sm">Update your account's profile information and email address</p>
                    </div>
                    <div class="p-6">
                        <livewire:profile.update-profile-information-form />
                    </div>
                </div>

                <!-- Update Password -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-key"></i> Update Password
                        </h2>
                        <p class="text-blue-100 text-sm">Ensure your account is using a long, random password to stay secure</p>
                    </div>
                    <div class="p-6">
                        <livewire:profile.update-password-form />
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="bg-white rounded-lg shadow-sm border border-red-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle"></i> Delete Account
                        </h2>
                        <p class="text-red-100 text-sm">Permanently delete your account and all associated data</p>
                    </div>
                    <div class="p-6">
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
