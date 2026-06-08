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
        <a href="{{ route('pemesanan-riwayat') }}">
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
