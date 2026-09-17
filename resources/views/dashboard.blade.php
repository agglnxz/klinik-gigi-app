@extends('layouts.main')

@section('title', 'Dashboard Utama')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Utama</h2>
        <p class="text-gray-500 text-sm">Selamat datang di Sistem Manajemen Protesa Klinik Winardi.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('pasien.index') }}">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-teal-100 text-teal-600 rounded-lg">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                    <span class="text-[10px] font-bold bg-teal-50 text-teal-600 px-2 py-1 rounded">AKTIF</span>
                </div>
                <p class="text-gray-400 text-xs font-semibold uppercase">Total Pasien</p>
                <h3 class="text-3xl font-bold text-gray-800">
                    {{ $totalPasien }}
                </h3>
            </div>
        </a>
        <a href="{{ route('pemesanan.index') }}">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-teal-100 text-teal-600 rounded-lg">
                        <i class="fa-solid fa-recycle"></i>
                    </div>
                    <span class="text-[10px] font-bold bg-teal-50 text-teal-600 px-2 py-1 rounded">PROSES</span>
                </div>
                <p class="text-gray-400 text-xs font-semibold uppercase">Pesanan Proses</p>
                <h3 class="text-3xl font-bold text-gray-800">
                    {{ $pesananProses }}
                </h3>
            </div>
        </a>
        <a href="{{ route('laporan.index') }}">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-teal-100 text-teal-600 rounded-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <span class="text-[10px] font-bold bg-teal-50 text-teal-600 px-2 py-1 rounded">SELESAI</span>
                </div>
                <p class="text-gray-400 text-xs font-semibold uppercase">Pesanan Selesai</p>
                <h3 class="text-3xl font-bold text-gray-800">
                    {{ $pesananSelesai }}
                </h3>
            </div>
        </a>
    </div>

    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-100 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Laporan Pesanan</h3>
                <p class="text-sm text-gray-500 mt-1">Pantau pesanan yang belum tiba dan sudah tiba di klinik.</p>
            </div>
            <a href="{{ route('pemesanan.index') }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-[#176851] hover:text-[#124d3d]">
                Lihat semua <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-6 bg-gray-50/70 border-b border-gray-100">
            <div class="flex items-center gap-4 bg-white rounded-xl border border-amber-100 p-4">
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Belum Tiba</p>
                    <p class="text-2xl font-black text-gray-800">{{ $pesananBelumTiba }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4 bg-white rounded-xl border border-teal-100 p-4">
                <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Sudah Tiba</p>
                    <p class="text-2xl font-black text-gray-800">{{ $pesananSudahTiba }}</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-gray-500">No. Pesanan</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-gray-500">Pasien</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-gray-500">Laboratorium</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-gray-500">Estimasi Selesai
                        </th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-gray-500">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($laporanPesanan as $pesanan)
                        @php
                            $sudahTiba = in_array($pesanan->status_pemesanan, ['tiba_di_klinik', 'selesai']);
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-[#176851]">{{ $pesanan->no_pemesanan }}</td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $pesanan->pemeriksaan->pasien->nama ?? 'Pasien Terhapus' }}</p>
                                <p class="text-[10px] text-gray-400">{{ $pesanan->pemeriksaan->pasien->no_rm ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $pesanan->lab->nama_lab ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ optional($pesanan->estimasi_selesai)->translatedFormat('d M Y') ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $sudahTiba ? 'bg-teal-50 text-teal-700 border border-teal-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                                    {{ $sudahTiba ? 'Sudah Tiba' : 'Belum Tiba' }}
                                </span>
                                <p class="text-[10px] text-gray-400 mt-1">
                                    {{ str_replace('_', ' ', ucfirst($pesanan->status_pemesanan)) }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400">Belum ada data pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="bg-[#f0f9f4] p-8 rounded-2xl flex justify-between items-center shadow-md border border-gray-100">
        <div class="max-w-md">
            <span class="bg-[#4d9078] text-white text-[10px] px-3 py-1 rounded-full font-bold">INFO SISTEM</span>
            <h3 class="text-2xl font-bold text-gray-800 mt-4 leading-tight">Optimalkan Alur Kerja Protesa Anda Secara
                Real-time.</h3>
            <p class="text-gray-500 text-sm mt-4">Gunakan dashboard ini untuk memantau setiap langkah pembuatan protesa
                gigi...</p>
            @if (Auth::user()->role === 'Admin')
                <a href="{{ route('pemeriksaan.index') }}">
                    <button class="mt-6 bg-[#1b5e4b] text-white px-6 py-2 rounded-md font-semibold">Mulai Pemeriksaan
                        Baru</button>
                </a>
            @endif
        </div>
        <img src="{{ asset('images/dental-tools.png') }}" class="w-72 h-48 object-cover rounded-xl shadow-lg">
    </div>
@endsection
