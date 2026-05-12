@extends('layouts.main')

@section('title', 'Data Master Dokter')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Data Master</h3>
            <p class="text-sm text-gray-500">Kelola entitas utama sistem Protesa Gigi Winardi</p>
        </div>
    </div>

    <div class="flex space-x-2 bg-gray-100/50 p-1 rounded-xl w-fit mb-8 border border-gray-200">
        <a href="{{ route('dokter.index') }}" class="px-6 py-2 bg-white text-teal-700 shadow-sm rounded-lg font-medium text-sm">Dokter</a>
        <a href="{{ route('asisten.index') }}" class="px-6 py-2 text-gray-500 hover:text-teal-700 font-medium text-sm transition">Asisten</a>
        <a href="{{ route('laboratorium.index') }}" class="px-6 py-2 text-gray-500 hover:text-teal-700 font-medium text-sm transition">Laboratorium</a>
        <a href="{{ route('jenis-gigi.index') }}" class="px-6 py-2 text-gray-500 hover:text-teal-700 font-medium text-sm transition">Jenis Gigi</a>
    </div>

    <div class="flex justify-between items-end mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Daftar Dokter Spesialis</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total {{ $dokter->count() }} Dokter</p>
        </div>
        <a href="{{ route('dokter.create') }}">
            <button class="bg-[#176851] hover:bg-[#357a66] text-white px-5 py-2.5 rounded-md text-sm font-bold flex items-center gap-2 transition-all shadow-sm">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Dokter
            </button>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-teal-50 border border-teal-200 text-teal-700 rounded-xl text-sm mb-4 flex items-center shadow-sm">
            <i class="fa-solid fa-circle-check mr-2 text-base"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden flex flex-col">
        <div class="overflow-x-auto max-w-full custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F3F4F3] border-b border-gray-100">
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-600 uppercase tracking-widest">Nama Dokter</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-600 uppercase tracking-widest">Nomor HP</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-600 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-[11px] font-bold text-gray-600 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($dokter as $item)
                    <tr class="hover:bg-gray-50/50 transition group">
                        <td class="px-8 py-6 font-bold text-gray-800">{{ $item->nama }}</td>
                        <td class="px-8 py-6 text-gray-600 text-sm">{{ $item->kontak }}</td>
                        <td class="px-8 py-6">
                            @if($item->is_aktif)
                                <span class="px-3 py-1 bg-teal-100 text-teal-600 text-[10px] font-bold rounded-full uppercase shadow-sm">Aktif</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-500 text-[10px] font-bold rounded-full uppercase shadow-sm">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex space-x-2">
                                <a href="{{ route('dokter.edit', $item->id) }}">
                                    <button class="flex items-center px-4 py-2 bg-[#59a38a] text-white text-xs font-bold rounded-md hover:bg-[#46826d] transition shadow-md hover:shadow">
                                        <i class="fa-solid fa-pen mr-1.5"></i> Edit
                                    </button>
                                </a>

                                <form action="{{ route('dokter.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan dokter ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center px-4 py-2 bg-[#d65f5f] text-white text-xs font-bold rounded-md hover:bg-[#b54d4d] transition shadow-md hover:shadow">
                                        <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center opacity-40">
                                <i class="fa-solid fa-user-doctor text-5xl mb-4"></i>
                                <p class="text-sm font-medium">Belum ada data dokter yang terdaftar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-[#F3F4F3] px-8 py-4 border-t border-gray-100">
            <p class="text-[11px] text-gray-400 font-medium italic">
                Menampilkan {{ $dokter->count() }} Tenaga Medis saat ini.
            </p>
        </div>
    </div>
</div>
@endsection
