@extends('layouts.main')

@section('title', 'Data Master Jenis Gigi')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-bold text-black-1000">Data Master</h3>
            <p class="text-sm text-gray-500 font-light">Kelola entitas utama sistem Protesa Gigi Winardi</p>
        </div>
    </div>

    <div class="flex space-x-2 bg-gray-100/50 p-1 rounded-xl w-fit mb-8 border border-gray-200">
        <a href="{{ route('dokter.index') }}" class="px-6 py-2 text-gray-500 hover:text-teal-700 font-medium text-sm transition">Dokter</a>
        <a href="{{ route('asisten.index') }}" class="px-6 py-2 text-gray-500 hover:text-teal-700 font-medium text-sm transition">Asisten</a>
        <a href="{{ route('laboratorium.index') }}" class="px-6 py-2 text-gray-500 hover:text-teal-700 font-medium text-sm transition">Laboratorium</a>
        <a href="{{ route('jenis-gigi.index') }}" class="px-6 py-2 bg-white text-teal-700 shadow-sm rounded-lg font-medium text-sm">Jenis Gigi</a>
    </div>

    <div class="flex justify-between items-end mb-6">
        <div>
            <h3 class="text-lg font-bold text-black-1000">Daftar Jenis Gigi</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total {{ $jenis_gigi->count() }} Jenis Gigi</p>
        </div>
        <a href="{{ route('jenis-gigi.create') }}">
            <button class="bg-[#176851] hover:bg-[#357a66] text-white px-5 py-2.5 rounded-md text-sm font-bold flex items-center gap-2 transition-all shadow-sm">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Jenis Gigi
            </button>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-teal-50 border border-teal-200 text-teal-700 rounded-xl text-sm mb-4 flex items-center shadow-sm">
            <i class="fa-solid fa-circle-check mr-2 text-base"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm mb-4 flex items-center shadow-sm">
            <i class="fa-solid fa-circle-exclamation mr-2 text-base"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="overflow-x-auto max-w-full custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-[#F3F4F3] border-b border-gray-100">
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Jenis Gigi</th>
                        <th class="w-[200px] px-8 py-4 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Kode Gigi</th>
                        <th class="w-[200px] px-8 py-4 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Harga</th>
                        <th class="w-[150px] px-8 py-4 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Status</th>
                        <th class="w-[200px] px-8 py-4 text-[11px] font-bold text-bold-900 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($jenis_gigi as $item)
                    <tr class="hover:bg-gray-50/30 transition">
                        <td class="px-8 py-6 font-bold text-gray-800">{{ $item->nama_jenis }}</td>
                        <td class="px-8 py-6 text-gray-600 font-medium">{{ $item->kode_gigi }}</td>
                        <td class="px-8 py-6 text-gray-600 font-semibold">Rp {{ number_format($item->estimasi_biaya, 0, ',', '.') }}</td>
                        <td class="px-8 py-6">
                            @if($item->is_aktif)
                                <span class="px-3 py-1 bg-teal-100 text-teal-600 text-[10px] font-bold rounded-full uppercase shadow-sm">Aktif</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-500 text-[10px] font-bold rounded-full uppercase shadow-sm">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex space-x-2">

                                {{-- CEK STATUS PENGAJUAN HAPUS --}}
                                @if (isset($pendingHapus) && in_array($item->id, $pendingHapus))
                                    {{-- Tombol Disabled (Abu-abu) --}}
                                    <span class="flex items-center px-4 py-2 bg-gray-100 text-gray-400 text-[10px] font-bold rounded-md border border-gray-200 cursor-not-allowed">
                                        <i class="fa-solid fa-hourglass-half mr-1.5 animate-pulse"></i> Menunggu Approval
                                    </span>
                                @else
                                    {{-- Tombol Normal Edit & Hapus --}}
                                    <a href="{{ route('jenis-gigi.edit', $item->id) }}"
                                        class="flex items-center px-4 py-2 bg-[#59a38a] text-white text-xs font-bold rounded-md hover:bg-[#46826d] transition shadow-md hover:shadow">
                                        <i class="fa-solid fa-pen mr-1.5"></i> Edit
                                    </a>

                                    <button type="button"
                                        @click="$dispatch('buka-modal-hapus', { id: '{{ $item->id }}', nama: '{{ $item->nama_jenis }}', tabel: 'jenis_gigi' })"
                                        class="flex items-center px-3 py-1.5 bg-[#d65f5f] text-white text-xs font-bold rounded-md hover:bg-[#b54d4d] cursor-pointer transition shadow-md hover:shadow">
                                        <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                                    </button>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center opacity-40">
                                <i class="fa-solid fa-tooth text-5xl mb-4"></i>
                                <p class="text-sm font-medium">Belum ada data jenis gigi yang terdaftar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-[#F3F4F3] px-8 py-4 border-t border-gray-100">
            <p class="text-[11px] text-gray-400 font-medium">Menampilkan {{ $jenis_gigi->count() }} Jenis Gigi Saat Ini</p>
        </div>
    </div>
</div>

{{-- MODAL BOX ALPINE.JS UNTUK INPUT ALASAN HAPUS --}}
<div x-data="{ open: false, idReferensi: '', namaData: '', namaTabel: '', alasan: '' }"
    @buka-modal-hapus.window="open = true; idReferensi = $event.detail.id; namaData = $event.detail.nama; namaTabel = $event.detail.tabel"
    x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 p-4"
    style="display: none;">

    <div @click.outside="open = false"
        class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100">
        <div class="flex items-center gap-3 text-red-500 mb-4">
            <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            <h3 class="text-base font-bold text-gray-800">Pengajuan Hapus Jenis Gigi</h3>
        </div>

        <p class="text-xs text-gray-500 mb-4 leading-relaxed">
            Anda mengajukan penghapusan untuk jenis gigi: <span class="font-bold text-gray-800" x-text="namaData"></span>. Data tidak akan langsung terhapus sebelum disetujui oleh Direktur.
        </p>

        <form :action="'{{ route('pengajuan-hapus.store') }}'" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="id_referensi" :value="idReferensi">
            <input type="hidden" name="nama_tabel" :value="namaTabel">
            <input type="hidden" name="nama_data" :value="namaData">

            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                    Alasan Penghapusan
                </label>
                <textarea name="alasan_hapus" x-model="alasan" required rows="3"
                    placeholder="Tulis alasan mengapa jenis gigi ini perlu dihapus dari daftar master..."
                    class="w-full p-3 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 outline-none transition-all placeholder:text-gray-400 font-medium"></textarea>
            </div>

            <div class="flex justify-end gap-2 border-t border-gray-50 pt-4">
                <button type="button" @click="open = false"
                    class="px-4 py-2 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg hover:bg-gray-200">
                    Batal
                </button>
                <button type="submit" :disabled="!alasan.trim()"
                    class="px-4 py-2 bg-[#176851] text-white text-xs font-bold rounded-lg hover:bg-teal-700 disabled:opacity-50">
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
