@extends('layouts.main')

@section('title', 'Riwayat Pemesanan')

@section('content')
    <div class="space-y-6">

        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-black-1000">Riwayat Pemesanan</h3>
                <p class="text-sm text-gray-500 font-light">Mengelola data pemesanan dan pengiriman yang telah tiba dari laboratorium gigi</p>
            </div>
        </div>

        {{-- WIDGET STATISTIK DINAMIS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Widget 1: Total Pesanan --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-[#e7f5f1] text-[#41917a] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Total Pesanan</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ number_format($totalPesanan, 0, ',', '.') }}</h3>
                </div>
            </div>

            {{-- Widget 2: Telah Tiba di Klinik --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6">
                <div class="bg-[#e7f5f1] text-[#176851] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Telah Tiba</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ number_format($telahTiba, 0, ',', '.') }}</h3>
                </div>
            </div>

            {{-- Widget 3: Selesai Tuntas --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-[#e7f5f1] text-[#176851] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Selesai Tuntas</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ number_format($pesananSelesai, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        {{-- FILTER & PENCARIAN --}}
        <div class="bg-[#F3F4F3] px-4 py-3 rounded-2xl flex flex-wrap lg:flex-nowrap justify-between items-center gap-3 border border-gray-100">
            <form action="{{ route('pemesanan-riwayat') }}" method="GET" class="w-full max-w-[380px]">
                <div class="relative w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari pesanan, pasien, lab..."
                        class="w-full pl-11 pr-4 py-2.5 text-xs border-none rounded-xl bg-white ring-1 ring-gray-100 focus:ring-2 focus:ring-teal-500 outline-none transition-all shadow-sm placeholder:text-gray-600 font-medium">
                </div>
            </form>

            <div class="flex items-center gap-8">
                {{-- AlpineJS Periode Filter (Statis UI) --}}
                <div x-data="{ open: false, selected: 'Bulan Ini' }" class="relative flex bg-white rounded-xl ring-1 ring-gray-100 shadow-sm">
                    <button @click="open = !open" class="px-5 py-3 text-[10px] font-bold text-gray-500 hover:bg-gray-50 rounded-xl flex items-center justify-between w-[160px] uppercase tracking-tight transition-colors">
                        <span>Periode</span>
                        <span class="text-[#176851] font-black flex items-center gap-1">
                            <span x-text="selected"></span>
                            <i class="fa-solid fa-chevron-down text-[8px]"></i>
                        </span>
                    </button>
                    <div x-show="open" @click.outside="open = false" class="absolute top-full mt-2 w-full bg-white rounded-xl shadow-lg ring-1 ring-gray-100 z-50 overflow-hidden" style="display: none;">
                        <button @click="selected='Hari Ini'; open=false" class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Hari Ini</button>
                        <button @click="selected='Minggu Ini'; open=false" class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Minggu Ini</button>
                        <button @click="selected='Bulan Ini'; open=false" class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Bulan Ini</button>
                    </div>
                </div>

                {{-- Ekspor Tombol --}}
                <button class="px-4 py-3 text-[9px] font-black text-gray-500 bg-white ring-1 ring-gray-100 rounded-xl flex items-center gap-3 hover:bg-gray-50 transition-all shadow-sm uppercase tracking-widest whitespace-nowrap cursor-pointer">
                    <i class="fa-solid fa-download text-gray-400 text-xs"></i> Ekspor Data
                </button>
            </div>
        </div>

        {{-- TABEL DATA RIWAYAT --}}
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-100">
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">ID Pesanan</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Pasien</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Laboratorium</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Tgl Tiba / Selesai</th>
                            <th class="px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($riwayat as $item)
                            <tr class="hover:bg-gray-50/30 transition">
                                <td class="px-5 py-4 text-[12px] font-bold text-[#176851]">
                                    {{ $item->no_pemesanan }}
                                </td>
                                <td class="px-5 py-4 text-[13px] font-bold text-gray-800">
                                    {{ $item->pemeriksaan->pasien->nama ?? 'Pasien Anonim' }}
                                </td>
                                <td class="px-5 py-4 text-[13px] text-gray-800 font-normal">
                                    {{ $item->lab->nama_lab ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-[13px] text-gray-800 font-normal">
                                    {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    @if ($item->status_pemesanan == 'tiba_di_klinik')
                                        <span class="px-3 py-1 bg-teal-50 text-teal-700 border border-teal-100 font-bold text-[10px] rounded-full uppercase tracking-wider">
                                            Tiba di Klinik
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-green-50 text-green-700 border border-green-100 font-bold text-[10px] rounded-full uppercase tracking-wider">
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-xs text-gray-400 italic">
                                    Belum ada riwayat pesanan yang telah tiba di klinik atau selesai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER PAGINATION --}}
            <div class="bg-[#F3F4F3] px-8 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-[11px] font-normal text-gray-400 italic">
                    Menampilkan <span class="text-gray-600 italic">{{ $riwayat->firstItem() ?? 0 }}-{{ $riwayat->lastItem() ?? 0 }} dari {{ $riwayat->total() }} riwayat</span>
                </p>
                <div>
                    {{ $riwayat->links('pagination::simple-tailwind') }}
                </div>
            </div>
        </div>
    </div>
@endsection
