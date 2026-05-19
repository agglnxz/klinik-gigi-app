<nav class="bg-white p-4 flex justify-end items-center w-full">
    {{-- <div class="relative w-96">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
            <i class="fa-solid fa-magnifying-glass text-sm"></i>
        </span>
        <input type="text" placeholder="Cari data..." class="w-full pl-10 pr-4 py-2 bg-gray-100 border-none rounded-md focus:ring-1 focus:ring-teal-500 text-sm">
    </div> --}}

    <div class="flex items-center space-x-4">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="relative focus:outline-none flex items-center">
                <i class="fa-regular fa-bell text-xl text-gray-500 hover:text-teal-600 transition"></i>
                <span class="absolute -top-1 -right-1 bg-[#176851] text-white text-[10px] rounded-full h-4 w-4 flex items-center justify-center border-2 border-white font-bold">
                    4
                </span>
            </button>

            <div x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 mt-4 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-[999] overflow-hidden"
                style="display: none;">

                <div class="px-5 py-3 border-b border-gray-50 flex justify-between bg-white">
                    <span class="text-center text-[10px] font-bold text-black-1000 uppercase tracking-widest">4 Notifications</span>
                </div>

                <hr class="mx-auto rounded-md" style="border: 1px solid #F3F3F3; width: 100%;"></hr>

                <div class="max-h-[350px] overflow-y-auto custom-scrollbar">
                    <div class="p-4 border-b border-gray-50 hover:bg-gray-50 transition cursor-pointer flex gap-4 bg-[#E9EBE9] rounded-md" style="margin: 10px">
                        <div class="w-9 h-9 bg-teal-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-clock text-teal-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-black-100">Pemesanan atas nama Yossy</p>
                            <p class="text-[11px] text-green-500 mt-0.5">akan segera tiba 3 hari lagi.</p>
                            <p class="text-[9px] text-gray-400 mt-1.5 font-bold uppercase tracking-tighter">2 menit yang lalu</p>
                        </div>
                    </div>

                    <div class="p-4 border-b border-gray-50 hover:bg-gray-100 transition cursor-pointer flex gap-4 bg-[#E9D9D9] rounded-md" style="margin: 10px">
                        <div class="w-9 h-9 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-clock text-red-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-800">Pemesanan atas nama Inandiar</p>
                            <p class="text-[11px] text-red-500 mt-0.5 font-medium">terlambat 1 hari</p>
                            <p class="text-[9px] text-gray-400 mt-1.5 font-bold uppercase tracking-tighter">15 menit yang lalu</p>
                        </div>
                    </div>

                    <div class="p-4 border-b border-gray-50 hover:bg-gray-50 transition cursor-pointer flex gap-4 bg-[#E9EBE9] rounded-md" style="margin: 10px">
                        <div class="w-9 h-9 bg-teal-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-clock text-teal-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-800">Pemesanan atas nama Galang</p>
                            <p class="text-[11px] text-green-500 mt-0.5">Akan segera tiba 1 hari lagi</p>
                            <p class="text-[9px] text-gray-400 mt-1.5 font-bold uppercase tracking-tighter">1 jam yang lalu</p>
                        </div>
                    </div>

                    <div class="p-4 border-b border-gray-50 hover:bg-gray-100 transition cursor-pointer flex gap-4 bg-[#E9D9D9] rounded-md" style="margin: 10px">
                        <div class="w-9 h-9 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-clock text-red-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-800">Pemesanan atas nama Firman</p>
                            <p class="text-[11px] text-red-500 mt-0.5 font-medium">terlambat 1 hari</p>
                            <p class="text-[9px] text-gray-400 mt-1.5 font-bold uppercase tracking-tighter">15 menit yang lalu</p>
                        </div>
                    </div>

                    <div class="p-4 border-b border-gray-50 hover:bg-gray-50 transition cursor-pointer flex gap-4 bg-[#E9EBE9] rounded-md" style="margin: 10px">
                        <div class="w-9 h-9 bg-teal-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-clock text-teal-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-800">Pemesanan atas nama Oky</p>
                            <p class="text-[11px] text-green-500 mt-0.5">Akan segera tiba 1 hari lagi</p>
                            <p class="text-[9px] text-gray-400 mt-1.5 font-bold uppercase tracking-tighter">1 jam yang lalu</p>
                        </div>
                    </div>
                </div>

                <a href="#" class="block py-3 text-center text-[10px] font-bold text-teal-700 bg-gray-50 hover:bg-teal-50 transition uppercase tracking-widest border-t border-gray-100">
                    Lihat Semua Notifikasi
                </a>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/user-profile-avatar.jpg') }}" class="w-8 h-8 rounded-xl object-cover border border-gray-100 shadow-sm" alt="Profile">
        </div>
    </div>
</nav>
