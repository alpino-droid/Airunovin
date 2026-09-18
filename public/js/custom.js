/**
 * ============================================
 * CUSTOM JS - All Pages
 * ============================================
 */

/**
 * ============================================
 * DROPDOWN MENU HANDLER
 * ============================================
 */

/**
 * Memilih item dari dropdown dan mengubah teks tombol
 * @param {HTMLElement} element - Elemen yang diklik
 */
function pilihMenu(element) {
    // Ambil teks dari item yang diklik
    const teks = element.textContent.trim();
    
    // Cari tombol dropdown
    const tombol = document.getElementById('dropdownMenuButton');
    if (!tombol) return;
    
    // Ubah teks tombol dengan teks yang dipilih
    tombol.textContent = teks;
    
    // Tutup dropdown otomatis
    const dropdown = bootstrap.Dropdown.getInstance(tombol);
    if (dropdown) {
        dropdown.hide();
    }
}

/**
 * ============================================
 * PROFIL PAGE HANDLER
 * ============================================
 */

const ProfilManager = {
    // Konfigurasi
    config: {
        maxFileSize: 2 * 1024 * 1024, // 2MB
        allowedTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'],
        defaultImage: '/img/blankPhotoProfile.png'
    },

    // State
    state: {
        initialized: false
    },

    /**
     * Inisialisasi Profil Manager
     */
    init() {
        if (this.state.initialized) return;
        
        this.setupEventListeners();
        this.state.initialized = true;
        
        console.log('✅ Profil Manager initialized');
    },

    /**
     * Setup semua event listeners
     */
    setupEventListeners() {
        // 1. Upload button trigger file input
        const uploadBtn = document.getElementById('uploadBtn');
        const fileInput = document.getElementById('fileInput');
        
        if (uploadBtn && fileInput) {
            uploadBtn.addEventListener('click', function() {
                fileInput.click();
            });
        }

        // 2. File input change handler
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                ProfilManager.handleFileUpload(this);
            });
        }

        // 3. Form submit handler (untuk preview sebelum submit)
        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                // Validasi sebelum submit
                const fileInput = document.getElementById('profile_picture');
                if (fileInput && fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    if (!ProfilManager.validateFile(file)) {
                        e.preventDefault();
                        return false;
                    }
                }
            });
        }

        // 4. Toggle form pengurus (jika ada)
        const radioIya = document.getElementById('radioDefault1');
        if (radioIya) {
            radioIya.addEventListener('change', function() {
                ProfilManager.toggleFormPengurus();
            });
        }

        console.log('✅ Event listeners setup complete');
    },

    /**
     * Handle upload file
     * @param {HTMLInputElement} inputElement - Input file yang berubah
     */
    handleFileUpload(inputElement) {
        const file = inputElement.files[0];
        if (!file) return;

        // Validasi file
        if (!this.validateFile(file)) {
            inputElement.value = '';
            return;
        }

        // Preview gambar
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('profileImage');
            if (img) {
                img.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);

        // Auto submit form setelah upload
        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            // Pindahkan file ke input hidden di form
            const fileInput = document.getElementById('profile_picture');
            if (fileInput) {
                // Clone file list ke input form
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
            }
            
            // Submit form otomatis
            setTimeout(() => {
                profileForm.submit();
            }, 500);
        }
    },

    /**
     * Validasi file
     * @param {File} file - File yang akan divalidasi
     * @returns {boolean} - Valid atau tidak
     */
    validateFile(file) {
        // Validasi tipe file
        if (!this.config.allowedTypes.includes(file.type)) {
            alert('❌ Format file tidak didukung! Gunakan JPG, PNG, atau GIF.');
            return false;
        }

        // Validasi ukuran file
        if (file.size > this.config.maxFileSize) {
            alert('❌ Ukuran file terlalu besar! Maksimal 2MB.');
            return false;
        }

        return true;
    },

    /**
     * Toggle form pengurus
     */
    toggleFormPengurus() {
        const radioIya = document.getElementById('radioDefault1');
        const formPengurus = document.getElementById('formPengurus');
        
        if (!radioIya || !formPengurus) return;
        
        if (radioIya.checked) {
            formPengurus.style.display = 'flex';
        } else {
            formPengurus.style.display = 'none';
        }
    },

    /**
     * Reset form ke default
     */
    resetForm() {
        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            profileForm.reset();
        }

        const fileInput = document.getElementById('profile_picture');
        if (fileInput) {
            fileInput.value = '';
        }

        // Reset gambar ke default
        const img = document.getElementById('profileImage');
        if (img) {
            img.src = this.config.defaultImage;
        }

        console.log('🔄 Form reset');
    },

    /**
     * Get data profil saat ini
     * @returns {Object} - Data profil
     */
    getProfileData() {
        const data = {
            username: document.getElementById('username')?.value || '',
            nama: document.getElementById('nama')?.value || '',
            email: document.getElementById('email')?.value || '',
            phone: document.getElementById('phone')?.value || '',
            province: document.getElementById('province')?.value || '',
            city: document.getElementById('city')?.value || '',
            profilePicture: document.getElementById('profileImage')?.src || ''
        };
        
        return data;
    },

    /**
     * Preview foto sebelum upload
     * @param {string} url - URL gambar
     */
    previewImage(url) {
        const img = document.getElementById('profileImage');
        if (img) {
            img.src = url;
        }
    }
};

