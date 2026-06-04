@extends('layouts.main')

@section('title', 'Tambah User Baru')

@section('content')

<div class="mb-6 flex items-center space-x-2 text-xs font-semibold text-gray-400">
    <a href="{{ route('users.index') }}" class="hover:text-[#429A80] transition">Data Manajemen User</a>
    <span><i class="fa-solid fa-chevron-right text-[10px]"></i></span>
    <span class="text-gray-700">Tambah Akun Baru</span>
</div>

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Tambah Akun Dokter / Staff</h1>
        <p class="text-sm text-gray-500 mt-0.5">Daftarkan akun baru untuk akses aplikasi Klinik Gigi Winardi.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-5xl">

    <form action="#" method="POST" class="space-y-6">
        @csrf

        <div>
            <label for="nama" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Nama Akun / Nama Lengkap</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <i class="fa-solid fa-user"></i>
                </span>
                <input type="text" id="nama" name="nama" placeholder="Contoh: drg. Ahmad Faisal"
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 focus:border-[#429A80] focus:bg-white rounded-xl text-xs font-medium text-gray-800 focus:outline-none transition duration-200">
            </div>
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Alamat Email Aktif</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                    <i class="fa-solid fa-envelope"></i>
                </span>
                <input type="email" id="email" name="email" placeholder="Contoh: ahmad@klinikwinardi.com"
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 focus:border-[#429A80] focus:bg-white rounded-xl text-xs font-medium text-gray-800 focus:outline-none transition duration-200">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Kata Sandi (Password)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" placeholder="Minimal 6 karakter"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 focus:border-[#429A80] focus:bg-white rounded-xl text-xs font-medium text-gray-800 focus:outline-none transition duration-200">
                </div>
            </div>

            <div>
                <label for="role" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Hak Akses / Role</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-user-shield"></i>
                    </span>
                    <select id="role" name="role"
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 focus:border-[#429A80] focus:bg-white rounded-xl text-xs font-semibold text-gray-700 focus:outline-none transition duration-200 appearance-none cursor-pointer">
                        <option value="" disabled selected>-- Pilih Hak Akses --</option>
                        <option value="Admin">Admin</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Direktur">Direktur</option>
                    </select>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </span>
                </div>
            </div>

        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Status Akun Awal</label>
            <div class="flex items-center space-x-6 bg-gray-50 border border-gray-200 p-3.5 rounded-xl">
                <label class="flex items-center space-x-2 text-xs font-semibold text-gray-700 cursor-pointer">
                    <input type="radio" name="is_aktif" value="1" checked class="w-4 h-4 text-[#429A80] focus:ring-[#429A80] border-gray-300">
                    <span>Langsung Aktif</span>
                </label>
                <label class="flex items-center space-x-2 text-xs font-semibold text-gray-600 cursor-pointer">
                    <input type="radio" name="is_aktif" value="0" class="w-4 h-4 text-[#429A80] focus:ring-[#429A80] border-gray-300">
                    <span>Non-Aktifkan Sementara</span>
                </label>
            </div>
        </div>

        <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 border-t border-gray-100 pt-8">
                <a href="{{ route('users.index') }}"
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

@endsection
