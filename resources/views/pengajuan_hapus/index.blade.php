@extends('layouts.main')

@section('title', 'Persetujuan Penghapusan Data')

@section('content')

@php
use Illuminate\Support\Str;
@endphp

<div class="space-y-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Persetujuan Penghapusan Data</h1>
        <p class="text-sm text-gray-500 mt-0.5">Kelola permintaan penghapusan data dari admin modul.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Menunggu Persetujuan</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $menunggu }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Disetujui Bulan Ini</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $disetujui }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Ditolak Bulan Ini</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $ditolak }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 flex justify-between items-center bg-white border-b border-gray-50">
            <h2 class="text-base font-bold text-gray-800">Permintaan Menunggu</h2>
            <button class="flex items-center space-x-1 text-xs font-bold text-teal-600 hover:text-teal-700 transition">
                <i class="fa-solid fa-sliders text-sm"></i>
                <span>Filter</span>
            </button>
        </div>

        <div class="overflow-x-auto max-w-full custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1400px]">
                <thead>
                    <tr class="bg-[#F3F4F3] border-b border-gray-100">
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Tanggal Pengajuan</th>
                        <th class="w-[150px] px-8 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Modul</th>
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Nama Data</th>
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Pemohon</th>
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Alasan</th>
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($pengajuan->where('status_approval', 'Pending') as $item)
                    <tr class="hover:bg-gray-50/30 transition">
                        <td class="px-8 py-6 text-gray-600 font-medium">
                            {{ $item->created_at->format('d M Y H:i') }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 bg-purple-100 text-purple-600 rounded-full text-[10px] font-bold uppercase">
                                {{ $item->nama_tabel }}
                            </span>
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $item->nama_data }}
                        </td>

                        <td class="px-8 py-6 text-gray-600">
                            {{ $item->pemohon->name ?? '-' }}
                        </td>

                        <td class="px-8 py-6 text-gray-600">
                            {{ Str::limit($item->alasan_hapus, 40) }}
                        </td>

                        <td class="px-8 py-6">
                            <div class="flex gap-2">

                                <form action="{{ route('pengajuan-hapus.approve', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-2 bg-green-600 text-white rounded">
                                        Setujui
                                    </button>
                                </form>

                                <form action="{{ route('pengajuan-hapus.reject', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-2 bg-red-600 text-white rounded">
                                        Tolak
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            Tidak ada pengajuan yang menunggu persetujuan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 text-[11px] text-gray-400 font-semibold shadow-inner">
            Menampilkan {{ $pengajuan->where('status_approval', 'Pending')->count() }} Permintaan Menunggu Penghapusan Data
        </div>
    </div>


    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 flex justify-between items-center bg-white border-b border-gray-50">
            <h2 class="text-base font-bold text-gray-800">Riwayat Persetujuan</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F3F4F3] border-b border-gray-100">
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Tanggal Pengajuan</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Modul</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Nama Data</th>
                        <th class="px-6 py-4 text-center text-[11px] font-bold text-gray-900 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-xs text-gray-600">
                    @forelse($pengajuan->where('status_approval', '!=', 'Pending') as $item)
                    <tr class="hover:bg-gray-50/40 transition">

                        <td class="px-6 py-4 text-gray-500 font-medium">
                            {{ $item->created_at->format('d M Y H:i') }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 bg-purple-100 text-purple-600 rounded-full text-[10px] font-bold uppercase">
                                {{ $item->nama_tabel }}
                            </span>
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $item->nama_data }}
                        </td>

                        <td class="px-6 py-4">
                            @if($item->status_approval == 'Disetujui')
                                <div class="flex justify-center items-center space-x-1 text-[#137333] font-bold bg-[#E6F4EA] w-24 mx-auto py-1 rounded-full text-[11px]">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                    <span>Disetujui</span>
                                </div>
                            @else
                                <div class="flex justify-center items-center space-x-1 text-[#C5221F] font-bold bg-[#FCE8E6] w-24 mx-auto py-1 rounded-full text-[11px]">
                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                    <span>Ditolak</span>
                                </div>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500">
                            Belum ada riwayat persetujuan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 text-[11px] text-gray-400 font-semibold shadow-inner">
            Menampilkan {{ $pengajuan->where('status_approval', '!=', 'Pending')->count() }} Riwayat Persetujuan Penghapusan Data
        </div>
    </div>

</div>

@endsection
