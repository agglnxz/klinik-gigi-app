@extends('layouts.main')

@section('title', 'Data Master Asisten')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-bold text-black-1000">Data Master</h3>
            <p class="text-sm text-gray-500 font-linght">Kelola entitas utama sistem Protesa Gigi Winardi</p>
        </div>
    </div>

    <div class="flex space-x-2 bg-gray-100/50 p-1 rounded-xl w-fit mb-8">
        <a href="{{ route('dokter.index') }}" class="px-6 py-2 text-gray-500 hover:text-teal-700 font-medium text-sm transition">Dokter</a>
        <a href="{{ route('asisten.index') }}" class="px-6 py-2 bg-white text-teal-700 shadow-sm rounded-lg font-medium text-sm">Asisten</a>
        <a href="{{ route('laboratorium.index') }}" class="px-6 py-2 text-gray-500 hover:text-teal-700 font-medium text-sm transition">Laboratorium</a>
        <a href="{{ route('jenis-gigi.index') }}" class="px-6 py-2 text-gray-500 hover:text-teal-700 font-medium text-sm transition">Jenis Gigi</a>
    </div>

    <div class="flex justify-between items-end mb-6">
        <div>
            <h3 class="text-lg font-bold text-black-1000">Daftar Asisten Klinik</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total 12 Asisten</p>
        </div>
        <a href="{{ route('asisten.create') }}">
            <button class="bg-[#176851] hover:bg-[#357a66] text-white px-5 py-2.5 rounded-md text-sm font-bold flex items-center gap-2 transition-all shadow-sm">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Asisten
            </button>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto max-w-full custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1250px]">
                <thead>
                    <tr class="bg-[#F3F4F3] border-b border-gray-100">
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Nama Asisten</th>
                        <th class="w-[200px] px-8 py-4 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Shift</th>
                        <th class="w-[300px] px-8 py-4 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Dokter Pendamping</th>
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Nomor HP</th>
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Status</th>
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    <tr class="hover:bg-gray-50/30 transition">
                        <td class="px-8 py-6 font-bold text-gray-800">Citra Lestari</td>
                        <td class="px-8 py-6 text-gray-500">Pagi</td>
                        <td class="px-8 py-6 font-bold text-gray-800">drg. Winardi</td>
                        <td class="px-8 py-6 text-gray-600">+62846353758</td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-teal-100 text-teal-600 text-[10px] font-bold rounded-full uppercase">Aktif</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex space-x-2">
                                <a href="{{ route('asisten.edit', ['id' => 1]) }}">
                                    <button class="flex items-center px-3 py-1.5 bg-[#59a38a] text-white text-xs rounded-md hover:bg-[#46826d] transition">
                                        <i class="fa-solid fa-pen mr-1.5"></i> Edit
                                    </button>
                                </a>
                                <button class="flex items-center px-3 py-1.5 bg-[#d65f5f] text-white text-xs rounded-md hover:bg-[#b54d4d] transition">
                                    <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/30 transition">
                        <td class="px-8 py-6 font-bold text-gray-800">Nikolas Saputra</td>
                        <td class="px-8 py-6 text-gray-500">Pagi</td>
                        <td class="px-8 py-6 font-bold text-gray-800">drg. Aluna Safira</td>
                        <td class="px-8 py-6 text-gray-600">+62846353758</td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-teal-100 text-teal-600 text-[10px] font-bold rounded-full uppercase">Aktif</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex space-x-2">
                                <button class="flex items-center px-3 py-1.5 bg-[#59a38a] text-white text-xs rounded-md">
                                    <i class="fa-solid fa-pen mr-1.5"></i> Edit
                                </button>
                                <button class="flex items-center px-3 py-1.5 bg-[#d65f5f] text-white text-xs rounded-md">
                                    <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/30 transition">
                        <td class="px-8 py-6 font-bold text-gray-800">Sheliana</td>
                        <td class="px-8 py-6 text-gray-500">Siang</td>
                        <td class="px-8 py-6 font-bold text-gray-800">drg. Kevin Aditya</td>
                        <td class="px-8 py-6 text-gray-600">+62846353758</td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-red-100 text-red-500 text-[10px] font-bold rounded-full uppercase">Nonaktif</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex space-x-2">
                                <button class="flex items-center px-3 py-1.5 bg-[#59a38a] text-white text-xs rounded-md">
                                    <i class="fa-solid fa-pen mr-1.5"></i> Edit
                                </button>
                                <button class="flex items-center px-3 py-1.5 bg-[#d65f5f] text-white text-xs rounded-md">
                                    <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="bg-[#F3F4F3] px-8 py-4 border-t border-gray-100">
            <p class="text-[11px] text-gray-400 font-medium text-left">Menampilkan 3 dari 12 Asisten</p>
        </div>
    </div>

</div>
@endsection
