@extends('layouts.main')

@section('title', 'Edit Data Pasien')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-bold text-black-1000">Edit Pasien</h3>
            <p class="text-sm text-gray-500 font-linght">Lengkapi data pasien di bawah ini untuk memperbarui informasi rekam medis.</p>
        </div>
    </div>

    <!-- Form Container dengan Shadow Tegas -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-5xl">
        <!-- Action diarahkan ke update sesuai ID pasien -->
        <form action="{{ route('pasien.update', $pasien->id) }}" method="POST" id="editForm">
            @csrf
            @method('PUT')

            <div class="space-y-6 mb-12">
                <!-- Nama Pasien -->
                <div>
                    <label for="nama" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Nama Pasien</label>
                    <input type="text" name="nama" id="nama" value="{{ $pasien->nama }}" placeholder="Masukkan Nama Sesuai KTP"
                        class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                    <!-- Nomor Rekam Medis (Read-only karena biasanya ID unik) -->
                    <div>
                        <label for="no_rm" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Nomor Rekam Medis</label>
                        <input type="text" name="no_rm" id="no_rm" value="{{ $pasien->no_rm }}" readonly
                            class="w-full px-4 py-3 bg-gray-100 border-none rounded-lg text-sm text-gray-400 cursor-not-allowed">
                        <p class="text-[10px] text-gray-400 mt-1">*Nomor rekam medis tidak dapat diubah</p>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Jenis Kelamin</label>
                        <div class="flex space-x-4">
                            <label class="flex-1 flex items-center justify-start px-4 py-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition border border-transparent">
                                <input type="radio" name="jenis_kelamin" value="Laki-laki" {{ $pasien->jenis_kelamin === 'Laki-laki' ? 'checked' : '' }} class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300">
                                <span class="ml-3 text-sm font-medium text-gray-600">Laki-laki</span>
                            </label>
                            <label class="flex-1 flex items-center justify-start px-4 py-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition border border-transparent">
                                <input type="radio" name="jenis_kelamin" value="Perempuan" {{ $pasien->jenis_kelamin === 'Perempuan' ? 'checked' : '' }} class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300">
                                <span class="ml-3 text-sm font-medium text-gray-600">Perempuan</span>
                            </label>
                        </div>
                    </div>

                    <!-- Nomor Handphone -->
                    <div>
                        <label for="kontak" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Nomor Handphone</label>
                        <input type="text" name="kontak" id="kontak" value="{{ $pasien->kontak }}" placeholder="0812-3456-7890"
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition">
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                <div>
                    <label for="alamat" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" rows="4" placeholder="Masukkan Alamat Lengkap Domisili saat ini..."
                        class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition resize-none">{{ $pasien->alamat }}</textarea>
                </div>

                <!-- Status -->
                <div>
                    <label for="is_aktif" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Status</label>
                    <div class="relative">
                        <select name="is_aktif" id="is_aktif"
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 appearance-none cursor-pointer transition">
                            <option value="1" {{ $pasien->is_aktif ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$pasien->is_aktif ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 border-t border-gray-100 pt-8">
                <a href="{{ route('pasien.index') }}"
                    class="px-12 py-2.5 border border-gray-300 rounded-lg text-gray-500 text-sm font-semibold hover:bg-gray-50 transition shadow-md text-center min-w-[160px]">
                    Batal
                </a>
                <button type="submit"
                    class="px-12 py-2.5 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-sm font-semibold flex items-center justify-center transition shadow-md shadow-teal-900/20 min-w-[160px]">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
