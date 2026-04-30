<aside class="w-64 bg-white h-screen border-r border-gray-100 hidden md:block" style="background-color: #FAFAFA">
    <div class="p-6">
        <h1 class="text-xl font-bold text-[#1b5e4b]">Klinik Winardi</h1>
        <p class="text-[10px] text-gray-400 uppercase tracking-tight">Sistem Manajemen Protesa</p>
    </div>

    {{-- KUMPULAN VARIABEL STYLE AGAR RAPI & REUSABLE --}}
    @php
        $baseClass     = "flex items-center p-3 rounded-lg transition ";
        $activeClass   = "text-[#1b5e4b] bg-white font-medium shadow-[0_1px_2px_rgba(0,0,0,0.05)]";
        $inactiveClass = "text-gray-500 hover:bg-gray-50";
    @endphp

    <nav class="mt-4 px-4">
        <ul class="space-y-2">

            <li>
                <a href="{{ route('dashboard') }}"
                   class="{{ $baseClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
                    <i class="fa-solid fa-table-columns w-6 text-center mr-2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('dokter.index') }}"
                   class="{{ $baseClass }} {{ request()->is('data-master*') ? $activeClass : $inactiveClass }}">
                    <i class="fa-solid fa-database w-6 text-center mr-2"></i>
                    <span>Data Master</span>
                </a>
            </li>

            <li>
                <a href="{{ route('pasien.index') }}"
                   class="{{ $baseClass }} {{ request()->is('pasien*') ? $activeClass : $inactiveClass }}">
                    <i class="fa-solid fa-users w-6 text-center mr-2"></i>
                    <span>Pasien</span>
                </a>
            </li>

            <li>
                <a href="{{ route('pemeriksaan.index') }}"
                   class="{{ $baseClass }} {{ request()->is('pemeriksaan*') ? $activeClass : $inactiveClass }}">
                    <i class="fa-solid fa-stethoscope w-6 text-center mr-2"></i>
                    <span>Pemeriksaan</span>
                </a>
            </li>

            <li>
                <a href="{{ route('pemesanan.index') }}"
                   class="{{ $baseClass }} {{ request()->is('pemesanan*') ? $activeClass : $inactiveClass }}">
                    <i class="fa-solid fa-cart-shopping w-6 text-center mr-2"></i>
                    <span>Pemesanan</span>
                </a>
            </li>

            <li>
                <a href="{{ route('pemesanan-riwayat') }}"
                   class="{{ $baseClass }} {{ request()->is('riwayat-pemesanan*') ? $activeClass : $inactiveClass }}">
                    <i class="fa-solid fa-clock-rotate-left w-6 text-center mr-2"></i>
                    <span>Riwayat Pemesanan</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
