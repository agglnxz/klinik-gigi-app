@extends('layouts.main')

@section('title', 'Data Master Laboratorium')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Data Master</h2>
    <p class="text-gray-500 text-sm">Kelola entitas utama sistem Protesa Gigi Winardi</p>
</div>

<div class="flex space-x-2 bg-gray-100/50 p-1 rounded-xl w-fit mb-8">
    <a href="{{ route('dokter.index') }}" class="px-6 py-2 text-gray-500 hover:text-teal-700 font-medium text-sm transition">Dokter</a>
    <a href="{{ route('asisten.index') }}" class="px-6 py-2 text-gray-500 hover:text-teal-700 font-medium text-sm transition">Asisten</a>
    <a href="{{ route('laboratorium.index') }}" class="px-6 py-2 bg-white text-teal-700 shadow-sm rounded-lg font-medium text-sm">Laboratorium</a>
    <a href="{{ route('jenis-gigi.index') }}" class="px-6 py-2 text-gray-500 hover:text-teal-700 font-medium text-sm transition">Jenis Gigi</a>
</div>

<div class="flex justify-between items-end mb-6">
    <div>
        <h3 class="text-lg font-bold text-gray-800">Daftar Laboratorium</h3>
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total 12 Laboratorium</p>
    </div>
    <button class="bg-[#529e85] hover:bg-[#3d735f] text-white px-5 py-2 rounded-lg flex items-center text-sm font-semibold transition shadow-sm">
        <i class="fa-solid fa-plus mr-2"></i> Tambah Laboratorium
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">

    <div class="overflow-x-auto max-w-full custom-scrollbar">
        <table class="w-full text-left border-collapse min-w-[1000px]">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Nama Laboratorium</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Alamat</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Nomor HP</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                    <th class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                <tr class="hover:bg-gray-50/30 transition">
                    <td class="px-8 py-6">
                        <p class="font-bold text-gray-800">Estetika Gigi Lab</p>
                        <p class="text-[11px] text-gray-400">estetika.gigi@lab.id</p>
                    </td>
                    <td class="px-8 py-6 text-gray-600">Banyuwangi</td>
                    <td class="px-8 py-6 text-gray-600 font-medium">+628334667854</td>
                    <td class="px-8 py-6 text-center">
                        <span class="px-3 py-1 bg-teal-100 text-teal-600 text-[10px] font-bold rounded-full uppercase">Aktif</span>
                    </td>
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
                        <p class="font-bold text-gray-800">Lab. Dental Prima</p>
                        <p class="text-[11px] text-gray-400">dental.prima@lab.id</p>
                    </td>
                    <td class="px-8 py-6 text-gray-600">Bali</td>
                    <td class="px-8 py-6 text-gray-600 font-medium">+628886445321</td>
                    <td class="px-8 py-6 text-center">
                        <span class="px-3 py-1 bg-teal-100 text-teal-600 text-[10px] font-bold rounded-full uppercase">Aktif</span>
                    </td>
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
                        <p class="font-bold text-gray-800">Bio-Dent Lab</p>
                        <p class="text-[11px] text-gray-400">bio.den@lab.id</p>
                    </td>
                    <td class="px-8 py-6 text-gray-600">Jakarta</td>
                    <td class="px-8 py-6 text-gray-600 font-medium">+628566328783</td>
                    <td class="px-8 py-6 text-center">
                        <span class="px-3 py-1 bg-red-100 text-red-500 text-[10px] font-bold rounded-full uppercase">Nonaktif</span>
                    </td>
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
            </tbody>
        </table>
    </div>

    <div class="bg-gray-50/50 px-8 py-4 border-t border-gray-100">
        <p class="text-[11px] text-gray-400 font-medium">Menampilkan 3 dari 12 Laboratorium</p>
    </div>
</div>
@endsection
