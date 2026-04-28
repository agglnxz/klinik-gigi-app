<aside class="w-64 bg-white h-screen border-r border-gray-100 hidden md:block" style="background-color: #FAFAFA">
    <div class="p-6">
        <h1 class="text-xl font-bold text-[#1b5e4b]">Klinik Winardi</h1>
        <p class="text-[10px] text-gray-400 uppercase tracking-tight">Sistem Manajemen Protesa</p>
    </div>

    <nav class="mt-4 px-4">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center p-3 rounded-lg transition {{ request()->routeIs('dashboard') ? 'text-[#1b5e4b] bg-white-50 font-medium shadow-sm border border-gray-200' : 'text-gray-500 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-table-columns mr-3"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('dokter.index') }}"
       class="flex items-center p-3 rounded-lg transition {{ request()->is('data-master*') ? 'text-[#1b5e4b] bg-teal-50 font-medium' : 'text-gray-500 hover:bg-gray-50' }}">
                    <i class="fa-solid fa-database mr-3"></i> Data Master
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-3 text-gray-500 hover:bg-gray-50 rounded-lg transition">
                    <i class="fa-solid fa-users mr-3"></i> Pasien
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-3 text-gray-500 hover:bg-gray-50 rounded-lg transition">
                    <i class="fa-solid fa-square-plus mr-3"></i> Pemeriksaan
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-3 text-gray-500 hover:bg-gray-50 rounded-lg transition">
                    <i class="fa-solid fa-cart-shopping mr-3"></i> Pemesanan
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-3 text-gray-500 hover:bg-gray-50 rounded-lg transition">
                    <i class="fa-solid fa-clock-rotate-left mr-3"></i> Riwayat Pesanan
                </a>
            </li>
        </ul>
    </nav>
</aside>
