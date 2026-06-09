@extends('layouts.main')

@section('title', 'Pemesanan')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-bold text-black-1000">Daftar Pemesanan</h3>
            <p class="text-sm text-gray-500 font-light">Mengelola data pemesanan dan pengiriman ke laboratorium gigi</p>
        </div>
        @if (auth()->user()->role === 'Admin')
        <a href="{{ route('pemesanan.create') }}">
            <button
                class="bg-[#176851] hover:bg-[#357a66] text-white px-5 py-2.5 rounded-md text-sm font-bold flex items-center gap-2 transition-all shadow-sm cursor-pointer">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Pemesanan
            </button>
        </a>
        @endif
    </div>

    {{-- STATISTIK WIDGET --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Card 1: Khusus Bulan Ini --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
            <div class="bg-[#e7f5f1] text-[#41917a] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]">Pemesanan Baru</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $totalPesanan }}</h3>
                <span class="text-[10px] text-teal-600 font-medium bg-teal-50 px-2 py-0.5 rounded-full">Bulan Ini</span>
            </div>
        </div>

        {{-- Card 2: Akumulatif Semua Bulan (Kunci Monitoring Anda) --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6">
            <div class="bg-amber-50 text-amber-600 w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-spinner animate-spin" style="animation-duration: 3s;"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]">Sedang Diproses</p>
                <h3 class="text-2xl font-black text-amber-600">{{ $sedangDiproses }}</h3>
                <span class="text-[10px] text-amber-600 font-medium bg-amber-50 px-2 py-0.5 rounded-full">seluruh periode</span>
            </div>
        </div>

        {{-- Card 3: Khusus Bulan Ini --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
            <div class="bg-[#e7f5f1] text-[#176851] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-check-double"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]">Pesanan Selesai</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $pesananSelesai }}</h3>
                <span class="text-[10px] text-teal-600 font-medium bg-teal-50 px-2 py-0.5 rounded-full">Bulan Ini</span>
            </div>
        </div>
    </div>

    {{-- BARIS PENCARIAN & FILTER MULTI-FUNGSI --}}
    <form action="{{ route('pemesanan.index') }}" method="GET" id="filter-form">
        <div
            class="bg-[#F3F4F3] px-4 py-3 rounded-2xl flex flex-wrap lg:flex-nowrap justify-between items-center gap-3 border border-gray-100">

            {{-- Input Pencarian --}}
            <div class="relative w-full max-w-[380px]">
                {{-- Input Teks --}}
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nomor pesanan, lab..."
                    class="w-full pl-4 pr-12 py-2.5 text-xs border-none rounded-xl bg-white ring-1 ring-gray-100 focus:ring-2 focus:ring-teal-500 outline-none transition-all shadow-sm placeholder:text-gray-600 font-medium">

                {{-- Tombol "Terapkan" yang diubah menjadi Icon Klik di Sisi Kanan Input --}}
                <button type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#176851] text-white p-1.5 rounded-lg hover:bg-teal-700 transition flex items-center justify-center w-8 h-8 shadow-sm cursor-pointer"
                    title="Terapkan Pencarian">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </div>

            {{-- Grouping Filter Kanan --}}
            <div class="flex items-center gap-4 flex-wrap lg:flex-nowrap">

                {{-- Hidden Inputs untuk menyimpan state dropdown Alpine --}}
                <input type="hidden" name="periode" id="input-periode" value="{{ request('periode', 'bulan_ini') }}">
                <input type="hidden" name="urutkan" id="input-urutkan" value="{{ request('urutkan', 'terbaru') }}">

                {{-- Filter Periode (AlpineJS Terintegrasi Form Auto-Submit) --}}
                @php
                $labelsPeriode = [
                'hari_ini' => 'Hari Ini',
                'minggu_ini' => 'Minggu Ini',
                'bulan_ini' => 'Bulan Ini',
                'tahun_ini' => 'Tahun Ini',
                ];
                $currentPeriodeLabel = $labelsPeriode[request('periode')] ?? 'Bulan Ini';
                @endphp
                <div x-data="{ open: false, selected: '{{ $currentPeriodeLabel }}' }" class="relative flex bg-white rounded-xl ring-1 ring-gray-100 shadow-sm">
                    <button @click="open = !open" type="button"
                        class="px-5 py-3 text-[10px] font-bold text-gray-500 hover:bg-gray-50 rounded-xl flex items-center justify-between w-[160px] uppercase tracking-tight transition-colors">
                        <span>Periode</span>
                        <span class="text-[#176851] font-black flex items-center gap-1">
                            <span x-text="selected"></span>
                            <i class="fa-solid fa-chevron-down text-[8px]"></i>
                        </span>
                    </button>
                    <div x-show="open" @click.outside="open = false"
                        class="absolute top-full mt-2 w-full bg-white rounded-xl shadow-lg ring-1 ring-gray-100 z-50 overflow-hidden"
                        style="display: none;">
                        <button type="button"
                            @click="selected='Hari Ini'; open=false; document.getElementById('input-periode').value='hari_ini'; document.getElementById('filter-form').submit();"
                            class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Hari Ini</button>
                        <button type="button"
                            @click="selected='Minggu Ini'; open=false; document.getElementById('input-periode').value='minggu_ini'; document.getElementById('filter-form').submit();"
                            class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Minggu Ini</button>
                        <button type="button"
                            @click="selected='Bulan Ini'; open=false; document.getElementById('input-periode').value='bulan_ini'; document.getElementById('filter-form').submit();"
                            class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Bulan Ini</button>
                        <button type="button"
                            @click="selected='Tahun Ini'; open=false; document.getElementById('input-periode').value='tahun_ini'; document.getElementById('filter-form').submit();"
                            class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Tahun Ini</button>
                    </div>
                </div>

                {{-- Filter Urutkan (AlpineJS Terintegrasi Form Auto-Submit) --}}
                @php
                $currentSortLabel = request('urutkan') === 'terlama' ? 'Tanggal Terlama' : 'Tanggal Terbaru';
                @endphp
                <div x-data="{ open: false, selected: '{{ $currentSortLabel }}' }" class="relative flex bg-white rounded-xl ring-1 ring-gray-100 shadow-sm">
                    <button @click="open = !open" type="button"
                        class="px-5 py-3 text-[10px] font-bold text-gray-500 hover:bg-gray-50 rounded-xl flex items-center gap-2 uppercase tracking-tight transition-colors whitespace-nowrap">
                        Urutkan
                        <span class="text-[#176851] font-black" x-text="selected"></span>
                        <i class="fa-solid fa-chevron-down text-[8px]"></i>
                    </button>
                    <div x-show="open" @click.outside="open = false"
                        class="absolute top-full mt-2 w-full bg-white rounded-xl shadow-lg ring-1 ring-gray-100 z-50 overflow-hidden"
                        style="display: none;">
                        <button type="button"
                            @click="selected='Tanggal Terbaru'; open=false; document.getElementById('input-urutkan').value='terbaru'; document.getElementById('filter-form').submit();"
                            class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Tanggal Terbaru</button>
                        <button type="button"
                            @click="selected='Tanggal Terlama'; open=false; document.getElementById('input-urutkan').value='terlama'; document.getElementById('filter-form').submit();"
                            class="w-full px-4 py-2 text-[10px] text-left hover:bg-gray-50">Tanggal Terlama</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- TABEL DATA --}}
    <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-[1900px] text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-100">
                        <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">No
                            Pemesanan</th>
                        <th class="w-[220px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Nama
                            Pasien</th>
                        <th class="w-[180px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">
                            Jenis Gigi</th>
                        <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">
                            Tanggal Kirim</th>
                        <th class="w-[180px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">
                            Estimasi Selesai</th>
                        <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">
                            Laboratorium</th>
                        <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">
                            Biaya Lab</th>
                        <th class="w-[180px] px-5 py-4 text-[10px] font-bold text-gray-900 uppercase tracking-wide">
                            Bayar Lab</th>
                        <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">
                            Harga Pasien</th>
                        <th class="w-[140px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">
                            Status</th>
                        <th
                            class="w-[200px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">

                    @forelse ($data as $item)
                    <tr class="hover:bg-gray-50/30 transition">
                        <td class="px-5 py-4 text-[13px] font-bold text-gray-800">
                            {{ $item->no_pemesanan }}
                        </td>

                        <td class="px-5 py-4">
                            <p class="font-bold text-gray-800 text-[13px]">
                                {{ $item->pemeriksaan->pasien->nama ?? 'Pasien Terhapus' }}
                            </p>
                            <p class="text-[10px] text-gray-400 font-normal uppercase mt-0.5 tracking-tighter">
                                {{ $item->pemeriksaan->pasien->no_rm ?? '-' }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            <p class="text-[13px] font-bold text-gray-800">{{ $item->items->count() }} Jenis</p>
                            <p class="text-[10px] text-gray-400 mt-0.5 max-w-[150px]"
                                style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $item->items->pluck('jenisGigi.nama_jenis')->join(', ') ?: '-' }}
                            </p>
                        </td>

                        <td class="px-5 py-4 text-[13px] text-gray-800 font-normal uppercase">
                            {{ \Carbon\Carbon::parse($item->tanggal_dikirim)->translatedFormat('d M Y') }}
                        </td>

                        <td class="px-5 py-4 text-[13px] text-gray-800 font-normal uppercase">
                            {{ \Carbon\Carbon::parse($item->estimasi_selesai)->translatedFormat('d M Y') }}
                        </td>

                        <td class="px-5 py-4 text-[13px] text-gray-800 font-normal">
                            {{ $item->lab->nama_lab ?? '-' }}
                        </td>

                        <td class="px-5 py-4 text-[13px] text-gray-800 font-normal">
                            Rp {{ number_format($item->biaya_lab, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4">
                            @if ($item->status_bayar_lab === 'sudah_lunas')
                            <span
                                class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[11px] font-bold">Lunas</span>
                            @else
                            <span
                                class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[11px] font-bold">Belum
                                Lunas</span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-[13px] text-gray-800 font-bold">
                            Rp {{ number_format($item->harga_pasien, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4">
                            @php
                            $statusColors = [
                            'dalam_proses' => 'bg-blue-100 text-blue-700',
                            'tiba_di_klinik' => 'bg-yellow-100 text-yellow-700',
                            'selesai' => 'bg-green-100 text-green-700',
                            'dibatalkan' => 'bg-gray-100 text-gray-700',
                            ];
                            $color =
                            $statusColors[$item->status_pemesanan] ??
                            'bg-red-50 text-red-500 border border-red-200';
                            $labelStatus = str_replace('_', ' ', $item->status_pemesanan);
                            @endphp
                            <span
                                class="{{ $color }} px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider whitespace-nowrap">
                                {{ $labelStatus }}
                            </span>
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex justify-center items-center gap-2">

                                {{-- TOMBOL DETAIL (Selalu aktif & perbaikan hover text putih) --}}
                                <a href="{{ route('pemesanan.show', $item->id) }}"
                                    class="flex items-center px-3 py-1.5 bg-[#e7f5f1] text-[#176851] text-xs font-bold rounded-md hover:bg-[#176851] hover:text-white transition-colors duration-200">
                                    <i class="fa-solid fa-eye mr-1.5"></i> Detail
                                </a>

                                @if (auth()->user()->role === 'Admin')
                                {{-- CEK STATUS PENGAJUAN HAPUS --}}
                                @if (isset($pendingHapus) && in_array($item->id, $pendingHapus))
                                {{-- Tombol Disabled (Abu-abu) --}}
                                <span
                                    class="flex items-center px-3 py-1.5 bg-gray-100 text-gray-400 text-[10px] font-bold rounded-md border border-gray-200 cursor-not-allowed">
                                    <i class="fa-solid fa-hourglass-half mr-1.5 animate-pulse"></i>
                                    Menunggu Hapus
                                </span>
                                @else
                                {{-- Tombol Normal Edit & Hapus --}}
                                <a href="{{ route('pemesanan.edit', $item->id) }}"
                                    class="flex items-center px-3 py-1.5 bg-[#59a38a] text-white text-xs font-bold rounded-md hover:bg-[#46826d] transition-colors duration-200">
                                    <i class="fa-solid fa-pen mr-1.5"></i> Edit
                                </a>

                                <button type="button"
                                    @click="$dispatch('buka-modal-hapus', { id: '{{ $item->id }}', nomor: '{{ $item->no_pemesanan }}', tabel: 'pemesanan' })"
                                    class="flex items-center px-3 py-1.5 bg-[#d65f5f] text-white text-xs font-bold rounded-md hover:bg-[#b54d4d] cursor-pointer transition-colors duration-200">
                                    <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                                </button>
                                @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-6 py-10 text-center text-gray-400 italic text-xs">
                            Belum ada data pemesanan laboratorium yang sesuai filter.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="bg-[#F3F4F3] px-8 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-[11px] font-normal text-gray-400 italic order-2 sm:order-1">
                Menampilkan data ke-{{ $data->firstItem() ?? 0 }} sampai {{ $data->lastItem() ?? 0 }} dari total <span class="text-gray-600 font-bold not-italic">{{ $data->total() }} pesanan</span>
            </p>

            <div class="order-1 sm:order-2 scale-90 origin-right global-pagination">
                {{ $data->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</div>


{{-- MODAL PENGAJUAN HAPUS --}}
<div x-data="{ open: false, idReferensi: '', nomorPesanan: '', namaTabel: '', alasan: '' }"
    @buka-modal-hapus.window="open = true; idReferensi = $event.detail.id; nomorPesanan = $event.detail.nomor; namaTabel = $event.detail.tabel"
    x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 p-4"
    style="display: none;">

    <div @click.outside="open = false"
        class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100">
        <div class="flex items-center gap-3 text-red-500 mb-4">
            <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            <h3 class="text-base font-bold text-gray-800">Pengajuan Hapus Data</h3>
        </div>

        <p class="text-xs text-gray-500 mb-4 leading-relaxed">
            Anda mengajukan penghapusan untuk <span class="font-bold text-gray-700" x-text="namaTabel"></span> dengan
            nomor: <span class="font-bold text-gray-800" x-text="nomorPesanan"></span>. Data tidak akan langsung
            terhapus sebelum disetujui Direktur.
        </p>
        <form :action="'{{ route('pengajuan-hapus.store') }}'" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="id_referensi" :value="idReferensi">
            <input type="hidden" name="nama_tabel" :value="namaTabel">

            {{-- TAMBAHAN 1: Input nama_data --}}
            <input type="hidden" name="nama_data" :value="nomorPesanan">

            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Alasan
                    Penghapusan</label>
                {{-- TAMBAHAN 2: Ubah name="alasan" menjadi name="alasan_hapus" --}}
                <textarea name="alasan_hapus" x-model="alasan" required rows="3"
                    placeholder="Tulis alasan mengapa data ini perlu dihapus..."
                    class="w-full p-3 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none transition-all placeholder:text-gray-400 font-medium"></textarea>
            </div>

            <div class="flex justify-end gap-2 border-t border-gray-50 pt-4">
                <button type="button" @click="open = false"
                    class="px-4 py-2 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg hover:bg-gray-200">Batal</button>
                <button type="submit" :disabled="!alasan.trim()"
                    class="px-4 py-2 bg-[#176851] text-white text-xs font-bold rounded-lg hover:bg-teal-700 disabled:opacity-50">Kirim
                    Pengajuan</button>
            </div>
        </form>
    </div>
</div>
@endsection