/**
 * ============================================
 * CLUB IMAGE PREVIEW HANDLER
 * ============================================
 */

const ClubManager = {
    // Konfigurasi
    config: {
        maxFileSize: 2 * 1024 * 1024, // 2MB
        allowedTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'],
        defaultImage: '/img/images.png'
    },

    // State
    state: {
        initialized: false
    },

    /**
     * Inisialisasi Club Manager
     */
    init() {
        if (this.state.initialized) return;
        
        this.setupEventListeners();
        this.state.initialized = true;
        
        console.log('✅ Club Manager initialized');
    },

    /**
     * Setup semua event listeners
     */
    setupEventListeners() {
        // File input change handler untuk preview logo club
        const fileInput = document.getElementById('fileInputButton');
        const previewImage = document.getElementById('clubLogoPreview');
        
        if (fileInput && previewImage) {
            fileInput.addEventListener('change', function(e) {
                const file = this.files[0];
                if (!file) return;

                // Validasi file
                if (!ClubManager.validateFile(file)) {
                    this.value = '';
                    return;
                }

                // Preview gambar
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImage.src = event.target.result;
                };
                reader.readAsDataURL(file);
            });
        }

        console.log('✅ Club Manager event listeners setup complete');
    },

    /**
     * Validasi file
     * @param {File} file - File yang akan divalidasi
     * @returns {boolean} - Valid atau tidak
     */
    validateFile(file) {
        // Validasi tipe file
        if (!this.config.allowedTypes.includes(file.type)) {
            alert('❌ Format file tidak didukung! Gunakan JPG, PNG, atau GIF.');
            return false;
        }

        // Validasi ukuran file
        if (file.size > this.config.maxFileSize) {
            alert('❌ Ukuran file terlalu besar! Maksimal 2MB.');
            return false;
        }

        return true;
    },

    /**
     * Preview logo club
     * @param {string} url - URL gambar
     */
    previewLogo(url) {
        const preview = document.getElementById('clubLogoPreview');
        if (preview) {
            preview.src = url;
        }
    },

    /**
     * Reset preview ke default
     */
    resetPreview() {
        const preview = document.getElementById('clubLogoPreview');
        if (preview) {
            preview.src = this.config.defaultImage;
        }

        const fileInput = document.getElementById('fileInputButton');
        if (fileInput) {
            fileInput.value = '';
        }
    }
};

/**
 * ============================================
 * EVENT CARD MANAGER
 * ============================================
 */

