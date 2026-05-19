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
            <a href="{{ route('pemesanan.create') }}">
                <button class="bg-[#176851] hover:bg-[#357a66] text-white px-5 py-2.5 rounded-md text-sm font-bold flex items-center gap-2 transition-all shadow-sm">
                    <i class="fa-solid fa-plus text-xs"></i> Tambah Pemesanan
                </button>
            </a>
        </div>

        {{-- STATISTIK WIDGET --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-[#e7f5f1] text-[#41917a] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Total Pemesanan</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ $data->count() }}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6">
                <div class="bg-[#e7f5f1] text-[#176851] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-spinner"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Sedang Diproses</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ $data->where('status_pemesanan', 'Dalam_proses')->count() }}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-[#e7f5f1] text-[#176851] w-14 h-14 rounded-2xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Selesai</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ $data->where('status_pemesanan', 'Selesai')->count() }}</h3>
                </div>
            </div>
        </div>

        {{-- BARIS PENCARIAN & FILTER --}}
        <div class="bg-[#F3F4F3] px-4 py-3 rounded-2xl flex flex-wrap lg:flex-nowrap justify-between items-center gap-3 border border-gray-100">
            <div class="relative w-full max-w-[380px]">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input type="text" placeholder="Cari nomor pesanan, lab..."
                    class="w-full pl-11 pr-4 py-2.5 text-xs border-none rounded-xl bg-white ring-1 ring-gray-100 focus:ring-2 focus:ring-teal-500 outline-none transition-all shadow-sm placeholder:text-gray-600 font-medium">
            </div>

            <button class="px-3 py-2.5 text-[10px] font-black text-gray-500 bg-white ring-1 ring-gray-100 rounded-xl flex items-center gap-2 hover:bg-gray-50 transition-all shadow-sm uppercase tracking-widest whitespace-nowrap">
                <i class="fa-solid fa-download text-gray-400 text-xs"></i> Ekspor Data
            </button>
        </div>

        {{-- TABEL DATA --}}
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full min-w-[1900px] text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-100">
                            <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">No Pemesanan</th>
                            <th class="w-[220px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Nama Pasien</th>
                            <th class="w-[180px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Jenis Gigi</th>
                            <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Tanggal Kirim</th>
                            <th class="w-[180px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Estimasi Selesai</th>
                            <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Laboratorium</th>
                            <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Biaya Lab</th>
                            <th class="w-[180px] px-5 py-4 text-[10px] font-bold text-gray-900 uppercase tracking-wide">Bayar Lab</th>
                            <th class="w-[160px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Harga Pasien</th>
                            <th class="w-[140px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide">Status</th>
                            <th class="w-[200px] px-5 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-wide text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">

                        @forelse ($data as $item)
                            <tr class="hover:bg-gray-50/30 transition">
                                <td class="px-5 py-4 text-[13px] font-bold text-gray-800">
                                    {{ $item->no_pemesanan }}
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-bold text-gray-800 text-[13px]">{{ $item->pemeriksaan->pasien->nama ?? 'Pasien Terhapus' }}</p>
                                    <p class="text-[10px] text-gray-400 font-normal uppercase mt-0.5 tracking-tighter">
                                        {{ $item->pemeriksaan->pasien->no_rm ?? '-' }}
                                    </p>
                                </td>

                                {{-- KOLOM JENIS GIGI MULTIPLE --}}
                                <td class="px-5 py-4">
                                    <p class="text-[13px] font-bold text-gray-800">{{ $item->items->count() }} Jenis</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5 max-w-[150px]" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
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
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[11px] font-bold">Lunas</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[11px] font-bold">Belum Lunas</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-[13px] text-gray-800 font-bold">
                                    Rp {{ number_format($item->harga_pasien, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $statusColors = [
                                            'dalam_proses'   => 'bg-blue-100 text-blue-700',
                                            'tiba_di_klinik' => 'bg-yellow-100 text-yellow-700',
                                            'selesai'        => 'bg-green-100 text-green-700',
                                            'dibatalkan'     => 'bg-gray-100 text-gray-700'
                                        ];
                                        $color = $statusColors[$item->status_pemesanan] ?? 'bg-red-50 text-red-500 border border-red-200';
                                        $labelStatus = str_replace('_', ' ', $item->status_pemesanan);
                                    @endphp
                                    <span class="{{ $color }} px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider whitespace-nowrap">
                                        {{ $labelStatus }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-center items-center gap-2">

                                        {{-- Tombol Detail (BARU) --}}
                                        <a href="{{ route('pemesanan.show', $item->id) }}"
                                            class="px-3 py-1.5 bg-[#e7f5f1] text-[#176851] text-xs rounded-md hover:bg-[#176851] hover:text-white transition flex items-center font-bold">
                                            <i class="fa-solid fa-eye mr-1.5"></i> Detail
                                        </a>

                                        <a href="{{ route('pemesanan.edit', $item->id) }}"
                                            class="px-3 py-1.5 bg-[#59a38a] text-white text-xs rounded-md hover:bg-[#46826d] transition flex items-center">
                                            <i class="fa-solid fa-pen mr-1.5"></i> Edit
                                        </a>

                                        {{-- KUNCI RBAC DIREKTUR --}}
                                        @if (Auth::check() && Auth::user()->role === 'Direktur')
                                            <form action="{{ route('pemesanan.destroy', $item->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data pemesanan ini?')"
                                                    class="px-3 py-1.5 bg-[#d65f5f] text-white text-xs rounded-md hover:bg-[#b54d4d] transition flex items-center cursor-pointer">
                                                    <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-6 py-10 text-center text-gray-400 italic text-xs">
                                    Belum ada data pemesanan laboratorium.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="bg-[#F3F4F3] px-8 py-4 border-t border-gray-100">
                <p class="text-[11px] font-normal text-gray-400 italic">Total terdaftar: <span class="text-gray-600 font-bold not-italic">{{ $data->count() }} pesanan</span></p>
            </div>
        </div>
    </div>
@endsection
