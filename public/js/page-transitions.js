document.addEventListener('DOMContentLoaded', function() {
    // Tentukan elemen kontainer yang akan di-animasikan
    // Prioritas: elemen dengan id="main-content" (Admin), "main-container" (Mitra), "auth-form-container" (Auth), atau <main>, atau <body>
    let container = document.getElementById('main-content') || 
                    document.getElementById('main-container') || 
                    document.getElementById('auth-form-container') ||
                    document.querySelector('main.container') ||
                    document.querySelector('main') || 
                    document.body;

    // Pastikan container memiliki kelas animasi masuk
    if (container && !container.classList.contains('page-transition-enter')) {
        container.classList.add('page-transition-enter');
    }

    // Tangkap semua link intern yang BUKAN untuk Modal atau anchoring halaman yang sama
    const internalLinks = document.querySelectorAll('a:not([target="_blank"]):not([href^="#"]):not([href^="javascript"]):not([data-bs-toggle])');

    internalLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const destUrl = this.getAttribute('href');
            
            // Cegah error
            if (!destUrl || destUrl === '#' || destUrl.trim() === '') return;
            // Jika ada event listener lain yang sudah mencegah klik, biarkan (contoh: validasi form onsubmit yg pakai a tag)
            if (e.defaultPrevented) return;
            // Cegah transisi jika pengguna menahan Ctrl/Cmd untuk buka tab baru
            if (e.ctrlKey || e.metaKey || e.shiftKey) return; 

            e.preventDefault();
            const targetUrl = this.href;

            if(container) {
                // Tambahkan class animasi keluar
                container.classList.remove('page-transition-enter');
                container.classList.add('page-transition-exit');

                // Pindah halaman setelah animasi keluar selesai (tergantung dr durasi CSS di page-transitions.css)
                setTimeout(() => {
                    window.location.href = targetUrl;
                }, 300);
            } else {
                window.location.href = targetUrl;
            }
        });
    });

    // Tangkap submit form agar pas loading form juga ada animasi keluarnya
    const forms = document.querySelectorAll('form:not([target="_blank"])');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Biarkan native HTML5 validation tetap jalan
            if (!form.checkValidity()) return;
            if (e.defaultPrevented) return;

            // Jika form valid dan disubmit
            if(container) {
                container.classList.remove('page-transition-enter');
                container.classList.add('page-transition-exit');
                
                // Jangan panggil preventDefault atau setTimeout. 
                // Biarkan native browser POST/GET. Animasi exit akan jalan sembari browser ngirim request.
            }
        });
    });
});
