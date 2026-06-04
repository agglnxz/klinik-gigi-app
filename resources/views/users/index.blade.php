@extends('layouts.main')

@section('title', 'Daftar User')

@section('content')

<div class="flex justify-between items-end">
    <div>
        <h3 class="text-2xl font-bold text-black-1000">Data Manajemen User</h3>
        <p class="text-sm text-gray-500 font-linght">Kelola Daftar akun yang dapat mengakses aplikasi pemesanan gigi palsu diklinik Gigi Winardi.</p>
    </div>

</div>

<div class="flex justify-between items-center mt-10 mb-8">
    <h2 class="text-base font-bold text-gray-800">Daftar User Klinik Gigi winardi</h2>
    <a href="{{ route('users.create') }}" class="flex items-center space-x-2">
        <button class="bg-[#176851] hover:bg-[#357a66] text-white px-5 py-2.5 rounded-md text-sm font-bold flex items-center gap-2 transition-all shadow-sm">
            <i class="fa-solid fa-plus text-xs"></i> Tambah User Baru
        </button>
    </a>
</div>


<!-- Konten Tabel Statis -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
    <div class="overflow-x-auto max-w-full custom-scrollbar">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#F3F4F3] border-b border-gray-100">
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Nama Akun</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Email</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Role</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-xs text-gray-600">

                <!-- Baris 1: Admin Utama -->
                <tr class="hover:bg-gray-50/40 transition">
                    <td class="px-6 py-5 font-bold text-gray-800">Admin Utama</td>
                    <td class="px-6 py-5 text-gray-500">admin@klinikwinardi.com</td>
                    <td class="px-6 py-5 font-semibold text-gray-700">Admin</td>
                    <td class="px-6 py-5">
                        <div class="flex">
                            <span class="px-2.5 py-0.5 bg-[#E6F4EA] text-[#137333] rounded text-[10px] font-bold uppercase tracking-wide">Aktif</span>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex space-x-2">
                            <a href="#">
                                <button class="flex items-center px-4 py-2 bg-[#59a38a] text-white text-xs font-bold rounded-md hover:bg-[#46826d] transition shadow-md hover:shadow">
                                    <i class="fa-solid fa-pen mr-1.5"></i> Edit
                                </button>
                            </a>
                            <form action="#" method="POST">
                                <button type="submit" class="flex items-center px-4 py-2 bg-[#d65f5f] text-white text-xs font-bold rounded-md hover:bg-[#b54d4d] transition shadow-md hover:shadow">
                                    <i class="fa-solid fa-trash mr-1.5"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- Info Item Count -->
    <div class="bg-[#F3F4F3] px-8 py-4 border-t border-gray-100">
        <p class="text-[11px] text-gray-400 font-medium">Menampilkan 3 dari 12 User Saat Ini</p>
    </div>
</div>


@endsection