const EventCardManager = {
    // Konfigurasi
    config: {
        cardCounter: 2,
        maxCards: 3,
        defaultImage: '/img/images.png',
        animationDuration: 300
    },

    // State
    state: {
        initialized: false
    },

    /**
     * Inisialisasi Card Manager
     */
    init() {
        if (this.state.initialized) return;
        
        this.setupEventListeners();
        this.state.initialized = true;
        
        console.log('✅ Event Card Manager initialized');
        console.log(`📊 Total cards: ${this.getCardCount()}`);
    },

    /**
     * Setup semua event listeners
     */
    setupEventListeners() {
        // Tombol tambah card
        const addBtn = document.getElementById('addCardBtn');
        if (addBtn) {
            addBtn.addEventListener('click', () => this.addCard());
        }

        // Upload file handler (delegation)
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-upload')) {
                const id = e.target.dataset.id;
                const fileInput = document.getElementById(`fileInputButton${id}`);
                if (fileInput) {
                    fileInput.click();
                }
            }
        });

        // File input change handler
        document.addEventListener('change', (e) => {
            if (e.target.type === 'file' && e.target.id.startsWith('fileInputButton')) {
                this.handleFileUpload(e.target);
            }
        });
    },

    /**
     * Menambahkan card baru
     */
    addCard() {
        if (this.getCardCount() >= this.config.maxCards) {
            alert(`❌ Maksimal ${this.config.maxCards} card!`);
            return;
        }

        const cardContainer = document.getElementById('cardContainer');
        if (!cardContainer) return;

        const cardDiv = document.createElement('div');
        cardDiv.className = 'col-md-3 mt-5 mb-3 card-item';
        cardDiv.setAttribute('data-card-id', this.config.cardCounter);
        
        cardDiv.innerHTML = `
            <div class="card" style="width: 18rem;">
                <img src="${this.config.defaultImage}" class="card-img-top" alt="Gambar ${this.config.cardCounter}">
                <div class="card-body">
                    <button class="btn btn-outline-primary btn-upload" data-id="${this.config.cardCounter}">
                        <i class="fas fa-upload"></i> Upload File
                    </button>
                    <input type="file" id="fileInputButton${this.config.cardCounter}" class="d-none" accept="image/*">
                    <button class="btn btn-outline-danger btn-sm mt-2 btn-remove" onclick="EventCardManager.removeCard(this)">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        `;

        const addButton = document.getElementById('addCardBtn')?.closest('.col-md-3');
        if (addButton) {
            cardContainer.insertBefore(cardDiv, addButton);
            this.config.cardCounter++;
            this.animateCard(cardDiv, 'add');
        }
    },

    /**
     * Menghapus card
     * @param {HTMLElement} button - Tombol hapus yang diklik
     */
    removeCard(button) {
        const cardItem = button.closest('.card-item');
        if (!cardItem) return;

        if (this.getCardCount() <= 1) {
            alert('⚠️ Minimal harus ada 1 card!');
            return;
        }

        this.animateCard(cardItem, 'remove', () => {
            cardItem.remove();
        });
    },

    /**
     * Handle upload file
     * @param {HTMLInputElement} inputElement - Input file yang berubah
     */
    handleFileUpload(inputElement) {
        const file = inputElement.files[0];
        if (!file) return;

        // Validasi tipe file
        if (!file.type.startsWith('image/')) {
            alert('❌ Hanya file gambar yang diperbolehkan!');
            inputElement.value = '';
            return;
        }

        // Validasi ukuran file (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('❌ Ukuran file maksimal 2MB!');
            inputElement.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            const card = inputElement.closest('.card-item');
            const img = card?.querySelector('.card-img-top');
            if (img) {
                img.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    },

    /**
     * Animasi card
     * @param {HTMLElement} card - Elemen card
     * @param {string} type - 'add' atau 'remove'
     * @param {Function} callback - Fungsi setelah animasi selesai
     */
    animateCard(card, type, callback = null) {
        const duration = this.config.animationDuration;
        
        if (type === 'add') {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.8)';
            card.style.transition = `all ${duration}ms ease`;
            
            // Trigger reflow
            card.offsetHeight;
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
                if (callback) callback();
            }, 50);
        } else if (type === 'remove') {
            card.style.transition = `all ${duration}ms ease`;
            card.style.opacity = '0';
            card.style.transform = 'scale(0.8)';
            
            setTimeout(() => {
                if (callback) callback();
            }, duration);
        }
    },

    /**
     * Reset semua card ke default
     */
    resetCards() {
        const cards = document.querySelectorAll('.card-item');
        if (cards.length === 0) return;

        cards.forEach((card, index) => {
            // Reset gambar
            const img = card.querySelector('.card-img-top');
            if (img) {
                img.src = this.config.defaultImage;
            }
            
            // Reset file input
            const fileInput = card.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.value = '';
            }
        });

        // Reset counter
        this.config.cardCounter = cards.length + 1;
        
        console.log('🔄 Cards reset');
    },

    /**
     * Get semua data card
     * @returns {Array} Array data card
     */
    getCardData() {
        const cards = document.querySelectorAll('.card-item');
        const data = [];
        
        cards.forEach((card, index) => {
            const img = card.querySelector('.card-img-top');
            const fileInput = card.querySelector('input[type="file"]');
            data.push({
                id: index + 1,
                cardId: card.dataset.cardId || 'unknown',
                image: img ? img.src : '',
                hasFile: fileInput && fileInput.files.length > 0,
                fileName: fileInput && fileInput.files[0] ? fileInput.files[0].name : null
            });
        });
        
        return data;
    },

    /**
     * Get jumlah card
     * @returns {number} Jumlah card
     */
    getCardCount() {
        return document.querySelectorAll('.card-item').length;
    },

    /**
     * Update default image
     * @param {string} path - Path gambar baru
     */
    setDefaultImage(path) {
        this.config.defaultImage = path;
    },

    /**
     * Set max cards
     * @param {number} max - Jumlah maksimal card
     */
    setMaxCards(max) {
        this.config.maxCards = max;
    }
};

