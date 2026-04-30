@extends('layouts.main')

@section('title', 'Daftar Pasien')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Daftar Pasien</h2>
        <p class="text-gray-500 text-sm">Kelola data rekam medis dan informasi kontak pasien Anda.</p>
    </div>
    <button class="bg-[#4d9078] hover:bg-[#3d735f] text-white px-5 py-2 rounded-lg flex items-center text-sm font-semibold transition shadow-sm">
        <i class="fa-solid fa-plus mr-2"></i> Tambah Pasien Baru
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
        <div class="p-4 bg-teal-50 text-teal-600 rounded-xl">
            <i class="fa-solid fa-users text-xl"></i>
        </div>
        <div>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Pasien</p>
            <h3 class="text-2xl font-bold text-gray-800">1,284</h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
        <div class="p-4 bg-teal-50 text-teal-600 rounded-xl">
            <i class="fa-solid fa-user-plus text-xl"></i>
        </div>
        <div>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Pasien Baru (Bulan Ini)</p>
            <h3 class="text-2xl font-bold text-gray-800">42</h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
        <div class="p-4 bg-orange-50 text-orange-500 rounded-xl">
            <i class="fa-solid fa-calendar-check text-xl"></i>
        </div>
        <div>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Kunjungan Hari Ini</p>
            <h3 class="text-2xl font-bold text-gray-800">18</h3>
        </div>
    </div>
</div>

<div class="flex justify-between items-center mb-6">
    <div class="relative w-1/2">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
            <i class="fa-solid fa-magnifying-glass text-sm"></i>
        </span>
        <input type="text" placeholder="Cari nama atau nomor rekam medis..."
            class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none text-sm shadow-sm">
    </div>
    <button class="flex items-center px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 transition shadow-sm">
        <i class="fa-solid fa-save mr-2 text-gray-400"></i> Ekspor
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
    <div class="overflow-x-auto max-w-full custom-scrollbar">
        <table class="w-full text-left border-collapse min-w-[1200px]">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Nama Pasien</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">No. Rekam Medis</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-center">Jenis Kelamin</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">No. Telepon</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Laboratorium</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                <tr class="hover:bg-gray-50/30 transition">
                    <td class="px-8 py-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-xs uppercase">YF</div>
                            <div>
                                <p class="font-bold text-gray-800">Yossy Fira Rosdiana</p>
                                <p class="text-[10px] text-gray-400">Terakhir periksa: 10 April 2026</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-gray-500 font-medium">RM-2026-0410</td>
                    <td class="px-8 py-6 text-center text-gray-600">Perempuan</td>
                    <td class="px-8 py-6 text-gray-600 font-medium">081234567892</td>
                    <td class="px-8 py-6 text-gray-600">Winardi Dental Lab</td>
                    <td class="px-8 py-6">
                        <div class="flex justify-center space-x-2">
                            <button class="px-3 py-1.5 bg-[#59a38a] text-white text-xs rounded-md hover:bg-[#46826d] transition flex items-center">
                                <i class="fa-solid fa-pen mr-1.5"></i> Edit
                            </button>
                            <button class="px-3 py-1.5 bg-[#d65f5f] text-white text-xs rounded-md hover:bg-[#b54d4d] transition flex items-center">
                                <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50/30 transition">
                    <td class="px-8 py-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-700 flex items-center justify-center font-bold text-xs uppercase">IS</div>
                            <div>
                                <p class="font-bold text-gray-800">Inandiar Sharfina Fauzi</p>
                                <p class="text-[10px] text-gray-400">Terakhir periksa: 30 Februari 2026</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-gray-500 font-medium">RM-2026-0230</td>
                    <td class="px-8 py-6 text-center text-gray-600">Perempuan</td>
                    <td class="px-8 py-6 text-gray-600 font-medium">081234567892</td>
                    <td class="px-8 py-6 text-gray-600">Smile Care Lab</td>
                    <td class="px-8 py-6">
                        <div class="flex justify-center space-x-2">
                            <button class="px-3 py-1.5 bg-[#59a38a] text-white text-xs rounded-md">
                                <i class="fa-solid fa-pen mr-1.5"></i> Edit
                            </button>
                            <button class="px-3 py-1.5 bg-[#d65f5f] text-white text-xs rounded-md">
                                <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50/30 transition">
                    <td class="px-8 py-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-200 text-gray-700 flex items-center justify-center font-bold text-xs uppercase">MF</div>
                            <div>
                                <p class="font-bold text-gray-800">Moch Firman Triswanda</p>
                                <p class="text-[10px] text-gray-400">Terakhir periksa: 12 Januari 2026</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-gray-500 font-medium">RM-2026-0112</td>
                    <td class="px-8 py-6 text-center text-gray-600">Laki laki</td>
                    <td class="px-8 py-6 text-gray-600 font-medium">081234567892</td>
                    <td class="px-8 py-6 text-gray-600">Bright Teeth Lab</td>
                    <td class="px-8 py-6 text-center">
                        <div class="flex justify-center space-x-2">
                            <button class="px-3 py-1.5 bg-[#59a38a] text-white text-xs rounded-md">
                                <i class="fa-solid fa-pen mr-1.5"></i> Edit
                            </button>
                            <button class="px-3 py-1.5 bg-[#d65f5f] text-white text-xs rounded-md">
                                <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <div class="bg-gray-50/50 px-8 py-4 border-t border-gray-100">
        <p class="text-[11px] text-gray-400 font-medium">Menampilkan 1-5 dari 1,284 pasien</p>
    </div>
</div>
@endsection
