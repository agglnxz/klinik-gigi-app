<aside class="bg-white h-screen border-r border-gray-100 flex flex-col justify-between transition-all duration-300 flex-shrink-0 z-50 fixed md:static inset-y-0 left-0 shadow-sm overflow-y-auto"
       :class="sidebarOpen ? 'flex w-64' : 'hidden md:flex w-20'"
       style="background-color: #FAFAFA">

    <div>
        <div class="p-6 overflow-hidden whitespace-nowrap border-b border-gray-50">
            <h1 class="font-bold text-[#1b5e4b] transition-all duration-300 flex items-center"
                :class="sidebarOpen ? 'text-xl justify-start' : 'text-sm justify-center'">
                <span x-show="sidebarOpen" style="display: none;" class="font-bold">Klinik Winardi</span>
                <span x-show="!sidebarOpen"><i class="fa-solid fa-tooth text-xl"></i></span>
            </h1>
            <p x-show="sidebarOpen" class="text-[10px] text-gray-400 uppercase tracking-tight mt-1" style="display: none;">Sistem Manajemen Protesa</p>
        </div>

        {{-- KUMPULAN VARIABEL STYLE --}}
        @php
            $baseClass     = "flex items-center p-3 rounded-xl transition duration-200 ";
            $activeClass   = "text-[#1b5e4b] bg-white font-semibold shadow-sm border border-gray-100";
            $inactiveClass = "text-gray-500 hover:text-[#1b5e4b] hover:bg-teal-50/50";
        @endphp

        <nav class="mt-4 px-3">
            <ul class="space-y-1.5">
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="{{ $baseClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}"
                       :class="sidebarOpen ? 'justify-start px-4' : 'justify-center'"
                       title="Dashboard">
                        <i class="fa-solid fa-table-columns w-6 text-center text-lg" :class="sidebarOpen ? 'mr-3' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap text-sm" style="display: none;">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('dokter.index') }}"
                       class="{{ $baseClass }} {{ request()->routeIs('dokter.*') || request()->is('data-master*') ? $activeClass : $inactiveClass }}"
                       :class="sidebarOpen ? 'justify-start px-4' : 'justify-center'"
                       title="Data Master">
                        <i class="fa-solid fa-database w-6 text-center text-lg" :class="sidebarOpen ? 'mr-3' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap text-sm" style="display: none;">Data Master</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pasien.index') }}"
                       class="{{ $baseClass }} {{ request()->routeIs('pasien.*') ? $activeClass : $inactiveClass }}"
                       :class="sidebarOpen ? 'justify-start px-4' : 'justify-center'"
                       title="Pasien">
                        <i class="fa-solid fa-users w-6 text-center text-lg" :class="sidebarOpen ? 'mr-3' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap text-sm" style="display: none;">Pasien</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pemeriksaan.index') }}"
                       class="{{ $baseClass }} {{ request()->routeIs('pemeriksaan.*') ? $activeClass : $inactiveClass }}"
                       :class="sidebarOpen ? 'justify-start px-4' : 'justify-center'"
                       title="Pemeriksaan">
                        <i class="fa-solid fa-stethoscope w-6 text-center text-lg" :class="sidebarOpen ? 'mr-3' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap text-sm" style="display: none;">Pemeriksaan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pemesanan.index') }}"
                       class="{{ $baseClass }} {{ request()->routeIs('pemesanan.index') || request()->routeIs('pemesanan.create') || request()->routeIs('pemesanan.edit') ? $activeClass : $inactiveClass }}"
                       :class="sidebarOpen ? 'justify-start px-4' : 'justify-center'"
                       title="Pemesanan">
                        <i class="fa-solid fa-cart-shopping w-6 text-center text-lg" :class="sidebarOpen ? 'mr-3' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap text-sm" style="display: none;">Pemesanan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pemesanan-riwayat') }}"
                       class="{{ $baseClass }} {{ request()->routeIs('pemesanan-riwayat') ? $activeClass : $inactiveClass }}"
                       :class="sidebarOpen ? 'justify-start px-4' : 'justify-center'"
                       title="Riwayat Pemesanan">
                        <i class="fa-solid fa-clock-rotate-left w-6 text-center text-lg" :class="sidebarOpen ? 'mr-3' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap text-sm" style="display: none;">Riwayat Pemesanan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pengajuan-hapus.index') }}"
                       class="{{ $baseClass }} {{ request()->routeIs('pengajuan-hapus.*') ? $activeClass : $inactiveClass }}"
                       :class="sidebarOpen ? 'justify-start px-4' : 'justify-center'"
                       title="Approve">
                        <i class="fa-solid fa-table-cells-large w-6 text-center text-lg" :class="sidebarOpen ? 'mr-3' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap text-sm" style="display: none;">Approve</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('users.index') }}"
                       class="{{ $baseClass }} {{ request()->routeIs('users.*') ? $activeClass : $inactiveClass }}"
                       :class="sidebarOpen ? 'justify-start px-4' : 'justify-center'"
                       title="Data Manajemen User">
                        <i class="fa-solid fa-user-gear w-6 text-center text-lg" :class="sidebarOpen ? 'mr-3' : ''"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap text-sm" style="display: none;">Manajemen User</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="p-4 border-t border-gray-100 flex flex-shrink-0" :class="sidebarOpen ? 'justify-end' : 'justify-center'">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-400 hover:text-[#1b5e4b] hover:bg-gray-100 rounded-xl transition focus:outline-none">
            <i class="fa-solid text-base" :class="sidebarOpen ? 'fa-angle-left' : 'fa-angle-right'"></i>
        </button>
    </div>
</aside>
