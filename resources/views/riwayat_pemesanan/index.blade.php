@extends('layouts.main')

@section('title', 'Riwayat Pemesanan')

@section('content')
    <div class="space-y-6">

        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-black-1000">Riwayat Pemesanan</h3>
                <p class="text-sm text-gray-500 font-linght">Mengelola data pemesanan dan pengiriman ke laboratorium gigi</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- DIUBAH: Total Pasien → Total Pesanan, icon fa-users → fa-clipboard-list --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-[#e7f5f1] text-[#41917a] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Total Pesanan</p>
                    <h3 class="text-2xl font-black text-gray-800">1,284</h3>
                </div>
            </div>

            {{-- DIUBAH: Pasien Baru (Bulan Ini) → Telah Tiba, icon fa-user-plus → fa-box-open --}}
            <div class="bg-white p-6 rounded-2xl     shadow-sm border border-gray-100 flex items-center gap-6">
                <div class="bg-[#e7f5f1] text-[#176851] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Telah Tiba</p>
                    <h3 class="text-2xl font-black text-gray-800">42</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-[#fef2f2] text-[#ef4444] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Kunjungan Hari Ini</p>
                    <h3 class="text-2xl font-black text-gray-800">18</h3>
                </div>
            </div>
        </div>

        <div
            class="bg-[#F3F4F3] px-4 py-3 rounded-2xl flex flex-wrap lg:flex-nowrap justify-between items-center gap-3 border border-gray-100">
            <div class="relative w-full max-w-[380px]">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input type="text" placeholder="Cari nama pasien, dokter, atau catatan..."
                    class="w-full pl-11 pr-4 py-2.5 text-xs border-none rounded-xl bg-white ring-1 ring-gray-100 focus:ring-2 focus:ring-teal-500 outline-none transition-all shadow-sm placeholder:text-gray-600 font-medium">
            </div>

            <div class="flex items-center gap-8">
                <div x-data="{ open: false, selected: 'Bulan Ini' }" class="relative flex bg-white rounded-xl ring-1 ring-gray-100 shadow-sm">

                    <button @click="open = !open"
                        class="px-5 py-3 text-[10px] font-bold text-gray-500 hover:bg-gray-50 rounded-xl flex items-center justify-between w-[160px] uppercase tracking-tight transition-colors">

                        <span>Periode</span>

                        <span class="text-[#176851] font-black flex items-center gap-1">
                            <span x-text="selected"></span>
                            <i class="fa-solid fa-chevron-down text-[8px]"></i>
                        </span>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.outside="open = false"
                        class="absolute top-full mt-2 w-full bg-white rounded-xl shadow-lg ring-1 ring-gray-100 z-50 overflow-hidden">

                        <button @click="selected='Hari Ini'; open=false"
                            class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Hari Ini</button>

                        <button @click="selected='Minggu Ini'; open=false"
                            class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Minggu Ini</button>

                        <button @click="selected='Bulan Ini'; open=false"
                            class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Bulan Ini</button>

                        <button @click="selected='Tahun Ini'; open=false"
                            class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Tahun Ini</button>
                    </div>
                </div>
                <div x-data="{ open: false, selected: 'Tanggal Terbaru' }" class="relative flex bg-white rounded-xl ring-1 ring-gray-100 shadow-sm">

                    <button @click="open = !open"
                        class="px-5 py-3 text-[10px] font-bold text-gray-500 hover:bg-gray-50 rounded-xl flex items-center gap-2 uppercase tracking-tight transition-colors whitespace-nowrap">

                        Urutkan
                        <span class="text-[#176851] font-black" x-text="selected"></span>
                        <i class="fa-solid fa-chevron-down text-[8px]"></i>
                    </button>

                    <div x-show="open" @click.outside="open = false"
                        class="absolute top-full mt-2 w-full bg-white rounded-xl shadow-lg ring-1 ring-gray-100 z-50 overflow-hidden">

                        <button @click="selected='Tanggal Terbaru'; open=false"
                            class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Tanggal Terbaru</button>

                        <button @click="selected='Tanggal Terlama'; open=false"
                            class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Tanggal Terlama</button>
                    </div>
                </div>

                <button
                    class="px-2 py-3 text-[9px] font-black text-gray-500 bg-white ring-1 ring-gray-100 rounded-xl flex items-center gap-3 hover:bg-gray-50 transition-all shadow-sm uppercase tracking-widest whitespace-nowrap">
                    <i class="fa-solid fa-download text-gray-400 text-xs"></i> Ekspor Data
                </button>
            </div>
        </div>

        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                {{-- DIUBAH: Kolom tabel disederhanakan sesuai gambar --}}
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-100">
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">ID Pesanan
                            </th>
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Pasien</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Laboratorium
                            </th>
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Estimasi</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">

                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="px-5 py-4 text-[12px] font-bold text-[#176851]">
                                ORD-2024-001
                            </td>
                            <td class="px-5 py-4 text-[13px] font-bold text-gray-800">Andi Wijaya</td>
                            <td class="px-5 py-4 text-[13px] text-gray-800 font-normal">Dental Lab</td>
                            <td class="px-5 py-4 text-[13px] text-gray-800 font-normal">25 Okt 2023</td>
                            <td class="px-5 py-4"><span class="badge-lunas">Selesai</span></td>
                        </tr>

                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="px-5 py-4 text-[12px] font-bold text-[#176851]">
                                ORD-2024-002
                            </td>
                            <td class="px-5 py-4 text-[13px] font-bold text-gray-800">Siiti Rahma</td>
                            <td class="px-5 py-4 text-[13px] text-gray-800 font-normal">Indo Tech Lab</td>
                            <td class="px-5 py-4 text-[13px] text-gray-800 font-normal">25 Okt 2023</td>
                            <td class="px-5 py-4"><span class="badge-lunas">Selesai</span></td>
                        </tr>

                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="px-5 py-4 text-[12px] font-bold text-[#176851]">
                                ORD-2024-003
                            </td>
                            <td class="px-5 py-4 text-[13px] font-bold text-gray-800">Budi Santoso</td>
                            <td class="px-5 py-4 text-[13px] text-gray-800 font-normal">Elite JKT Lab</td>
                            <td class="px-5 py-4 text-[13px] text-gray-800 font-normal">25 Okt 2023</td>
                            <td class="px-5 py-4"><span class="badge-lunas">Selesai</span></td>
                        </tr>

                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="px-5 py-4 text-[12px] font-bold text-[#176851]">
                                ORD-2024-004
                            </td>
                            <td class="px-5 py-4 text-[13px] font-bold text-gray-800">Galang Bagus Erkamta</td>
                            <td class="px-5 py-4 text-[13px] text-gray-800 font-normal">Wijaya Lab</td>
                            <td class="px-5 py-4 text-[13px] text-gray-800 font-normal">14 Apr 2026</td>
                            <td class="px-5 py-4"><span class="badge-lunas">Selesai</span></td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="bg-[#F3F4F3] px-8 py-4 border-t border-gray-100">
                <p class="text-[11px] font-normal text-gray-400 italic">Menampilkan <span class="text-gray-600 italic">1-4
                        dari 40 riwayat</span></p>
            </div>
        </div>
    </div>
@endsection
