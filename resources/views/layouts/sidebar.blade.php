<aside
    class="bg-white h-screen border-r border-gray-100 hidden md:flex flex-col justify-between transition-all duration-300 flex-shrink-0"
    :class="sidebarOpen ? 'w-64' : 'w-20'"
    style="background-color: #FAFAFA">

    <div>
        <div class="p-6 overflow-hidden whitespace-nowrap">
            <h1 class="text-xl font-bold text-[#1b5e4b] transition-all duration-300"
                :class="sidebarOpen ? '' : 'text-center text-sm'">
                <span x-show="sidebarOpen">Klinik Winardi</span>
                <span x-show="!sidebarOpen"><i class="fa-solid fa-tooth"></i></span>
            </h1>
            <p x-show="sidebarOpen" class="text-[10px] text-gray-400 uppercase tracking-tight mt-1">Sistem Manajemen Protesa</p>
        </div>

        {{-- KUMPULAN VARIABEL STYLE --}}
        @php
            $baseClass     = "flex items-center p-3 rounded-md transition duration-200 ";
            $activeClass   = "text-[#1b5e4b] bg-white font-medium shadow-md";
            $inactiveClass = "text-gray-500 hover:bg-gray-50";
        @endphp

        <nav class="mt-4 px-4">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="{{ $baseClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }} :class="sidebarOpen ? '' : 'justify-center'"
                       title="Dashboard">
                        <i class="fa-solid fa-table-columns w-6 text-center text-lg :class="sidebarOpen ? 'mr-2' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('dokter.index') }}"
                       class="{{ $baseClass }} {{ request()->is('data-master*') ? $activeClass : $inactiveClass }}"
                       :class="sidebarOpen ? '' : 'justify-center'"
                       title="Data Master">
                        <i class="fa-solid fa-database w-6 text-center text-lg :class="sidebarOpen ? 'mr-2' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Data Master</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pasien.index') }}"
                       class="{{ $baseClass }} {{ request()->is('pasien*') ? $activeClass : $inactiveClass }}"
                       :class="sidebarOpen ? '' : 'justify-center'"
                       title="Pasien">
                        <i class="fa-solid fa-users w-6 text-center text-lg :class="sidebarOpen ? 'mr-2' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Pasien</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pemeriksaan.index') }}"
                       class="{{ $baseClass }} {{ request()->is('pemeriksaan*') ? $activeClass : $inactiveClass }}"
                       :class="sidebarOpen ? '' : 'justify-center'"
                       title="Pemeriksaan">
                        <i class="fa-solid fa-stethoscope w-6 text-center text-lg :class="sidebarOpen ? 'mr-2' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Pemeriksaan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pemesanan.index') }}"
                       class="{{ $baseClass }} {{ request()->is('pemesanan*') ? $activeClass : $inactiveClass }}"
                       :class="sidebarOpen ? '' : 'justify-center'"
                       title="Pemesanan">
                        <i class="fa-solid fa-cart-shopping w-6 text-center text-lg :class="sidebarOpen ? 'mr-2' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Pemesanan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pemesanan-riwayat') }}"
                       class="{{ $baseClass }} {{ request()->is('riwayat-pemesanan*') ? $activeClass : $inactiveClass }}"
                       :class="sidebarOpen ? '' : 'justify-center'"
                       title="Riwayat Pemesanan">
                        <i class="fa-solid fa-clock-rotate-left w-6 text-center text-lg :class="sidebarOpen ? 'mr-2' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap">Riwayat Pemesanan</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="p-4 border-t border-gray-100 flex" :class="sidebarOpen ? 'justify-end' : 'justify-center'">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-400 hover:text-[#1b5e4b] hover:bg-gray-100 rounded-md transition focus:outline-none">
            <i class="fa-solid text-base" :class="sidebarOpen ? 'fa-angle-left' : 'fa-angle-right'"></i>
        </button>
    </div>
</aside>
