<div 
    x-data="toastManager()" 
    @notify.window="addToast($event.detail)"
    class="fixed top-4 right-4 z-50 space-y-2"
    aria-live="polite"
    aria-atomic="true"
    role="status"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div 
            x-show="toast.visible"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0"
            class="max-w-sm w-full shadow-lg rounded-lg pointer-events-auto overflow-hidden"
            :class="{
                'bg-green-50 border-l-4 border-green-500': toast.type === 'success',
                'bg-red-50 border-l-4 border-red-500': toast.type === 'error',
                'bg-yellow-50 border-l-4 border-yellow-500': toast.type === 'warning',
                'bg-blue-50 border-l-4 border-blue-500': toast.type === 'info'
            }"
            role="alert"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas text-xl" :class="{
                            'fa-check-circle text-green-500': toast.type === 'success',
                            'fa-exclamation-circle text-red-500': toast.type === 'error',
                            'fa-exclamation-triangle text-yellow-500': toast.type === 'warning',
                            'fa-info-circle text-blue-500': toast.type === 'info'
                        }" aria-hidden="true"></i>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium" :class="{
                            'text-green-900': toast.type === 'success',
                            'text-red-900': toast.type === 'error',
                            'text-yellow-900': toast.type === 'warning',
                            'text-blue-900': toast.type === 'info'
                        }" x-text="toast.message"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button 
                            @click="removeToast(toast.id)"
                            class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none"
                            aria-label="Close notification"
                        >
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function toastManager() {
        return {
            toasts: [],
            nextId: 1,

            addToast(detail) {
                const id = this.nextId++;
                const toast = {
                    id,
                    message: detail.message || 'Notification',
                    type: detail.type || 'info',
                    visible: false
                };

                this.toasts.push(toast);

                // Trigger animation
                this.$nextTick(() => {
                    const toastIndex = this.toasts.findIndex(t => t.id === id);
                    if (toastIndex !== -1) {
                        this.toasts[toastIndex].visible = true;
                    }
                });

                // Auto remove after 4 seconds
                setTimeout(() => this.removeToast(id), 4000);
            },

            removeToast(id) {
                const toastIndex = this.toasts.findIndex(t => t.id === id);
                if (toastIndex !== -1) {
                    this.toasts[toastIndex].visible = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 300);
                }
            }
        }
    }
</script>
