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
                <button
                    class="bg-[#176851] hover:bg-[#357a66] text-white px-5 py-2.5 rounded-md text-sm font-bold flex items-center gap-2 transition-all shadow-sm">
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
        <div
            class="bg-[#F3F4F3] px-4 py-3 rounded-2xl flex flex-wrap lg:flex-nowrap justify-between items-center gap-3 border border-gray-100">
            <div class="relative w-full max-w-[380px]">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input type="text" placeholder="Cari nama pasien, dokter, atau catatan..."
                    class="w-full pl-11 pr-4 py-2.5 text-xs border-none rounded-xl bg-white ring-1 ring-gray-100 focus:ring-2 focus:ring-teal-500 outline-none transition-all shadow-sm placeholder:text-gray-600 font-medium">
            </div>

            <div class="flex items-center gap-8">
                {{-- Filter Periode --}}
                <div x-data="{ open: false, selected: 'Bulan Ini' }" class="relative flex bg-white rounded-xl ring-1 ring-gray-100 shadow-sm">
                    <button @click="open = !open"
                        class="px-5 py-3 text-[10px] font-bold text-gray-500 hover:bg-gray-50 rounded-xl flex items-center justify-between w-[160px] uppercase tracking-tight transition-colors">
                        <span>Periode</span>
                        <span class="text-[#176851] font-black flex items-center gap-1">
                            <span x-text="selected"></span>
                            <i class="fa-solid fa-chevron-down text-[8px]"></i>
                        </span>
                    </button>
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

                {{-- Filter Urutkan --}}
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

        {{-- TABEL DATA PEMERIKSAAN --}}
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full min-w-[1900px] text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F3F4F3] border-b border-gray-100">
                            <th class="w-[250px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest">
                                Nama Pasien</th>
                            <th class="w-[200px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest">ID
                                Pemeriksaan</th>
                            <th class="w-[200px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest">
                                Tanggal Periksa</th>
                            <th class="w-[200px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest">
                                Dokter Gigi</th>
                            <th class="w-[200px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest">
                                Asisten Dokter</th>
                            <th class="w-[350px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest">
                                Catatan</th>
                            <th
                                class="w-[150px] px-6 py-5 text-[11px] font-bold text-bold-900 uppercase tracking-widest text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">

                        {{-- LOOPING DATA DINAMIS DARI DATABASE --}}
                        @forelse ($pemeriksaan as $item)
                            <tr class="hover:bg-gray-50/30 transition">

                                {{-- NAMA PASIEN & NOMOR RM --}}
                                <td class="px-6 py-6">
                                    <p class="font-bold text-gray-800 text-[13px]">
                                        {{ $item->pasien->nama ?? 'Data Pasien Terhapus' }}</p>
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
                                <td class="px-6 py-6 max-w-[350px]">
                                    <div x-data="{ teks: {{ json_encode($item->catatan) }} }">
                                        <p class="text-[12px] text-gray-800 font-normal leading-relaxed line-clamp-3 cursor-help"
                                            {{-- UBAH BARIS INI: Kirimkan juga posisi x dan y --}}
                                            @mouseenter="$dispatch('tampilkan-catatan', { teks: teks, x: $event.clientX, y: $event.clientY })"
                                            @mouseleave="$dispatch('sembunyikan-catatan')">
                                            {{ $item->catatan }}
                                        </p>
                                    </div>
                                </td>

                                {{-- TOMBOL AKSI --}}
                                <td class="px-6 py-6">
                                    <div class="flex justify-center items-center gap-2">
                                        @if (auth()->user()->role === 'Admin')
                                            <a href="{{ route('pemeriksaan.edit', $item->id) }}"
                                                class="flex items-center px-3 py-1.5 bg-[#59a38a] text-white text-xs font-bold rounded-md hover:bg-[#46826d]">
                                                <i class="fa-solid fa-pen mr-1.5"></i> Edit
                                            </a>

                                            <button type="button"
                                                @click="$dispatch('buka-modal-hapus', { id: '{{ $item->id }}', nomor: '{{ $item->no_pemeriksaan }}', tabel: 'pemeriksaan' })"
                                                class="flex items-center px-3 py-1.5 bg-[#d65f5f] text-white text-xs font-bold rounded-md hover:bg-[#b54d4d] cursor-pointer">
                                                <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                                            </button>
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
                    Total terdaftar: <span class="text-gray-600 font-bold not-italic">{{ $pemeriksaan->count() }} riwayat
                        pemeriksaan</span>
                </p>
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

    {{-- MODAL TOOLTIP GLOBAL (Statis, diam di tempat) --}}
    <div x-data="{ show: false, text: '', x: 0, y: 0 }" {{-- UBAH BARIS INI: Tangkap data teks beserta koordinat x dan y yang dikirim --}}
        @tampilkan-catatan.window="show = true; text = $event.detail.teks; x = $event.detail.x; y = $event.detail.y"
        @sembunyikan-catatan.window="show = false" x-show="show" x-transition.opacity.duration.200ms
        class="fixed z-[99999] bg-gray-900 text-white text-xs p-4 rounded-xl shadow-2xl max-w-[350px] pointer-events-none border border-gray-700"
        :style="`top: ${y > window.innerHeight / 2 ? y - $el.offsetHeight - 15 : y + 15}px; left: ${x > window.innerWidth / 2 ? x - $el.offsetWidth - 15 : x + 15}px;`"
        style="display: none;">

        <p x-text="text" class="whitespace-pre-wrap leading-relaxed break-words"></p>
    </div>
@endsection
