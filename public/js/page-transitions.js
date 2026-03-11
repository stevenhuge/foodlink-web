document.addEventListener('DOMContentLoaded', function() {
    // Tentukan elemen kontainer (untuk animasi fade-in onload)
    let container = document.getElementById('main-content') || 
                    document.getElementById('main-container') || 
                    document.getElementById('auth-form-container') ||
                    document.querySelector('main.container') ||
                    document.querySelector('main') || 
                    document.body;

    if (container && !container.classList.contains('page-transition-enter')) {
        container.classList.add('page-transition-enter');
    }

    // Fungsi global untuk menampilkan Premium Loader
    window.showPremiumLoader = function() {
        let loader = document.getElementById('fl-global-loader');
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'fl-global-loader';
            loader.className = 'fl-loader-overlay';
            loader.innerHTML = `
                <div class="fl-brand-pulse">
                    <img src="/images/logo_foodlink_hijau_tanpa_background.png" alt="Foodlink" style="width: 48px; height: auto; object-fit: contain;">
                </div>
                <div class="fl-loader-text">Loading...</div>
            `;
            document.body.appendChild(loader);
        }
        
        // Memaksa browser merepaint sebelum menambah class
        requestAnimationFrame(() => {
            loader.classList.add('show');
        });
    };

    window.hidePremiumLoader = function() {
        let loader = document.getElementById('fl-global-loader');
        if (loader) {
            loader.classList.remove('show');
        }
    };

    // Tangkap semua link nav
    const internalLinks = document.querySelectorAll('a:not([target="_blank"]):not([href^="#"]):not([href^="javascript"]):not([data-bs-toggle])');

    internalLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const destUrl = this.getAttribute('href');
            
            if (!destUrl || destUrl === '#' || destUrl.trim() === '') return;
            if (e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey) return; 

            e.preventDefault();
            const targetUrl = this.href;

            // Trigger global loading UI
            window.showPremiumLoader();

            // Pindah halaman dengan delay lebih singkat agar terasa ringan
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 100);
        });
    });

    // Tangkap submit form
    const forms = document.querySelectorAll('form:not([target="_blank"])');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity() || e.defaultPrevented) return;
            
            // Trigger global loading UI during form post
            window.showPremiumLoader();
        });
    });
});

// Hide loader jika pengguna menggunakan tombol BACK/FORWARD dari browser
window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
        window.hidePremiumLoader();
    }
});
