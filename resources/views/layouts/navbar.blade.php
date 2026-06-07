<nav class="bg-white p-4 flex justify-between items-center w-full border-b border-gray-100 shadow-sm">

    <div class="flex items-center">
        <button @click="sidebarOpen = !sidebarOpen"
            class="p-2 text-gray-500 hover:text-teal-600 hover:bg-gray-50 rounded-lg transition focus:outline-none">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
    </div>

    <div class="flex items-center space-x-4">
        {{-- ALPINE.JS WRAPPER UNTUK DROPDOWN --}}
        <div class="relative" x-data="{ open: false }">

            {{-- TOMBOL LONCENG --}}
            <button @click="open = !open" class="relative focus:outline-none flex items-center">
                <i class="fa-regular fa-bell text-xl text-gray-500 hover:text-teal-600 transition"></i>
                @if ($unreadCount > 0)
                    <span
                        class="absolute -top-1 -right-1 bg-[#176851] text-white text-[10px] rounded-full h-4 w-4 flex items-center justify-center border-2 border-white font-bold">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </button>

            {{-- KOTAK DROPDOWN MELAYANG --}}
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 mt-4 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-[999] overflow-hidden"
                style="display: none;">

                {{-- HEADER DROPDOWN --}}
                <div class="px-5 py-3 border-b border-gray-50 flex justify-between bg-white">
                    <span class="text-center text-[10px] font-bold text-black-1000 uppercase tracking-widest">
                        {{ $unreadCount }} Notifications Baru
                    </span>
                </div>

                <hr class="mx-auto rounded-md" style="border: 1px solid #F3F3F3; width: 100%;">

                {{-- ISI NOTIFIKASI DINAMIS --}}
                <div class="max-h-[350px] overflow-y-auto custom-scrollbar">
                    @forelse($notifikasiNavbar as $notif)
                        @php
                            $bgTheme = $notif->status == 'terlambat' ? 'bg-[#E9D9D9]' : 'bg-[#E9EBE9]';
                            $iconBg = $notif->status == 'terlambat' ? 'bg-red-50' : 'bg-teal-50';
                            $iconColor = $notif->status == 'terlambat' ? 'text-red-600' : 'text-teal-600';
                            $textColor = $notif->status == 'terlambat' ? 'text-red-500' : 'text-green-500';
                        @endphp

                        <a href="{{ route('pemesanan.show', $notif->pemesanan_id) }}"
                            class="p-4 border-b border-gray-50 hover:opacity-80 transition cursor-pointer flex gap-4 {{ $bgTheme }} rounded-md"
                            style="margin: 10px">
                            <div
                                class="w-9 h-9 {{ $iconBg }} rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-clock {{ $iconColor }} text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-gray-800">Pemesanan atas nama
                                    {{ $notif->pemesanan->pemeriksaan->pasien->nama ?? 'Pasien' }}</p>
                                <p class="text-[11px] {{ $textColor }} mt-0.5 font-medium">{{ $notif->pesan }}</p>
                                <p class="text-[9px] text-gray-400 mt-1.5 font-bold uppercase tracking-tighter">
                                    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-center text-xs text-gray-400 italic">
                            Belum ada notifikasi.
                        </div>
                    @endforelse
                </div>

                {{-- FOOTER DROPDOWN --}}
                <a href="{{ route('notifikasi.index') }}"
                    class="block text-center text-[#176851] font-bold text-xs py-3 hover:bg-gray-50 transition">
                    LIHAT SEMUA NOTIFIKASI
                </a>

            </div>
        </div>

        {{-- PROFIL USER --}}
        <div class="relative" x-data="{ openProfile: false }">
            <button @click="openProfile = !openProfile" class="flex items-center space-x-1.5 focus:outline-none group">
                <img src="{{ Auth::user() && Auth::user()->foto
                    ? asset('storage/' . Auth::user()->foto) .
                        '?v=' .
                        (Auth::user()->updated_at ? Auth::user()->updated_at->timestamp : time())
                    : asset('images/user-profile-avatar.jpg') }}"
                    class="w-8 h-8 rounded-xl object-cover border border-gray-700 shadow-sm group-hover:border-teal-500 transition"
                    alt="Profile">
            </button>

            <div x-show="openProfile" @click.away="openProfile = false"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute right-0 mt-4 w-56 bg-white rounded-xl shadow-xl border border-gray-300 z-[999] overflow-hidden py-2"
                style="display: none;">

                <a href="{{ route('profile') }}"
                    class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                    <i class="fa-regular fa-user w-5 text-gray-400 text-base mr-2.5"></i>
                    <span>Lihat Profil</span>
                </a>

                <hr class="border-gray-100 my-1">

                <div class="px-4 py-2 hover:bg-red-50/40 transition group">
                    <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                        @csrf
                        <button type="submit" class="w-full flex items-start text-left focus:outline-none">
                            <i
                                class="fa-solid fa-arrow-right-from-bracket w-5 text-red-500 text-base mr-2.5 mt-0.5"></i>
                            <div>
                                <span class="block text-sm font-semibold text-red-600">Log Out</span>
                                <span class="block text-[11px] text-gray-400 mt-0.5">
                                    {{ Auth::user()->name ?? 'Dr. Adhi Winardi' }}
                                </span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
