@extends('layouts.main')

@section('title', 'Daftar Pesanan Laboratorium')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-black-1000">Daftar Pesanan Laboratorium</h3>
                <p class="text-sm text-gray-500 font-light">Monitoring pengerjaan protesa gigi, tracking vendor laboratorium, dan kedatangan barang</p>
            </div>
            @if (auth()->user()->role === 'Admin')
                <a href="{{ route('pesanan-lab.create') }}">
                    <button
                        class="bg-[#176851] hover:bg-[#357a66] text-white px-5 py-2.5 rounded-md text-sm font-bold flex items-center gap-2 transition-all shadow-sm cursor-pointer">
                        <i class="fa-solid fa-paper-plane text-xs"></i> Order ke Lab
                    </button>
                </a>
            @endif
        </div>

        {{-- STATISTIK WIDGET OPERASIONAL LAB --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            {{-- Card 1: Total Order Lab --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-[#e7f5f1] text-[#41917a] w-12 h-12 rounded-2xl flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Order Lab</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ $totalPesananLab }}</h3>
                    <span class="text-[10px] text-teal-600 font-medium">Semua SPK</span>
                </div>
            </div>

            {{-- Card 2: Sedang Diproses Lab --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-blue-50 text-blue-600 w-12 h-12 rounded-2xl flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-flask-vial animate-pulse"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sedang di Lab</p>
                    <h3 class="text-2xl font-black text-blue-700">{{ $sedangDiproses }}</h3>
                    <span class="text-[10px] text-blue-600 font-medium">Belum Tiba</span>
                </div>
            </div>

            {{-- Card 3: Sudah Tiba di Klinik --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-emerald-50 text-emerald-600 w-12 h-12 rounded-2xl flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-box-check"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sudah Tiba</p>
                    <h3 class="text-2xl font-black text-emerald-700">{{ $sudahTiba }}</h3>
                    <span class="text-[10px] text-emerald-600 font-medium">Siap Dipasang</span>
                </div>
            </div>

            {{-- Card 4: Jatuh Tempo / Lewat --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-red-50 text-red-500 w-12 h-12 rounded-2xl flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tenggat H-3</p>
                    <h3 class="text-2xl font-black text-red-600">{{ $jatuhTempo }}</h3>
                    <span class="text-[10px] text-red-600 font-medium">Perlu Ditekan</span>
                </div>
            </div>
        </div>

        {{-- BARIS PENCARIAN & FILTER MULTI-FUNGSI --}}
        <form action="{{ route('pesanan-lab.index') }}" method="GET" id="filter-form">
            <div class="bg-[#F3F4F3] px-4 py-3 rounded-2xl flex flex-wrap lg:flex-nowrap justify-between items-center gap-3 border border-gray-100">

                {{-- Input Pencarian --}}
                <div class="relative w-full max-w-[360px]">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari no pesanan, pasien, lab..."
                        class="w-full pl-4 pr-12 py-2.5 text-xs border-none rounded-xl bg-white ring-1 ring-gray-100 focus:ring-2 focus:ring-teal-500 outline-none transition-all shadow-sm placeholder:text-gray-600 font-medium">

                    <button type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#176851] text-white p-1.5 rounded-lg hover:bg-teal-700 transition flex items-center justify-center w-8 h-8 shadow-sm cursor-pointer"
                        title="Terapkan Pencarian">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </button>
                </div>

                {{-- Grouping Filter Kanan --}}
                <div class="flex items-center gap-3 flex-wrap lg:flex-nowrap">

                    {{-- Filter Status Kedatangan --}}
                    <select name="status_pengerjaan" onchange="document.getElementById('filter-form').submit()"
                        class="px-4 py-2.5 text-xs bg-white rounded-xl ring-1 ring-gray-100 font-bold text-gray-600 border-none shadow-sm cursor-pointer focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Kedatangan</option>
                        <option value="sedang_diproses" {{ request('status_pengerjaan') === 'sedang_diproses' ? 'selected' : '' }}>Belum Tiba</option>
                        <option value="sudah_tiba" {{ request('status_pengerjaan') === 'sudah_tiba' ? 'selected' : '' }}>Sudah Tiba</option>
                    </select>

                    {{-- Filter Status Bayar Lab --}}
                    <select name="status_bayar_lab" onchange="document.getElementById('filter-form').submit()"
                        class="px-4 py-2.5 text-xs bg-white rounded-xl ring-1 ring-gray-100 font-bold text-gray-600 border-none shadow-sm cursor-pointer focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Status Bayar</option>
                        <option value="belum_lunas" {{ request('status_bayar_lab') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="sudah_lunas" {{ request('status_bayar_lab') === 'sudah_lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                    </select>

                    {{-- Filter Vendor Lab --}}
                    <select name="id_lab" onchange="document.getElementById('filter-form').submit()"
                        class="px-4 py-2.5 text-xs bg-white rounded-xl ring-1 ring-gray-100 font-bold text-gray-600 border-none shadow-sm cursor-pointer focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Vendor Lab</option>
                        @foreach ($laboratoriumList as $lab)
                            <option value="{{ $lab->id }}" {{ request('id_lab') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->nama_lab }}
                            </option>
                        @endforeach
                    </select>

                    @if (request()->hasAny(['search', 'status_pengerjaan', 'status_bayar_lab', 'id_lab']))
                        <a href="{{ route('pesanan-lab.index') }}"
                            class="px-3 py-2 text-xs bg-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-300 transition"
                            title="Reset Filter">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- TABEL DATA PESANAN LAB --}}
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full min-w-[1350px] text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-100">
                            <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">No Pemesanan</th>
                            <th class="w-[200px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Nama Pasien</th>
                            <th class="w-[180px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Lab Tujuan</th>
                            <th class="w-[140px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Tanggal Kirim</th>
                            <th class="w-[150px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Estimasi Selesai</th>
                            <th class="w-[150px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Biaya Lab</th>
                            <th class="w-[150px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Status Bayar Lab</th>
                            <th class="w-[180px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Status Kedatangan</th>
                            <th class="w-[220px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">

                        @forelse ($data as $item)
                            <tr class="hover:bg-gray-50/30 transition">
                                {{-- 1. No Pemesanan --}}
                                <td class="px-5 py-4 text-[13px] font-bold text-gray-800">
                                    {{ $item->pemesanan->no_pemesanan ?? '-' }}
                                </td>

                                {{-- 2. Nama Pasien --}}
                                <td class="px-5 py-4">
                                    <p class="font-bold text-gray-800 text-[13px]">
                                        {{ $item->pemesanan->pemeriksaan->pasien->nama ?? 'Pasien Terhapus' }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 font-normal uppercase mt-0.5 tracking-tighter">
                                        RM: {{ $item->pemesanan->pemeriksaan->pasien->no_rm ?? '-' }}
                                    </p>
                                </td>

                                {{-- 3. Lab Tujuan --}}
                                <td class="px-5 py-4">
                                    <p class="font-bold text-gray-800 text-[13px]">
                                        {{ $item->lab->nama_lab ?? '-' }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 font-normal truncate max-w-[160px]">
                                        {{ $item->lab->alamat ?? '' }}
                                    </p>
                                </td>

                                {{-- 4. Tanggal Kirim --}}
                                <td class="px-5 py-4 text-[13px] text-gray-700 font-medium">
                                    {{ \Carbon\Carbon::parse($item->tanggal_dikirim)->translatedFormat('d M Y') }}
                                </td>

                                {{-- 5. Estimasi Selesai (dengan deteksi keterlambatan) --}}
                                <td class="px-5 py-4 text-[13px] font-medium">
                                    @php
                                        $isOverdue = is_null($item->tanggal_tiba) && \Carbon\Carbon::today()->gt(\Carbon\Carbon::parse($item->estimasi_selesai));
                                    @endphp
                                    <span class="{{ $isOverdue ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                        {{ \Carbon\Carbon::parse($item->estimasi_selesai)->translatedFormat('d M Y') }}
                                    </span>
                                    @if ($isOverdue)
                                        <span class="block text-[10px] text-red-500 font-bold uppercase tracking-tight">Lewat Estimasi</span>
                                    @endif
                                </td>

                                {{-- 6. Biaya Lab --}}
                                <td class="px-5 py-4 text-[13px] text-gray-800 font-bold">
                                    Rp {{ number_format($item->biaya_lab, 0, ',', '.') }}
                                </td>

                                {{-- 7. Status Bayar Lab --}}
                                <td class="px-5 py-4">
                                    @if (auth()->user()->role === 'Admin')
                                        <form action="{{ route('pesanan-lab.update-bayar', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status_bayar_lab" onchange="this.form.submit()"
                                                class="px-3 py-1 rounded-full text-[11px] font-bold cursor-pointer border-none shadow-sm focus:ring-2 focus:ring-teal-500 {{ $item->status_bayar_lab === 'sudah_lunas' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                <option value="belum_lunas" {{ $item->status_bayar_lab === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                                <option value="sudah_lunas" {{ $item->status_bayar_lab === 'sudah_lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                                            </select>
                                        </form>
                                    @else
                                        <span class="{{ $item->status_bayar_lab === 'sudah_lunas' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} px-3 py-1 rounded-full text-[11px] font-bold">
                                            {{ $item->status_bayar_lab === 'sudah_lunas' ? 'Lunas' : 'Belum Lunas' }}
                                        </span>
                                    @endif
                                </td>

                                {{-- 8. Status Kedatangan (Logika: Jika null -> Belum Tiba; Jika ada -> Sudah Tiba + Tgl) --}}
                                <td class="px-5 py-4">
                                    @if (is_null($item->tanggal_tiba))
                                        <span class="{{ $isOverdue ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-amber-100 text-amber-800' }} px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-clock text-[10px]"></i> Belum Tiba
                                        </span>
                                    @else
                                        <div>
                                            <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider inline-flex items-center gap-1.5">
                                                <i class="fa-solid fa-circle-check text-[10px]"></i> Sudah Tiba
                                            </span>
                                            <p class="text-[10px] text-gray-500 font-semibold mt-1">
                                                Tgl: {{ \Carbon\Carbon::parse($item->tanggal_tiba)->translatedFormat('d M Y') }}
                                            </p>
                                        </div>
                                    @endif
                                </td>

                                {{-- 9. Aksi --}}
                                <td class="px-5 py-4">
                                    <div class="flex justify-center items-center gap-1.5 flex-wrap">

                                        {{-- AKSI KHUSUS: Form / Tombol "Tandai Telah Tiba" jika tanggal_tiba null --}}
                                        @if (is_null($item->tanggal_tiba))
                                            <form action="{{ route('pesanan-lab.mark-as-arrived', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Konfirmasi bahwa gigi pesanan {{ $item->pemesanan->no_pemesanan ?? '' }} telah tiba di klinik hari ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="flex items-center px-2.5 py-1.5 bg-[#e7f5f1] text-[#176851] text-xs font-bold rounded-md hover:bg-[#176851] hover:text-white transition-colors duration-200 shadow-sm cursor-pointer"
                                                    title="Tandai Telah Tiba di Klinik (Ubah status booking ke Siap Pasang)">
                                                    <i class="fa-solid fa-box-open mr-1.5 text-xs"></i> Tandai Telah Tiba
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Edit Order Lab (Khusus Direktur) --}}
                                        @if (auth()->user()->role === 'Direktur')
                                            <a href="{{ route('pesanan-lab.edit', $item->id) }}"
                                                class="flex items-center px-2.5 py-1.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-md hover:bg-gray-200 transition-colors duration-200"
                                                title="Edit Data Pesanan Lab (Khusus Direktur)">
                                                <i class="fa-solid fa-pen text-xs mr-1"></i> Edit
                                            </a>
                                        @endif

                                        {{-- Detail Pemesanan Pasien Induk --}}
                                        @if ($item->id_pemesanan)
                                            <a href="{{ route('pemesanan.show', $item->id_pemesanan) }}"
                                                class="flex items-center px-2.5 py-1.5 bg-[#eef2ff] text-[#3b4ab5] text-xs font-bold rounded-md hover:bg-[#3b4ab5] hover:text-white transition-colors duration-200"
                                                title="Lihat Detail Booking Pasien">
                                                <i class="fa-solid fa-eye mr-1 text-xs"></i> Pasien
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-10 text-center text-gray-400 italic text-xs">
                                    Belum ada data pesanan laboratorium yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="bg-[#F3F4F3] px-8 py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-[11px] font-normal text-gray-400 italic order-2 sm:order-1">
                    Menampilkan data ke-{{ $data->firstItem() ?? 0 }} sampai {{ $data->lastItem() ?? 0 }} dari total <span
                        class="text-gray-600 font-bold not-italic">{{ $data->total() }} order lab</span>
                </p>

                <div class="order-1 sm:order-2 scale-90 origin-right global-pagination">
                    {{ $data->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

