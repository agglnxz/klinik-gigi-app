@extends('layouts.main')

@section('title', 'Update Data Jenis Gigi')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-bold text-black-1000">Update Data Jenis Gigi</h3>
            <p class="text-sm text-gray-500 font-linght">Lengkapi detail pemeriksaan pasien untuk pencatatan rekam medis yang akurat.</p>
        </div>
    </div>

    <!-- Form Container dengan Shadow Tegas -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-5xl">
        <form action="#" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 mb-12">
                <!-- Jenis Gigi -->
                <div>
                    <label for="nama_jenis_gigi" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Jenis Gigi</label>
                    <input type="text" name="nama_jenis_gigi" id="nama_jenis_gigi" value="Gigi Seri" placeholder="Nama Jenis Gigi"
                        class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition">
                </div>

                <!-- Kode Gigi -->
                <div>
                    <label for="kode_gigi" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Kode Gigi</label>
                    <input type="text" name="kode_gigi" id="kode_gigi" value="48" placeholder="Masukkan Kode Gigi"
                        class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition">
                </div>

                <!-- Harga -->
                <div>
                    <label for="harga" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Harga</label>
                    <input type="number" name="harga" id="harga" value="900000" placeholder="Masukkan Harga"
                        class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Status</label>
                    <div class="relative">
                        <select name="status" id="status"
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 appearance-none cursor-pointer transition">
                            <option value="aktif" selected>Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons dengan Shadow -->
            <div class="flex justify-end space-x-4 border-t border-gray-100 pt-8">
                <a href="{{ route('jenis-gigi.index') }}"
                    class="px-12 py-2.5 border border-gray-300 rounded-lg text-gray-500 text-sm font-semibold hover:bg-gray-50 hover:shadow-sm transition text-center shadow-md min-w-[160px]">
                    Batal
                </a>
                <button type="submit"
                    class="px-12 py-2.5 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-sm font-semibold flex items-center justify-center transition shadow-md shadow-teal-900/20 min-w-[160px]">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
