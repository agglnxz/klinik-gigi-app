@extends('layouts.main')

@section('title', 'Pemesanan')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">


    <div class="space-y-6">

        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-black-1000">Daftar Pemesanan</h3>
                <p class="text-sm text-gray-500 font-linght">Mengelola data pemesanan dan pengiriman ke laboratorium gigi</p>
            </div>
            <button
                class="bg-[#176851] hover:bg-[#357a66] text-white px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 transition-all shadow-sm">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Pemesanan
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-[#e7f5f1] text-[#41917a] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Total Pasien</p>
                    <h3 class="text-2xl font-black text-gray-800">1,284</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl     shadow-sm border border-gray-100 flex items-center gap-6">
                <div class="bg-[#e7f5f1] text-[#176851] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Pasien Baru (Bulan Ini)</p>
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
                <table class="w-full min-w-[1900px] text-left border-collapse">
                   <thead>
                        <tr class="bg-gray-100 border-b border-gray-100">
                            <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">ID Pemesanan</th>
                            <th class="w-[220px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Nama Pasien</th>
                            <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Tanggal Kirim</th>
                            <th class="w-[180px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Estimasi Selesai</th>
                            <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Laboratorium</th>
                            <th class="w-[190px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Biaya Laboratorium</th>
                            <th class="w-[240px] px-5 py-4 text-[10px] font-bold text-gray-900 uppercase tracking-wide">Status Pembayaran Laboratorium</th>
                            <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Biaya Ekspedisi</th>
                            <th class="w-[140px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Status</th>
                            <th class="w-[150px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">

                        <tr class="hover:bg-gray-50/30 transition">
                            <tr class="hover:bg-gray-50/30 transition">
                            <td class="px-5 py-4 text-[12px] font-medium text-gray-700">
                                PSN-001-20260410
                            </td>
                            <td class="px-6 py-6">
                                <p class="font-bold text-gray-800 text-[13px]">Yossy Fira Rosdiana</p>
                                <p class="text-[10px] text-gray-400 font-normal uppercase mt-0.5 tracking-tighter">RM-2026-0410</p>
                            </td>
                            <td class="px-6 py-6 text-[13px] text-gray-900 font-normal uppercase">01 Januari 2026</td>
                            <td class="px-6 py-6 text-[13px] text-gray-900 font-normal uppercase">10 Januari 2026</td>
                            <td class="px-6 py-6 text-[13px] text-gray-900 font-normal">Inident</td>
                            <td class="px-6 py-6 text-[13px] text-gray-900 font-normal">Rp. 1.100.000</td>
                            <td class="px-6 py-6"><span class="badge-lunas">Lunas</span></td>
                            <td class="px-6 py-6 text-[13px] text-gray-900 font-normal">Rp. 100.000</td>
                            <td class="px-6 py-6"><span class="status-circle status-diproses">Di Proses</span></td>
                            <td class="px-6 py-6">
                                <div class="flex justify-center gap-2">
                                    <button
                                        class="flex items-center px-2 py-1 bg-[#59a38a] hover:bg-[#4a8a75] text-white text-[10px] font-bold rounded-lg uppercase transition">
                                        <i class="fa-solid fa-pen text-[9px] mr-1"></i> Edit
                                    </button>
                                    <button
                                        class="flex items-center px-3 py-1.5 bg-[#d65f5f] hover:bg-[#b84f4f] text-white text-[10px] font-bold rounded-lg uppercase transition">
                                        <i class="fa-solid fa-trash text-[9px] mr-1.5"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50/30 transition">
                            <tr class="hover:bg-gray-50/30 transition">
                            <td class="px-5 py-4 text-[12px] font-medium text-gray-700">
                                PSN-001-20260410
                            </td>
                            <td class="px-6 py-6">
                                <p class="font-bold text-gray-800 text-[13px]">Inandiar Sharfina Fauzi</p>
                                <p class="text-[10px] text-gray-400 font-normal uppercase mt-0.5 tracking-tighter">RM-2026-0230</p>
                            </td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal uppercase">02 Februari 2026</td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal uppercase">12 Februari 2026</td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal">Sataswati</td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal">Rp. 1.200.000</td>
                            <td class="px-6 py-6"><span class="badge-belum">Belum Lunas</span></td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal">Rp. 200.000</td>
                            <td class="px-6 py-6"><span class="status-circle status-selesai">Selesai</span></td>
                            <td class="px-6 py-6">
                                <div class="flex justify-center gap-2">
                                    <button
                                        class="flex items-center px-2 py-1 bg-[#59a38a] hover:bg-[#4a8a75] text-white text-[10px] font-bold rounded-lg uppercase transition">
                                        <i class="fa-solid fa-pen text-[9px] mr-1"></i> Edit
                                    </button>
                                    <button
                                        class="flex items-center px-3 py-1.5 bg-[#d65f5f] hover:bg-[#b84f4f] text-white text-[10px] font-bold rounded-lg uppercase transition">
                                        <i class="fa-solid fa-trash text-[9px] mr-1.5"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50/30 transition">
                            <tr class="hover:bg-gray-50/30 transition">
                            <td class="px-5 py-4 text-[12px] font-medium text-gray-700">
                                PSN-001-20260410
                            </td>
                            <td class="px-6 py-6">
                                <p class="font-bold text-gray-800 text-[13px]">Moch Firman Triswanda</p>
                                <p class="text-[10px] text-gray-400 font-normal uppercase mt-0.5 tracking-tighter">RM-2026-0112</p>
                            </td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal uppercase">03 Maret 2026</td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal uppercase">13 Maret 2026</td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal">Fali Denta</td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal">Rp. 1.300.000</td>
                            <td class="px-6 py-6"><span class="badge-lunas">Lunas</span></td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal">Rp. 300.000</td>
                            <td class="px-6 py-6"><span class="status-circle status-terlambat">Terlambat</span></td>
                            <td class="px-6 py-6">
                                <div class="flex justify-center gap-2">
                                    <button
                                        class="flex items-center px-2 py-1 bg-[#59a38a] hover:bg-[#4a8a75] text-white text-[10px] font-bold rounded-lg uppercase transition">
                                        <i class="fa-solid fa-pen text-[9px] mr-1"></i> Edit
                                    </button>
                                    <button
                                        class="flex items-center px-3 py-1.5 bg-[#d65f5f] hover:bg-[#b84f4f] text-white text-[10px] font-bold rounded-lg uppercase transition">
                                        <i class="fa-solid fa-trash text-[9px] mr-1.5"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50/30 transition">
                            <tr class="hover:bg-gray-50/30 transition">
                            <td class="px-5 py-4 text-[12px] font-medium text-gray-700">
                                PSN-001-20260410
                            </td>
                            <td class="px-6 py-6">
                                <p class="font-bold text-gray-800 text-[13px]">Galang Bagus Erkamta</p>
                                <p class="text-[10px] text-gray-400 font-normal uppercase mt-0.5 tracking-tighter">RM-2026-0108</p>
                            </td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal uppercase">04 April 2026</td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal uppercase">14 April 2026</td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal">Wijaya</td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal">Rp. 1.400.000</td>
                            <td class="px-6 py-6"><span class="badge-belum">Belum Lunas</span></td>
                            <td class="px-6 py-6 text-[13px] text-gray-800 font-normal">Rp. 400.000</td>
                            <td class="px-6 py-6"><span class="status-circle status-diproses">Di Proses</span></td>
                            <td class="px-6 py-6">
                                <div class="flex justify-center gap-2">
                                    <button
                                        class="flex items-center px-2 py-1 bg-[#59a38a] hover:bg-[#4a8a75] text-white text-[10px] font-bold rounded-lg uppercase transition">
                                        <i class="fa-solid fa-pen text-[9px] mr-1"></i> Edit
                                    </button>
                                    <button
                                        class="flex items-center px-3 py-1.5 bg-[#d65f5f] hover:bg-[#b84f4f] text-white text-[10px] font-bold rounded-lg uppercase transition">
                                        <i class="fa-solid fa-trash text-[9px] mr-1.5"></i> Hapus
                                    </button>
                                </div>
                            </td>
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
