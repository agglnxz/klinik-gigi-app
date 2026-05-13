@extends('layouts.main')

@section('title', 'Pemeriksaan')

@section('content')
    <div class="space-y-6">

        {{-- HEADER & TOMBOL TAMBAH --}}
        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-black-1000">Daftar Pemeriksaan</h3>
                <p class="text-sm text-gray-500 font-light">Mengelola data rekam medis dan riwayat pemeriksaan pasien</p>
            </div>
            <a href="{{ route('pemeriksaan.create') }}">
                <button class="bg-[#176851] hover:bg-[#357a66] text-white px-5 py-2.5 rounded-md text-sm font-bold flex items-center gap-2 transition-all shadow-sm">
                    <i class="fa-solid fa-plus text-xs"></i> Tambah Pemeriksaan
                </button>
            </a>
        </div>

{{-- WIDGET STATISTIK DINAMIS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Card 1: Total Pasien --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-[#e7f5f1] text-[#41917a] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Total Pasien</p>
                    <h3 class="text-2xl font-black text-gray-800">
                        {{ number_format($totalPasien, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            {{-- Card 2: Pasien Baru (Bulan Ini) --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6">
                <div class="bg-[#e7f5f1] text-[#176851] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Pasien Baru (Bulan Ini)</p>
                    <h3 class="text-2xl font-black text-gray-800">
                        {{ number_format($pasienBaru, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            {{-- Card 3: Kunjungan Hari Ini --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-[#fef2f2] text-[#ef4444] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Kunjungan Hari Ini</p>
                    <h3 class="text-2xl font-black text-gray-800">
                        {{ number_format($kunjunganHariIni, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

        </div>

        {{-- BARIS PENCARIAN & FILTER --}}
        <div class="bg-[#F3F4F3] px-4 py-3 rounded-2xl flex flex-wrap lg:flex-nowrap justify-between items-center gap-3 border border-gray-100">
            <div class="relative w-full max-w-[380px]">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input type="text" placeholder="Cari nama pasien, dokter, atau catatan..."
                    class="w-full pl-11 pr-4 py-2.5 text-xs border-none rounded-xl bg-white ring-1 ring-gray-100 focus:ring-2 focus:ring-teal-500 outline-none transition-all shadow-sm placeholder:text-gray-600 font-medium">
            </div>

            <div class="flex items-center gap-8">
                {{-- Filter Periode --}}
                <div x-data="{ open: false, selected: 'Bulan Ini' }" class="relative flex bg-white rounded-xl ring-1 ring-gray-100 shadow-sm">
                    <button @click="open = !open" class="px-5 py-3 text-[10px] font-bold text-gray-500 hover:bg-gray-50 rounded-xl flex items-center justify-between w-[160px] uppercase tracking-tight transition-colors">
                        <span>Periode</span>
                        <span class="text-[#176851] font-black flex items-center gap-1">
                            <span x-text="selected"></span>
                            <i class="fa-solid fa-chevron-down text-[8px]"></i>
                        </span>
                    </button>
                    <div x-show="open" @click.outside="open = false" class="absolute top-full mt-2 w-full bg-white rounded-xl shadow-lg ring-1 ring-gray-100 z-50 overflow-hidden">
                        <button @click="selected='Hari Ini'; open=false" class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Hari Ini</button>
                        <button @click="selected='Minggu Ini'; open=false" class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Minggu Ini</button>
                        <button @click="selected='Bulan Ini'; open=false" class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Bulan Ini</button>
                        <button @click="selected='Tahun Ini'; open=false" class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Tahun Ini</button>
                    </div>
                </div>

                {{-- Filter Urutkan --}}
                <div x-data="{ open: false, selected: 'Tanggal Terbaru' }" class="relative flex bg-white rounded-xl ring-1 ring-gray-100 shadow-sm">
                    <button @click="open = !open" class="px-5 py-3 text-[10px] font-bold text-gray-500 hover:bg-gray-50 rounded-xl flex items-center gap-2 uppercase tracking-tight transition-colors whitespace-nowrap">
                        Urutkan
                        <span class="text-[#176851] font-black" x-text="selected"></span>
                        <i class="fa-solid fa-chevron-down text-[8px]"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false" class="absolute top-full mt-2 w-full bg-white rounded-xl shadow-lg ring-1 ring-gray-100 z-50 overflow-hidden">
                        <button @click="selected='Tanggal Terbaru'; open=false" class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Tanggal Terbaru</button>
                        <button @click="selected='Tanggal Terlama'; open=false" class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Tanggal Terlama</button>
                    </div>
                </div>

                <button class="px-2 py-3 text-[9px] font-black text-gray-500 bg-white ring-1 ring-gray-100 rounded-xl flex items-center gap-3 hover:bg-gray-50 transition-all shadow-sm uppercase tracking-widest whitespace-nowrap">
                    <i class="fa-solid fa-download text-gray-400 text-xs"></i> Ekspor Data
                </button>
            </div>
        </div>

        {{-- TABEL DATA PEMERIKSAAN --}}
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full min-w-[1900px] text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F3F4F3] border-b border-gray-100">
                            <th class="w-[250px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Nama Pasien</th>
                            <th class="w-[200px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest">ID Pemeriksaan</th>
                            <th class="w-[200px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Tanggal Periksa</th>
                            <th class="w-[200px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Dokter Gigi</th>
                            <th class="w-[200px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Asisten Dokter</th>
                            <th class="w-[350px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Catatan</th>
                            <th class="w-[150px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">

                        {{-- LOOPING DATA DINAMIS DARI DATABASE --}}
                        @forelse ($pemeriksaan as $item)
                            <tr class="hover:bg-gray-50/30 transition">

                                {{-- NAMA PASIEN & NOMOR RM --}}
                                <td class="px-6 py-6">
                                    <p class="font-bold text-gray-800 text-[13px]">{{ $item->pasien->nama ?? 'Data Pasien Terhapus' }}</p>
                                    <p class="text-[10px] text-gray-400 font-normal uppercase mt-0.5 tracking-tighter">
                                        {{ $item->pasien->no_rm ?? '-' }}
                                    </p>
                                </td>

                                {{-- NOMOR / ID PEMERIKSAAN --}}
                                <td class="px-6 py-6 text-[13px] text-gray-800 font-normal">
                                    {{ $item->no_pemeriksaan }}
                                </td>

                                {{-- TANGGAL PEMERIKSAAN (Terjemahan Bahasa Indonesia) --}}
                                <td class="px-6 py-6 text-[13px] text-gray-800 font-normal uppercase">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                </td>

                                {{-- DOKTER GIGI --}}
                                <td class="px-6 py-6 text-[13px] font-bold text-gray-700">
                                    {{ $item->dokter->nama ?? 'Dokter Tidak Ditemukan' }}
                                </td>

                                {{-- ASISTEN DOKTER --}}
                                <td class="px-6 py-6 text-[13px] text-gray-800 font-normal">
                                    {{ $item->asisten->nama ?? '-' }}
                                </td>

                                {{-- CATATAN KLINIS --}}
                                <td class="px-6 py-6">
                                    <p class="text-[12px] text-gray-800 font-normal leading-relaxed">
                                        {{ $item->catatan }}
                                    </p>
                                </td>

                                {{-- TOMBOL AKSI --}}
                                <td class="px-6 py-6">
                                    <div class="flex justify-center items-center gap-2">

                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('pemeriksaan.edit', $item->id) }}"
                                            class="px-3 py-1.5 bg-[#59a38a] text-white text-xs rounded-md hover:bg-[#46826d] transition flex items-center">
                                            <i class="fa-solid fa-pen mr-1.5"></i> Edit
                                        </a>

                                        {{-- Tombol Hapus (SATPAM RBAC: Hanya muncul untuk Direktur) --}}
                                        @if (Auth::check() && Auth::user()->role === 'direktur')
                                            <form action="{{ route('pemeriksaan.destroy', $item->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data pemeriksaan ini?')"
                                                    class="px-3 py-1.5 bg-[#d65f5f] text-white text-xs rounded-md hover:bg-[#b54d4d] transition flex items-center cursor-pointer">
                                                    <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- TAMPILAN JIKA DATA KOSONG --}}
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic text-xs border-none">
                                    Belum ada data pemeriksaan yang dicatat.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- FOOTER INDIKATOR TOTAL DATA DINAMIS --}}
            <div class="bg-[#F3F4F3] px-8 py-4 border-t border-gray-100">
                <p class="text-[11px] font-normal text-gray-400 italic">
                    Total terdaftar: <span class="text-gray-600 font-bold not-italic">{{ $pemeriksaan->count() }} riwayat pemeriksaan</span>
                </p>
            </div>
        </div>
    </div>
@endsection
