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

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>

    <body
        class="bg-gray-50 flex h-screen overflow-hidden"
        x-data="sidebarController()"
        x-init="init()"
        @resize.window="handleResize()"
        @keydown.escape.window="closeSidebar()"
    >
        @include('layouts.sidebar')

        {{-- OVERLAY KHUSUS MOBILE --}}
        <div
            x-cloak
            x-show="sidebarOpen && isMobile"
            x-transition.opacity
            @click="closeSidebar()"
            class="fixed inset-0 bg-black/40 z-40 md:hidden"
            aria-hidden="true"
        ></div>

        <div class="flex-1 flex flex-col h-screen overflow-hidden relative z-0">
            @include('layouts.navbar')

            <main class="p-8 bg-teal-50 flex-1 overflow-y-auto">
                @yield('content')
            </main>
        </div>

        <script>
            function sidebarController() {
                return {
                    isMobile: window.innerWidth < 768,
                    sidebarOpen: true,

                    init() {
                        const saved = localStorage.getItem('sidebar_status');

                        if (this.isMobile) {
                            // Di mobile selalu mulai dalam keadaan tertutup.
                            this.sidebarOpen = false;
                        } else {
                            // Di desktop gunakan status terakhir.
                            this.sidebarOpen = saved === null ? true : saved === 'true';
                        }
                    },

                    toggleSidebar() {
                        this.sidebarOpen = !this.sidebarOpen;

                        // Simpan hanya state desktop agar mobile tidak ikut mewarisi sidebar terbuka.
                        if (!this.isMobile) {
                            localStorage.setItem('sidebar_status', this.sidebarOpen);
                        }
                    },

                    openSidebar() {
                        this.sidebarOpen = true;

                        if (!this.isMobile) {
                            localStorage.setItem('sidebar_status', 'true');
                        }
                    },

                    closeSidebar() {
                        this.sidebarOpen = false;

                        if (!this.isMobile) {
                            localStorage.setItem('sidebar_status', 'false');
                        }
                    },

                    handleResize() {
                        const mobileNow = window.innerWidth < 768;

                        if (mobileNow !== this.isMobile) {
                            this.isMobile = mobileNow;

                            if (mobileNow) {
                                // Saat masuk ukuran mobile, otomatis tutup.
                                this.sidebarOpen = false;
                            } else {
                                // Saat kembali ke desktop, ambil state terakhir.
                                const saved = localStorage.getItem('sidebar_status');
                                this.sidebarOpen = saved === null ? true : saved === 'true';
                            }
                        }
                    }
                };
            }
        </script>

        {{-- Alpine Persist tidak lagi diperlukan karena state sidebar dikelola manual.
             Ini juga menghindari bentrokan akibat beberapa instance Alpine. --}}
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script>
            // Konfirmasi saat user meninggalkan halaman form yang belum disimpan.
            const form = document.querySelector('form');
            let formChanged = false;

            if (form) {
                form.addEventListener('change', () => {
                    formChanged = true;
                });

                const btnBatal = document.querySelector('a[href*="index"]');

                if (btnBatal) {
                    btnBatal.addEventListener('click', function(e) {
                        if (formChanged) {
                            const konfirmasi = confirm(
                                "Apakah Anda tidak ingin melanjutkan pengisian? Data yang sudah diisi akan hilang."
                            );

                            if (!konfirmasi) {
                                e.preventDefault();
                            }
                        }
                    });
                }

                form.addEventListener('submit', () => {
                    formChanged = false;
                });
            }

            window.addEventListener('beforeunload', (e) => {
                if (formChanged) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });
        </script>
    </body>
</html>
 