/**
 * ============================================
 * INISIALISASI SAAT DOM READY
 * ============================================
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Profil Manager (jika ada elemen profil)
    if (document.getElementById('profileForm') || document.getElementById('profileImage')) {
        ProfilManager.init();
        ProfilManager.toggleFormPengurus();
    }

    // ✅ Inisialisasi Club Manager (jika ada elemen club)
    if (document.getElementById('fileInputButton') || document.getElementById('clubLogoPreview')) {
        ClubManager.init();
    }

    // Inisialisasi Event Card Manager (jika ada elemen card legacy dan bukan posterCardContainer)
    if ((document.getElementById('cardContainer') || document.getElementById('addCardBtn')) && !document.getElementById('posterCardContainer')) {
        EventCardManager.init();
        
        // Set default image dari Laravel asset
        const defaultImg = document.querySelector('.card-img-top')?.src;
        if (defaultImg) {
            EventCardManager.setDefaultImage(defaultImg);
        }
    }

    console.log('✅ Application loaded successfully');
});

/**
 * ============================================
 * FUNGSI GLOBAL (untuk inline onclick)
 * ============================================
 */

// Profil functions
window.ProfilManager = ProfilManager;
window.toggleFormPengurus = function() {
    ProfilManager.toggleFormPengurus();
};
window.resetProfilForm = function() {
    ProfilManager.resetForm();
};
window.previewProfileImage = function(url) {
    ProfilManager.previewImage(url);
};

// ✅ Club functions
window.ClubManager = ClubManager;
window.previewClubLogo = function(url) {
    ClubManager.previewLogo(url);
};
window.resetClubPreview = function() {
    ClubManager.resetPreview();
};

// Event Card functions
window.EventCardManager = EventCardManager;
window.pilihMenu = pilihMenu;
window.addCard = () => EventCardManager.addCard();
window.removeCard = (btn) => EventCardManager.removeCard(btn);
window.resetCards = () => EventCardManager.resetCards();
window.getCardData = () => EventCardManager.getCardData();

