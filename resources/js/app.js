import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import Swal from 'sweetalert2';

window.Swal = Swal;

Alpine.plugin(collapse);
Alpine.plugin(focus);

// --- GLOBAL MOCK DATA ---
const MOCK_DATA = {
    user: {
        id: 1,
        firstname: 'Alex',
        lastname: 'Pistol',
        displayName: 'Alex_Pistol',
        handle: 'alexpistol_pro',
        email: 'alex@easyesport.com',
        avatar_url: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Alex',
        bio: 'Competitive Valorant player | Team Lead @NeonVibe',
        total_trophies: 12,
        total_matches: 450,
        wins: 350,
        win_rate: 78,
        status: 'in_team',
        rank: 'Diamond III'
    },
    tournaments: [
        { id: 1, name: 'Valorant Premier League', game: 'Valorant', prize: '$5,000', participants: 48, max_participants: 64, status: 'pending', starts_in: '2d 14h' },
        { id: 2, name: 'CS2 Masters Series', game: 'CS2', prize: '$2,500', participants: 32, max_participants: 32, status: 'ongoing', starts_in: 'Live' },
        { id: 3, name: 'League of Legends Cup', game: 'LoL', prize: '$1,000', participants: 16, max_participants: 32, status: 'finished', starts_in: 'Ended' }
    ],
    matches: [
        { id: 1, opponent: 'Team Liquid', time: '2h ago', score: '13 - 11', status: 'win' },
        { id: 2, opponent: 'G2 Esports', time: '1d ago', score: '10 - 13', status: 'loss' },
        { id: 3, opponent: 'Vitality', time: '3d ago', score: '15 - 13', status: 'win' }
    ]
};

// --- GLOBAL ALPINE STORE ---
document.addEventListener('alpine:init', () => {
    
    Alpine.store('global', {
        darkMode: localStorage.getItem('darkMode') === 'true' || true,
        sidebarOpen: false,
        toasts: [],
        user: MOCK_DATA.user,
        tournaments: MOCK_DATA.tournaments,
        matches: MOCK_DATA.matches,

        init() {
            this.applyTheme();
        },

        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
            this.applyTheme();
        },

        applyTheme() {
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },

        notify(message, type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, message, type });
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 5000);
        }
    });

    // --- OCR SCANNER COMPONENT ---
    Alpine.data('ocrScanner', () => ({
        isScanning: false,
        isComplete: false,
        progress: 0,
        result: null,
        previewUrl: null,
        file: null,

        handleFile(e) {
            const file = e.type === 'drop' ? e.dataTransfer.files[0] : e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                this.file = file;
                const reader = new FileReader();
                reader.onload = (e) => this.previewUrl = e.target.result;
                reader.readAsDataURL(file);
            }
        },

        async startScan() {
            if (!this.file) return;

            this.isScanning = true;
            this.progress = 0;
            this.isComplete = false;
            
            // Artificial progress for better UX
            let interval = setInterval(() => {
                if (this.progress < 90) {
                    this.progress += Math.random() * 15;
                }
            }, 300);

            try {
                const formData = new FormData();
                formData.append('image', this.file);

                const response = await fetch('/scan-ocr', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await response.json();
                
                clearInterval(interval);
                this.progress = 100;

                if (data.success) {
                    setTimeout(() => {
                        this.isScanning = false;
                        this.isComplete = true;
                        this.result = data.data;
                        Alpine.store('global').notify('OCR Analysis Complete!', 'success');
                    }, 500);
                } else {
                    throw new Error(data.message || 'Scan failed');
                }
            } catch (error) {
                clearInterval(interval);
                this.isScanning = false;
                Alpine.store('global').notify(error.message, 'error');
            }
        },

        async confirmSave() {
            if (!this.result) return;

            try {
                const response = await fetch('/save-ocr', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        rank: this.result.rank,
                        stats: this.result.stats
                    })
                });

                const data = await response.json();

                if (data.success) {
                    Alpine.store('global').notify(data.message, 'success');
                    this.reset();
                } else {
                    throw new Error(data.message || 'Save failed');
                }
            } catch (error) {
                Alpine.store('global').notify(error.message, 'error');
            }
        },

        reset() {
            this.previewUrl = null;
            this.isComplete = false;
            this.result = null;
            this.file = null;
            this.progress = 0;
        }
    }));
});

Alpine.start();
