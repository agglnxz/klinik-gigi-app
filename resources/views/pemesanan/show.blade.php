@extends('layouts.main')

@section('title', 'Detail Pemesanan Pasien')

@section('content')
<div class="space-y-6 max-w-4xl">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('pemesanan.index') }}" class="text-[11px] text-gray-400 hover:text-[#176851] font-bold uppercase tracking-widest flex items-center gap-1.5 mb-2 transition">
                <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Daftar
            </a>
            <h3 class="text-2xl font-black text-gray-900 tracking-tight">Detail Pemesanan Pasien</h3>
            <p class="text-sm text-gray-400 font-light mt-0.5">{{ $data->no_pemesanan }}</p>
        </div>

        {{-- Badge Status Booking --}}
        @php
            $bookingColors = [
                'pending'     => 'bg-gray-100 text-gray-700',
                'proses_lab'  => 'bg-blue-100 text-blue-700',
                'siap_pasang' => 'bg-amber-100 text-amber-700',
                'selesai'     => 'bg-green-100 text-green-700',
            ];
            $color = $bookingColors[$data->status_booking] ?? 'bg-gray-100 text-gray-700';
        @endphp
        <span class="{{ $color }} px-4 py-2 rounded-full text-[12px] font-black uppercase tracking-wider whitespace-nowrap shadow-sm">
            Status: {{ strtoupper(str_replace('_', ' ', $data->status_booking)) }}
        </span>
    </div>

    {{-- INFORMASI PASIEN --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Informasi Rekam Medis Pasien</p>
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
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Dokter Pemeriksa</p>
                <p class="text-[14px] font-bold text-gray-800">{{ $data->pemeriksaan->dokter->nama ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- INFORMASI TRANSAKSI BOOKING --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Informasi Booking & Transaksi</p>
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Nomor Pemesanan</p>
                <p class="text-[14px] font-bold text-gray-800">{{ $data->no_pemesanan }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Tanggal Pemesanan Dibuat</p>
                <p class="text-[14px] font-bold text-gray-800">
                    {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y, H:i') }} WIB
                </p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Status Pembayaran Pasien</p>
                @if($data->status_bayar_pasien === 'sudah_lunas')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                        <i class="fa-solid fa-circle-check mr-1.5 text-[10px]"></i> Sudah Lunas
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                        <i class="fa-solid fa-circle-exclamation mr-1.5 text-[10px]"></i> Belum Lunas
                    </span>
                @endif
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Status Pengerjaan Laboratorium</p>
                @if($data->pesananLab)
                    <p class="text-[13px] font-bold text-blue-700">
                        Sudah dikirim ke: {{ $data->pesananLab->lab->nama_lab ?? 'Lab Mitra' }}
                        @if($data->pesananLab->tanggal_tiba)
                            <span class="text-green-600 block text-xs">(Gigi telah tiba di klinik: {{ \Carbon\Carbon::parse($data->pesananLab->tanggal_tiba)->translatedFormat('d M Y') }})</span>
                        @else
                            <span class="text-amber-600 block text-xs">(Estimasi selesai: {{ \Carbon\Carbon::parse($data->pesananLab->estimasi_selesai)->translatedFormat('d M Y') }})</span>
                        @endif
                    </p>
                @else
                    <p class="text-[13px] font-semibold text-gray-400 italic">Belum dikirimkan ke laboratorium</p>
                @endif
            </div>
        </div>
    </div>

    {{-- RINCIAN JENIS GIGI --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        @php
            $grouped = $data->items->groupBy(fn($i) => $i->jenisGigi->nama_jenis ?? 'Item Terhapus');
        @endphp
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">
            Rincian Item Gigi ({{ $grouped->count() }} Jenis · {{ $data->items->count() }} Total Unit)
        </p>
        <div class="space-y-3">
            @forelse($grouped as $nama => $items)
                <div class="flex items-center justify-between p-3.5 bg-gray-50/70 rounded-xl border border-gray-100">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-[#e7f5f1] text-[#176851] text-xs font-black flex items-center justify-center shrink-0">
                            {{ $loop->iteration }}
                        </span>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $nama }}</p>
                            <p class="text-xs text-gray-400">Harga Satuan Snapshot: Rp {{ number_format($items->first()->harga_jual_pasien ?? ($items->first()->jenisGigi->estimasi_biaya ?? 0), 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-[#176851] bg-[#e7f5f1] px-3 py-1 rounded-full">
                            {{ $items->count() }} Unit Gigi
                        </span>
                        <p class="text-xs font-black text-gray-700 mt-1">
                            Rp {{ number_format(($items->first()->harga_jual_pasien ?? 0) * $items->count(), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-xs text-gray-400 italic">Tidak ada item gigi tercatat.</p>
            @endforelse
        </div>
    </div>

    {{-- INFORMASI KEUANGAN PASIEN --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Informasi Keuangan Pasien</p>
        <div class="grid grid-cols-4 gap-6">
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Total Biaya Gigi</p>
                <p class="text-[16px] font-bold text-gray-800">
                    Rp {{ number_format($data->items->sum('harga_jual_pasien'), 0, ',', '.') }}
                </p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Biaya Pemasangan</p>
                <p class="text-[16px] font-bold text-gray-800">
                    Rp {{ number_format($data->biaya_pemasangan, 0, ',', '.') }}
                </p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Diskon Pasien</p>
                <p class="text-[16px] font-bold text-amber-600">
                    {{ $data->diskon > 0 ? (int)$data->diskon . '%' : '0%' }}
                </p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Total Tagihan Pasien</p>
                <p class="text-[18px] font-black text-teal-800">
                    Rp {{ number_format($data->harga_pasien, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- TOMBOL AKSI --}}
    <div class="flex items-center gap-3 pt-2">
        @if (auth()->user()->role === 'Admin')
            {{-- Tombol Kirim ke Lab jika belum dikirim --}}
            @if ($data->status_booking === 'pending')
                <a href="{{ route('pesanan-lab.create', $data->id) }}"
                    class="px-5 py-2.5 bg-[#0284c7] text-white text-sm rounded-lg hover:bg-[#0369a1] transition font-bold flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-paper-plane"></i> Kirim ke Lab
                </a>
            @endif

            <a href="{{ route('pemesanan.invoice', $data->id) }}" target="_blank"
                class="px-5 py-2.5 bg-[#3b4ab5] text-white text-sm rounded-lg hover:bg-[#2f3993] transition font-bold flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-print"></i> Cetak Invoice
            </a>
        @endif

        @if (auth()->user()->role === 'Direktur')
            <a href="{{ route('pemesanan.edit', $data->id) }}"
                class="px-5 py-2.5 bg-[#176851] text-white text-sm rounded-lg hover:bg-[#357a66] transition font-bold flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-pen"></i> Edit Pemesanan
            </a>
        @endif

        <a href="{{ route('pemesanan.index') }}"
            class="px-5 py-2.5 bg-gray-100 text-gray-600 text-sm rounded-lg hover:bg-gray-200 transition font-bold flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

</div>
@endsection
