<nav class="bg-white p-4 flex justify-end items-center w-full">

    <div class="flex items-center space-x-4">
        {{-- ALPINE.JS WRAPPER UNTUK DROPDOWN --}}
        <div class="relative" x-data="{ open: false }">

            {{-- TOMBOL LONCENG --}}
            <button @click="open = !open" class="relative focus:outline-none flex items-center">
                <i class="fa-regular fa-bell text-xl text-gray-500 hover:text-teal-600 transition"></i>
                @if ($unreadCount > 0)
                    <span class="absolute -top-1 -right-1 bg-[#176851] text-white text-[10px] rounded-full h-4 w-4 flex items-center justify-center border-2 border-white font-bold">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </button>

            {{-- KOTAK DROPDOWN MELAYANG (Ini yang sebelumnya terhapus) --}}
            <div x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
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
                            $iconBg  = $notif->status == 'terlambat' ? 'bg-red-50' : 'bg-teal-50';
                            $iconColor = $notif->status == 'terlambat' ? 'text-red-600' : 'text-teal-600';
                            $textColor = $notif->status == 'terlambat' ? 'text-red-500' : 'text-green-500';
                        @endphp

                        <a href="{{ route('pemesanan.show', $notif->pemesanan_id) }}"
                            class="p-4 border-b border-gray-50 hover:opacity-80 transition cursor-pointer flex gap-4 {{ $bgTheme }} rounded-md"
                            style="margin: 10px">
                            <div class="w-9 h-9 {{ $iconBg }} rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-clock {{ $iconColor }} text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-gray-800">Pemesanan atas nama {{ $notif->pemesanan->pemeriksaan->pasien->nama ?? 'Pasien' }}</p>
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
                <a href="{{ route('notifikasi.index') }}" class="block text-center text-[#176851] font-bold text-xs py-3 hover:bg-gray-50 transition">
                    LIHAT SEMUA NOTIFIKASI
                </a>

            </div>
        </div>

        {{-- PROFIL USER --}}
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/user-profile-avatar.jpg') }}" class="w-8 h-8 rounded-xl object-cover border border-gray-100 shadow-sm" alt="Profile">
        </div>

    </div>
</nav>
