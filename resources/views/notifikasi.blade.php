@extends('layouts.main')

@section('title', 'Notifikasi')

@section('content')
<div class="space-y-6 max-w-4xl">

    {{-- HEADER --}}
    <div>
        <h3 class="text-2xl font-black text-gray-900 tracking-tight">NOTIFICATION</h3>
        <p class="text-sm text-gray-400 font-light mt-0.5">
            Tuliskan detail pemeriksaan, keluhan pasien, atau diagnosa awal di sini...
        </p>
    </div>

    {{-- NOTIFIKASI HARI INI --}}
    @php
        $hariIni    = $notifikasi->filter(fn($n) => \Carbon\Carbon::parse($n->created_at)->isToday());
        $kemarin    = $notifikasi->filter(fn($n) => \Carbon\Carbon::parse($n->created_at)->isYesterday());
        $lebihLama  = $notifikasi->filter(fn($n) =>
            !\Carbon\Carbon::parse($n->created_at)->isToday() &&
            !\Carbon\Carbon::parse($n->created_at)->isYesterday()
        );
    @endphp

    {{-- ===== HARI INI ===== --}}
    @if($hariIni->isNotEmpty())
    <div>
        <p class="text-[13px] font-bold text-gray-700 mb-3">Hari Ini</p>
        <div class="space-y-3">
            @foreach($hariIni as $item)
                <a href="{{ route('pemesanan.index') }}"
                   class="block bg-white border border-gray-100 rounded-2xl px-6 py-4 shadow-sm hover:shadow-md hover:border-gray-200 transition-all duration-200 group">
                    <div class="flex items-start justify-between gap-4">

                        {{-- KIRI: Badge + Tanggal + Pesan --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1.5 flex-wrap">

                                {{-- Badge Status --}}
                                @if($item->status === 'segera_tiba')
                                    <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#f59e0b] text-white">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white inline-block"></span>
                                        Segera Tiba
                                    </span>
                                @elseif($item->status === 'terlambat')
                                    <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#ef4444] text-white">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white inline-block"></span>
                                        Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-gray-400 text-white">
                                        Info
                                    </span>
                                @endif

                                {{-- Tanggal --}}
                                <span class="text-[11px] text-gray-400 font-medium">
                                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                                </span>
                            </div>

                            {{-- Isi Notifikasi --}}
                            <p class="text-[13px] text-gray-700 leading-relaxed">
                                {!! $item->pesan !!}
                            </p>
                        </div>

                        {{-- KANAN: Waktu + Lihat Detail --}}
                        <div class="flex flex-col items-end justify-between gap-3 shrink-0 min-w-[120px]">
                            <p class="text-[11px] text-gray-400 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                            </p>
                            <span class="text-[12px] font-bold text-[#176851] group-hover:underline whitespace-nowrap">
                                Lihat Detail
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== KEMARIN ===== --}}
    @if($kemarin->isNotEmpty())
    <div>
        <p class="text-[13px] font-bold text-gray-700 mb-3">Kemarin</p>
        <div class="space-y-3">
            @foreach($kemarin as $item)
                <a href="{{ route('pemesanan.show', $item->pemesanan_id) }}"
                   class="block bg-white border border-gray-100 rounded-2xl px-6 py-4 shadow-sm hover:shadow-md hover:border-gray-200 transition-all duration-200 group">
                    <div class="flex items-start justify-between gap-4">

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                @if($item->status === 'segera_tiba')
                                    <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#f59e0b] text-white">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white inline-block"></span>
                                        Segera Tiba
                                    </span>
                                @elseif($item->status === 'terlambat')
                                    <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#ef4444] text-white">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white inline-block"></span>
                                        Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-gray-400 text-white">
                                        Info
                                    </span>
                                @endif
                                <span class="text-[11px] text-gray-400 font-medium">
                                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            <p class="text-[13px] text-gray-700 leading-relaxed">
                                {!! $item->pesan !!}
                            </p>
                        </div>

                        <div class="flex flex-col items-end justify-between gap-3 shrink-0 min-w-[120px]">
                            <p class="text-[11px] text-gray-400 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                            </p>
                            <span class="text-[12px] font-bold text-[#176851] group-hover:underline whitespace-nowrap">
                                Lihat Detail
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== LEBIH LAMA ===== --}}
    @if($lebihLama->isNotEmpty())
    <div>
        <p class="text-[13px] font-bold text-gray-700 mb-3">Lebih Lama</p>
        <div class="space-y-3">
            @foreach($lebihLama as $item)
                <a href="{{ route('pemesanan.show', $item->pemesanan_id) }}"
                   class="block bg-white border border-gray-100 rounded-2xl px-6 py-4 shadow-sm hover:shadow-md hover:border-gray-200 transition-all duration-200 group opacity-80 hover:opacity-100">
                    <div class="flex items-start justify-between gap-4">

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                @if($item->status === 'segera_tiba')
                                    <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#f59e0b] text-white">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white inline-block"></span>
                                        Segera Tiba
                                    </span>
                                @elseif($item->status === 'terlambat')
                                    <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#ef4444] text-white">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white inline-block"></span>
                                        Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-gray-400 text-white">
                                        Info
                                    </span>
                                @endif
                                <span class="text-[11px] text-gray-400 font-medium">
                                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            <p class="text-[13px] text-gray-700 leading-relaxed">
                                {!! $item->pesan !!}
                            </p>
                        </div>

                        <div class="flex flex-col items-end justify-between gap-3 shrink-0 min-w-[120px]">
                            <p class="text-[11px] text-gray-400 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                            </p>
                            <span class="text-[12px] font-bold text-[#176851] group-hover:underline whitespace-nowrap">
                                Lihat Detail
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- JIKA TIDAK ADA NOTIFIKASI SAMA SEKALI --}}
    @if($notifikasi->isEmpty())
    <div class="bg-white border border-gray-100 rounded-2xl px-6 py-16 text-center shadow-sm">
        <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <i class="fa-regular fa-bell text-gray-400 text-2xl"></i>
        </div>
        <p class="text-sm font-semibold text-gray-500">Tidak ada notifikasi</p>
        <p class="text-xs text-gray-400 mt-1">Semua pesanan berjalan tepat waktu.</p>
    </div>
    @endif

</div>
@endsection
