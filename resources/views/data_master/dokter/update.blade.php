@extends('layouts.main')

@section('title', 'Edit Data Dokter')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-bold text-black-1000">Edit Data Dokter</h3>
            <p class="text-sm text-gray-500 font-linght">Lengkapi detail pemeriksaan pasien untuk pencatatan rekam medis yang akurat.</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-5xl">
        <form action="{{ route('dokter.update', $dokter->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 mb-12">
                <!-- Nama Dokter -->
                <div>
                    <label for="nama" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Nama Dokter</label>
                    <input type="text" name="nama" id="nama" value="{{ $dokter->nama }}" placeholder="Nama Dokter"
                        class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm text-gray-600 placeholder-gray-400 transition">
                </div>

                <!-- Nomor Handphone -->
                <div>
                    <label for="kontak" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Nomor Handphone</label>
                    <input type="text" name="kontak" id="kontak" value="{{ $dokter->kontak }}" placeholder="Masukkan Nomor Handphone Dokter"
                        class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm text-gray-600 placeholder-gray-400 transition">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Status</label>
                    <div class="relative">
                        <select name="is_aktif" id="is_aktif"
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm text-gray-600 appearance-none cursor-pointer transition">
                            <option value="1" {{ $dokter->is_aktif ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$dokter->is_aktif ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 border-t border-gray-100 pt-8">
                <a href="{{ route('dokter.index') }}"
                    class="px-12 py-2.5 border border-gray-300 rounded-lg text-gray-500 text-sm font-semibold hover:bg-gray-50 transition text-center shadow-md min-w-[160px]">
                    Batal
                </a>
                <button type="submit"
                    class="px-12 py-2.5 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-sm font-semibold flex items-center justify-center transition shadow-md min-w-[160px]">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
