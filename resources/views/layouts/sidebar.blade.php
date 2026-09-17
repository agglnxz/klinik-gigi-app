<aside x-data="{ hoverSidebar: false }" @mouseenter="hoverSidebar = true" @mouseleave="hoverSidebar = false"
    class="bg-white h-screen border-r border-gray-100 flex flex-col justify-between flex-shrink-0 fixed md:static inset-y-0 left-0 shadow-sm overflow-y-auto z-50 transform transition-all duration-300 ease-in-out no-print"
    :class="isMobile
        ?
        (sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-64') :
        ((sidebarOpen || hoverSidebar) ? 'w-64 translate-x-0' : 'w-20 translate-x-0')"
    style="background-color: #FAFAFA">
    <div class="min-w-0">
        {{-- HEADER SIDEBAR --}}
        <div class="h-[93px] px-4 border-b border-gray-50 flex items-center overflow-hidden">
            <div class="w-full min-w-0">
                <div class="flex items-center"
                    :class="(sidebarOpen || hoverSidebar) ? 'justify-start' : 'justify-center'">
                    <div class="text-[#1b5e4b] font-bold whitespace-nowrap transition-all duration-200"
                        :class="(sidebarOpen || hoverSidebar) ? 'text-xl opacity-100' : 'text-sm opacity-100'">
                        <span x-cloak x-show="sidebarOpen || hoverSidebar">Klinik Winardi</span>
                        <span x-cloak x-show="!sidebarOpen && !hoverSidebar">
                            <i class="fa-solid fa-tooth text-xl"></i>
                        </span>
                    </div>
                </div>

                <p x-cloak x-show="sidebarOpen || hoverSidebar" x-transition.opacity
                    class="text-[10px] text-gray-400 uppercase tracking-tight mt-1 whitespace-nowrap">
                    Sistem Manajemen Protesa
                </p>
            </div>
        </div>

        @php
            $baseClass = 'flex items-center p-3 rounded-xl transition duration-200';
            $activeClass = 'text-[#1b5e4b] bg-white font-semibold shadow-sm border border-gray-100';
            $inactiveClass = 'text-gray-500 hover:text-[#1b5e4b] hover:bg-teal-50/50';
        @endphp

        {{-- NAVIGASI --}}
        <nav class="mt-4 px-3">
            <ul class="space-y-1.5">

                {{-- DASHBOARD --}}
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="{{ $baseClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}"
                        :class="(sidebarOpen || hoverSidebar) ? 'justify-start px-4' : 'justify-center'"
                        title="Dashboard">
                        <i class="fa-solid fa-table-columns w-6 text-center text-lg flex-shrink-0"
                            :class="(sidebarOpen || hoverSidebar) ? 'mr-3' : ''"></i>
                        <span x-cloak x-show="sidebarOpen || hoverSidebar" x-transition.opacity
                            class="whitespace-nowrap text-sm">
                            Dashboard
                        </span>
                    </a>
                </li>

                {{-- ADMIN --}}
                @if (auth()->user()->role === 'Admin')
                    <li>
                        <a href="{{ route('dokter.index') }}"
                            class="{{ $baseClass }} {{ request()->routeIs('dokter.*') || request()->is('data-master*') ? $activeClass : $inactiveClass }}"
                            :class="(sidebarOpen || hoverSidebar) ? 'justify-start px-4' : 'justify-center'"
                            title="Data Master">
                            <i class="fa-solid fa-database w-6 text-center text-lg flex-shrink-0"
                                :class="(sidebarOpen || hoverSidebar) ? 'mr-3' : ''"></i>
                            <span x-cloak x-show="sidebarOpen || hoverSidebar" x-transition.opacity
                                class="whitespace-nowrap text-sm">
                                Data Master
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('pasien.index') }}"
                            class="{{ $baseClass }} {{ request()->routeIs('pasien.*') ? $activeClass : $inactiveClass }}"
                            :class="(sidebarOpen || hoverSidebar) ? 'justify-start px-4' : 'justify-center'"
                            title="Pasien">
                            <i class="fa-solid fa-users w-6 text-center text-lg flex-shrink-0"
                                :class="(sidebarOpen || hoverSidebar) ? 'mr-3' : ''"></i>
                            <span x-cloak x-show="sidebarOpen || hoverSidebar" x-transition.opacity
                                class="whitespace-nowrap text-sm">
                                Pasien
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('pemeriksaan.index') }}"
                            class="{{ $baseClass }} {{ request()->routeIs('pemeriksaan.*') ? $activeClass : $inactiveClass }}"
                            :class="(sidebarOpen || hoverSidebar) ? 'justify-start px-4' : 'justify-center'"
                            title="Pemeriksaan">
                            <i class="fa-solid fa-stethoscope w-6 text-center text-lg flex-shrink-0"
                                :class="(sidebarOpen || hoverSidebar) ? 'mr-3' : ''"></i>
                            <span x-cloak x-show="sidebarOpen || hoverSidebar" x-transition.opacity
                                class="whitespace-nowrap text-sm">
                                Pemeriksaan
                            </span>
                        </a>
                    </li>
                @endif

                {{-- PEMESANAN --}}
                <li>
                    <a href="{{ route('pemesanan.index') }}"
                        class="{{ $baseClass }} {{ request()->routeIs('pemesanan.index') || request()->routeIs('pemesanan.create') || request()->routeIs('pemesanan.edit') ? $activeClass : $inactiveClass }}"
                        :class="(sidebarOpen || hoverSidebar) ? 'justify-start px-4' : 'justify-center'"
                        title="Pemesanan">
                        <i class="fa-solid fa-cart-shopping w-6 text-center text-lg flex-shrink-0"
                            :class="(sidebarOpen || hoverSidebar) ? 'mr-3' : ''"></i>
                        <span x-cloak x-show="sidebarOpen || hoverSidebar" x-transition.opacity
                            class="whitespace-nowrap text-sm">
                            Pemesanan
                        </span>
                    </a>
                </li>

                {{-- RIWAYAT --}}
                <li>
                    <a href="{{ route('pemesanan-riwayat') }}"
                        class="{{ $baseClass }} {{ request()->routeIs('pemesanan-riwayat') ? $activeClass : $inactiveClass }}"
                        :class="(sidebarOpen || hoverSidebar) ? 'justify-start px-4' : 'justify-center'"
                        title="Riwayat Pemesanan">
                        <i class="fa-solid fa-clock-rotate-left w-6 text-center text-lg flex-shrink-0"
                            :class="(sidebarOpen || hoverSidebar) ? 'mr-3' : ''"></i>
                        <span x-cloak x-show="sidebarOpen || hoverSidebar" x-transition.opacity
                            class="whitespace-nowrap text-sm">
                            Riwayat Pemesanan
                        </span>
                    </a>
                </li>

                {{-- LAPORAN --}}
                <li>
                    <a href="{{ route('laporan.index') }}"
                        class="{{ $baseClass }} {{ request()->routeIs('laporan.*') ? $activeClass : $inactiveClass }}"
                        :class="(sidebarOpen || hoverSidebar) ? 'justify-start px-4' : 'justify-center'"
                        title="Laporan">
                        <i class="fa-solid fa-chart-line w-6 text-center text-lg flex-shrink-0"
                            :class="(sidebarOpen || hoverSidebar) ? 'mr-3' : ''"></i>
                        <span x-cloak x-show="sidebarOpen || hoverSidebar" x-transition.opacity
                            class="whitespace-nowrap text-sm">
                            Laporan
                        </span>
                    </a>
                </li>

                {{-- DIREKTUR --}}
                @if (auth()->user()->role === 'Direktur')
                    <li>
                        <a href="{{ route('pengajuan-hapus.index') }}"
                            class="{{ $baseClass }} {{ request()->routeIs('pengajuan-hapus.*') ? $activeClass : $inactiveClass }}"
                            :class="(sidebarOpen || hoverSidebar) ? 'justify-start px-4' : 'justify-center'"
                            title="Approve">
                            <i class="fa-solid fa-table-cells-large w-6 text-center text-lg flex-shrink-0"
                                :class="(sidebarOpen || hoverSidebar) ? 'mr-3' : ''"></i>
                            <span x-cloak x-show="sidebarOpen || hoverSidebar" x-transition.opacity
                                class="whitespace-nowrap text-sm">
                                Approve
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('users.index') }}"
                            class="{{ $baseClass }} {{ request()->routeIs('users.*') ? $activeClass : $inactiveClass }}"
                            :class="(sidebarOpen || hoverSidebar) ? 'justify-start px-4' : 'justify-center'"
                            title="Data Manajemen User">
                            <i class="fa-solid fa-user-gear w-6 text-center text-lg flex-shrink-0"
                                :class="(sidebarOpen || hoverSidebar) ? 'mr-3' : ''"></i>
                            <span x-cloak x-show="sidebarOpen || hoverSidebar" x-transition.opacity
                                class="whitespace-nowrap text-sm">
                                Manajemen User
                            </span>
                        </a>
                    </li>

                <li>
                    <a href="{{ route('laporan.index') }}"
                        class="{{ $baseClass }} {{ request()->routeIs('laporan.*') ? $activeClass : $inactiveClass }}"
                        :class="(sidebarOpen || hoverSidebar) ? 'justify-start px-4' : 'justify-center'"
                        title="Laporan">
                        <i class="fa-solid fa-chart-line w-6 text-center text-lg flex-shrink-0"
                            :class="(sidebarOpen || hoverSidebar) ? 'mr-3' : ''"></i>
                        <span x-cloak x-show="sidebarOpen || hoverSidebar" x-transition.opacity
                            class="whitespace-nowrap text-sm">
                            Laporan
                        </span>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
    </div>

    {{-- TOGGLE BUTTON --}}
    <div class="p-4 border-t border-gray-100 flex flex-shrink-0"
        :class="(sidebarOpen || hoverSidebar) ? 'justify-end' : 'justify-center'">

        <button @click="toggleSidebar()" type="button"
            class="p-2 text-gray-400 hover:text-[#1b5e4b] hover:bg-gray-100 rounded-xl transition focus:outline-none"
            aria-label="Toggle sidebar">
            <i class="fa-solid text-base" :class="sidebarOpen ? 'fa-angle-left' : 'fa-angle-right'"></i>
        </button>
    </div>
</aside>
