<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title') - Klinik Winardi</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('style.css') }}">
    </head>

    <body class="bg-gray-50 flex h-screen overflow-hidden" x-data="{ sidebarOpen: $persist(true).as('sidebar_status') }">

        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            @include('layouts.navbar')

            <main class="p-8 bg-teal-50 flex-1 overflow-y-auto">
                @yield('content')
            </main>
        </div>

        <script defer src="https://unpkg.com/@alpinejs/persist@3.x.x/dist/cdn.min.js">
            //buat menyimpan status sidebar (buka/tutup) di localStorage sehingga tetap konsisten saat user refresh halaman atau kembali ke halaman lain
        </script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js">
            // Untuk Alpine.js (Toggle sidebar & pop-up notifikasi)
        </script>

        <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.3/cdn.min.js"
        integrity="sha512-Yw9G2Y9ZAtb68p8FvK0Bdfa7wSgS6fXm99wbe6Yg00gGZfeG7S81U0d869bOOfm9p3e7gO0O5nS9R/pDOnYk9w=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer">// Untuk Alpine.js (Pop-up notifikasi)</script>
        <script src="//unpkg.com/alpinejs" defer></script>
        <script> // Untuk pop-up konfirmasi saat user ingin meninggalkan halaman form tanpa menyimpan
            // Ambil form dan semua input di dalamnya
            const form = document.querySelector('form');
            let formChanged = false;

            // Pantau jika ada perubahan pada input, select, atau textarea
            form.addEventListener('change', () => {
                formChanged = true;
            });

            // 1. Pop-up untuk tombol "Batal" (Manual)
            const btnBatal = document.querySelector('a[href*="index"]'); // Mencari link yang menuju halaman daftar
            if (btnBatal) {
                btnBatal.addEventListener('click', function(e) {
                    if (formChanged) {
                        const konfirmasi = confirm("Apakah Anda tidak ingin melanjutkan pengisian? Data yang sudah diisi akan hilang.");
                        if (!konfirmasi) {
                            e.preventDefault(); // Batalkan perpindahan halaman jika user pilih 'Cancel'
                        }
                    }
                });
            }

            // 2. Pop-up saat user mencoba menutup tab atau refresh browser (Otomatis)
            window.addEventListener('beforeunload', (e) => {
                if (formChanged) {
                    e.preventDefault();
                    e.returnValue = ''; // Standar browser modern untuk memicu dialog konfirmasi
                }
            });

            // 3. Jika form disubmit (Simpan), kita tidak perlu munculkan pop-up
            form.addEventListener('submit', () => {
                formChanged = false;
            });
        </script>

    </body>

</html>
