// Barcode Scanner Component using html5-qrcode
// Import in app.js: import './barcode-scanner';

import { Html5Qrcode } from 'html5-qrcode';

window.barcodeScanner = function(config = {}) {
    return {
        scanning: false,
        result: null,
        error: null,
        html5QrCode: null,
        cameraId: null,
        
        // Callbacks
        onScan: config.onScan || null,
        onError: config.onError || null,
        
        async init() {
            // Get available cameras
            try {
                const devices = await Html5Qrcode.getCameras();
                if (devices && devices.length) {
                    // Prefer back camera
                    const backCamera = devices.find(d => 
                        d.label.toLowerCase().includes('back') || 
                        d.label.toLowerCase().includes('rear')
                    );
                    this.cameraId = backCamera ? backCamera.id : devices[0].id;
                }
            } catch (err) {
                console.error('Could not get cameras', err);
                this.error = 'Could not access camera';
            }
        },
        
        async startScanning(elementId = 'barcode-reader') {
            if (this.scanning) return;
            
            this.error = null;
            this.result = null;
            
            try {
                this.html5QrCode = new Html5Qrcode(elementId);
                
                const config = {
                    fps: 10,
                    qrbox: { width: 250, height: 100 },
                    aspectRatio: 1.0,
                };
                
                await this.html5QrCode.start(
                    { facingMode: "environment" }, // Use back camera
                    config,
                    (decodedText, decodedResult) => {
                        this.result = decodedText;
                        this.scanning = false;
                        
                        // Play beep sound
                        this.playBeep();
                        
                        // Stop scanning
                        this.stopScanning();
                        
                        // Call callback
                        if (this.onScan) {
                            this.onScan(decodedText);
                        }
                        
                        // Dispatch event for Livewire
                        this.$dispatch('barcode-scanned', { barcode: decodedText });
                    },
                    (errorMessage) => {
                        // Ignore "No QR code found" errors during scanning
                    }
                );
                
                this.scanning = true;
            } catch (err) {
                console.error('Failed to start scanner:', err);
                this.error = err.message || 'Failed to start camera';
                
                if (this.onError) {
                    this.onError(err);
                }
            }
        },
        
        async stopScanning() {
            if (this.html5QrCode && this.scanning) {
                try {
                    await this.html5QrCode.stop();
                    this.html5QrCode.clear();
                } catch (err) {
                    console.error('Failed to stop scanner:', err);
                }
            }
            this.scanning = false;
        },
        
        playBeep() {
            // Create a simple beep sound
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = 1000;
            oscillator.type = 'sine';
            gainNode.gain.value = 0.3;
            
            oscillator.start();
            setTimeout(() => {
                oscillator.stop();
                audioContext.close();
            }, 150);
        },
        
        // Clean up when component is destroyed
        destroy() {
            this.stopScanning();
        }
    };
};

// Make it available globally for Alpine.js
document.addEventListener('alpine:init', () => {
    Alpine.data('barcodeScanner', window.barcodeScanner);
});
