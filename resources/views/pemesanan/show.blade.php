@extends('layouts.main')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="space-y-6 max-w-4xl">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('pemesanan.index') }}" class="text-[11px] text-gray-400 hover:text-[#176851] font-bold uppercase tracking-widest flex items-center gap-1.5 mb-2 transition">
                <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Daftar
            </a>
            <h3 class="text-2xl font-black text-gray-900 tracking-tight">Detail Pemesanan</h3>
            <p class="text-sm text-gray-400 font-light mt-0.5">{{ $data->no_pemesanan }}</p>
        </div>

        {{-- Badge Status --}}
        @php
            $statusColors = [
                'dalam_proses'   => 'bg-blue-100 text-blue-700',
                'tiba_di_klinik' => 'bg-yellow-100 text-yellow-700',
                'selesai'        => 'bg-green-100 text-green-700',
                'dibatalkan'     => 'bg-gray-100 text-gray-700'
            ];
            $color = $statusColors[$data->status_pemesanan] ?? 'bg-gray-100 text-gray-700';
        @endphp
        <span class="{{ $color }} px-4 py-2 rounded-full text-[12px] font-black uppercase tracking-wider whitespace-nowrap">
            {{ str_replace('_', ' ', $data->status_pemesanan) }}
        </span>
    </div>

    {{-- INFORMASI PASIEN --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Informasi Pasien</p>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Nama Pasien</p>
                <p class="text-[14px] font-bold text-gray-800">{{ $data->pemeriksaan->pasien->nama ?? 'Pasien Terhapus' }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">No Rekam Medis</p>
                <p class="text-[14px] font-bold text-gray-800">{{ $data->pemeriksaan->pasien->no_rm ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">No Pemeriksaan</p>
                <p class="text-[14px] font-bold text-gray-800">{{ $data->pemeriksaan->no_pemeriksaan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Tanggal Pemeriksaan</p>
                <p class="text-[14px] font-bold text-gray-800">
                    {{ $data->pemeriksaan->tanggal ? \Carbon\Carbon::parse($data->pemeriksaan->tanggal)->translatedFormat('d F Y') : '-' }}
                </p>
            </div>
        </div>
    </div>

    {{-- INFORMASI PEMESANAN --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Informasi Pemesanan</p>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Laboratorium</p>
                <p class="text-[14px] font-bold text-gray-800">{{ $data->lab->nama_lab ?? '-' }}</p>
                <p class="text-[11px] text-gray-400">{{ $data->lab->alamat ?? '' }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">No Pemesanan</p>
                <p class="text-[14px] font-bold text-gray-800">{{ $data->no_pemesanan }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Tanggal Dikirim</p>
                <p class="text-[14px] font-bold text-gray-800 uppercase">
                    {{ \Carbon\Carbon::parse($data->tanggal_dikirim)->translatedFormat('d F Y') }}
                </p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Estimasi Selesai</p>
                <p class="text-[14px] font-bold text-gray-800 uppercase">
                    {{ \Carbon\Carbon::parse($data->estimasi_selesai)->translatedFormat('d F Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- JENIS GIGI --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        @php
            $grouped = $data->items->groupBy(fn($i) => $i->jenisGigi->nama_jenis ?? 'Item Terhapus');
        @endphp
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">
            Jenis Gigi ({{ $grouped->count() }} Jenis · {{ $data->items->count() }} Item)
        </p>
        <div class="grid grid-flow-col grid-rows-8 gap-x-6 gap-y-2">
            @forelse($grouped as $nama => $items)
                <div class="flex items-center gap-3 py-2 border-b border-gray-50 min-w-[200px]">
                    <span class="w-6 h-6 rounded-full bg-[#e7f5f1] text-[#176851] text-[10px] font-black flex items-center justify-center shrink-0">
                        {{ $loop->iteration }}
                    </span>
                    <p class="text-[12px] font-semibold text-gray-800 flex-1">{{ $nama }}</p>
                    <span class="text-[10px] font-black text-[#176851] bg-[#e7f5f1] px-2 py-0.5 rounded-full whitespace-nowrap shrink-0">
                        {{ $items->count() }} gigi
                    </span>
                </div>
            @empty
                <p class="text-[12px] text-gray-400 italic">Tidak ada item gigi tercatat.</p>
            @endforelse
        </div>
    </div>

    {{-- INFORMASI KEUANGAN --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Informasi Keuangan</p>
        <div class="grid grid-cols-3 gap-6">
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Biaya Lab</p>
                <p class="text-[18px] font-black text-gray-800">Rp {{ number_format($data->biaya_lab, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Harga Pasien</p>
                <p class="text-[18px] font-black text-gray-800">Rp {{ number_format($data->harga_pasien, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Status Bayar Lab</p>
                @if($data->status_bayar_lab === 'sudah_lunas')
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[11px] font-bold">Lunas</span>
                @else
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[11px] font-bold">Belum Lunas</span>
                @endif
            </div>
        </div>
    </div>

    {{-- TOMBOL AKSI --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('pemesanan.edit', $data->id) }}"
            class="px-5 py-2.5 bg-[#176851] text-white text-sm rounded-lg hover:bg-[#357a66] transition font-bold flex items-center gap-2">
            <i class="fa-solid fa-pen"></i> Edit Pemesanan
        </a>
        <a href="{{ route('pemesanan.index') }}"
            class="px-5 py-2.5 bg-gray-100 text-gray-600 text-sm rounded-lg hover:bg-gray-200 transition font-bold flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

</div>
@endsection
