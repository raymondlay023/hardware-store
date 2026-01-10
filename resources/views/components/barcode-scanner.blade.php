{{-- Barcode Scanner Modal Component --}}
@props([
    'title' => __('Scan Barcode'),
])

<div x-data="{ 
    showScanner: false,
    scanning: false,
    result: null,
    error: null,
    html5QrCode: null,
    
    async openScanner() {
        this.showScanner = true;
        this.result = null;
        this.error = null;
        
        // Wait for modal to render
        await this.$nextTick();
        
        // Initialize scanner
        setTimeout(() => this.startScanning(), 100);
    },
    
    async startScanning() {
        if (this.scanning) return;
        
        try {
            const { Html5Qrcode } = await import('html5-qrcode');
            this.html5QrCode = new Html5Qrcode('barcode-reader');
            
            await this.html5QrCode.start(
                { facingMode: 'environment' },
                { 
                    fps: 10, 
                    qrbox: { width: 250, height: 100 },
                    aspectRatio: 1.0 
                },
                (decodedText) => {
                    this.result = decodedText;
                    this.playBeep();
                    this.stopScanning();
                    
                    // Dispatch Livewire event
                    $wire.dispatch('barcode-scanned', { barcode: decodedText });
                    
                    // Close modal after short delay
                    setTimeout(() => {
                        this.showScanner = false;
                    }, 500);
                },
                () => {} // Ignore scan errors
            );
            
            this.scanning = true;
        } catch (err) {
            console.error('Scanner error:', err);
            this.error = err.message || 'Camera access denied';
        }
    },
    
    async stopScanning() {
        if (this.html5QrCode && this.scanning) {
            try {
                await this.html5QrCode.stop();
                this.html5QrCode.clear();
            } catch (err) {
                console.error('Stop error:', err);
            }
        }
        this.scanning = false;
    },
    
    closeScanner() {
        this.stopScanning();
        this.showScanner = false;
    },
    
    playBeep() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 1000;
            gain.gain.value = 0.3;
            osc.start();
            setTimeout(() => { osc.stop(); ctx.close(); }, 150);
        } catch (e) {}
    }
}">
    {{-- Trigger Button --}}
    <button type="button" 
            @click="openScanner()" 
            {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition']) }}>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
        </svg>
        <span>{{ $title }}</span>
    </button>
    
    {{-- Scanner Modal --}}
    <div x-show="showScanner" 
         x-transition
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/60" @click="closeScanner()"></div>
            
            {{-- Modal Content --}}
            <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                    <button @click="closeScanner()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                {{-- Scanner Area --}}
                <div class="relative bg-black rounded-lg overflow-hidden" style="min-height: 300px;">
                    <div id="barcode-reader" class="w-full"></div>
                    
                    {{-- Scanning Overlay --}}
                    <div x-show="scanning" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="border-2 border-green-400 rounded-lg animate-pulse" style="width: 250px; height: 100px;"></div>
                    </div>
                </div>
                
                {{-- Error Message --}}
                <div x-show="error" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                    <span x-text="error"></span>
                </div>
                
                {{-- Success Message --}}
                <div x-show="result" class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-700 font-medium">{{ __('Barcode terdeteksi:') }}</p>
                    <p class="text-lg font-mono text-green-900" x-text="result"></p>
                </div>
                
                {{-- Instructions --}}
                <p x-show="!error && !result" class="mt-4 text-sm text-gray-500 text-center">
                    {{ __('Arahkan kamera ke barcode produk') }}
                </p>
            </div>
        </div>
    </div>
</div>
