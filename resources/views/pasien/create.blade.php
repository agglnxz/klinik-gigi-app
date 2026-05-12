@extends('layouts.main')

@section('title', 'Tambah Pasien Baru')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-bold text-black-1000">Tambah Pasien Baru</h3>
            <p class="text-sm text-gray-500 font-linght">Lengkapi data pasien di bawah ini untuk pendaftaran rekam medis baru.</p>
        </div>
    </div>

    <!-- Form Container dengan Shadow Tegas -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-5xl">
        <form action="{{ route('pasien.store') }}" method="POST">
            @csrf
            <div class="space-y-6 mb-12">
                <!-- Nama Pasien -->
                <div>
                    <label for="nama" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Nama Pasien</label>
                    <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Sesuai KTP"
                        class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                    <!-- Nomor Rekam Medis -->
                    <div>
                        <label for="no_rm" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Nomor Rekam Medis</label>
                        <input type="text" name="no_rm" id="no_rm" placeholder="RM-2023-0042"
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition">
                        <p class="text-[10px] text-gray-400 mt-1">*Dihasilkan secara otomatis oleh sistem</p>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Jenis Kelamin</label>
                        <div class="flex space-x-4">
                            <label class="flex-1 flex items-center justify-start px-4 py-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
                                <input type="radio" name="jenis_kelamin" value="Laki-laki" class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300">
                                <span class="ml-3 text-sm font-medium text-gray-600">Laki-laki</span>
                            </label>
                            <label class="flex-1 flex items-center justify-start px-4 py-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
                                <input type="radio" name="jenis_kelamin" value="Perempuan" class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300">
                                <span class="ml-3 text-sm font-medium text-gray-600">Perempuan</span>
                            </label>
                        </div>
                    </div>

                    <!-- Nomor Handphone -->
                    <div>
                        <label for="kontak" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Nomor Handphone</label>
                        <input type="text" name="kontak" id="kontak" placeholder="Masukkan Nomor Handphone Pasien"
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition">
                    </div>

                    <!-- Alamat Lengkap -->
                    <div>
                        <label for="alamat" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" rows="4" placeholder="Masukkan Alamat Lengkap Domisili saat ini..."
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition resize-none"></textarea>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_aktif" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Status</label>
                        <div class="relative">
                            <select name="is_aktif" id="is_aktif"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm text-gray-600 appearance-none cursor-pointer transition">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 border-t border-gray-100 pt-8">
                <a href="{{ route('pasien.index') }}"
                    class="px-12 py-2.5 border border-gray-300 rounded-lg text-gray-500 text-sm font-semibold hover:bg-gray-50 transition text-center shadow-md min-w-[160px]">
                    Batal
                </a>
                <button type="submit"
                    class="px-12 py-2.5 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-sm font-semibold flex items-center justify-center transition shadow-md shadow-teal-900/20 min-w-[160px]">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Pasien
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
