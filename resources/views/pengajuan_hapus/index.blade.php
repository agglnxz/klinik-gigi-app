@extends('layouts.main')

@section('title', 'Persetujuan Penghapusan Data')

@section('content')
<div class="space-y-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Persetujuan Penghapusan Data</h1>
        <p class="text-sm text-gray-500 mt-0.5">Kelola permintaan penghapusan data dari admin modul.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Menunggu Persetujuan</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">5</h3>
            </div>
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Disetujui Bulan Ini</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">12</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Ditolak Bulan Ini</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">2</h3>
            </div>
            <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 flex justify-between items-center bg-white border-b border-gray-50">
            <h2 class="text-base font-bold text-gray-800">Permintaan Menunggu</h2>
            <button class="flex items-center space-x-1 text-xs font-bold text-teal-600 hover:text-teal-700 transition">
                <i class="fa-solid fa-sliders text-sm"></i>
                <span>Filter</span>
            </button>
        </div>

        <div class="overflow-x-auto max-w-full custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1400px]">
                <thead>
                    <tr class="bg-[#F3F4F3] border-b border-gray-100">
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Tanggal Pengajuan</th>
                        <th class="w-[150px] px-8 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Modul</th>
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Nama Data</th>
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Pemohon</th>
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Alasan</th>
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    <tr class="hover:bg-gray-50/30 transition">
                        <td class="px-8 py-6 text-gray-600 font-medium">12 Okt 2023, 09:30</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 bg-purple-100 text-purple-600 rounded-full text-[10px] font-bold uppercase">Pasien</span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">Budi Santoso (RM-1029)</td>
                        <td class="px-8 py-6 text-gray-600">Admin Resepsionis</td>
                        <td class="px-8 py-6 text-gray-600 max-w-xs truncate">Pasien meminta penghapusan akun karena pindah...</td>
                        <td class="px-8 py-6">
                            <div class="flex space-x-2">
                                <button type="button"
                                        onclick="openDetailModal('12 Okt 2023, 09:30', 'Pasien', 'Budi Santoso (RM-1029)', 'Admin Resepsionis', 'Pasien meminta penghapusan akun karena pindah domisili ke luar kota dan tidak akan melakukan pengobatan kembali di klinik ini.')"
                                        class="flex items-center px-4 py-2 bg-[#6690FF] text-white text-xs font-bold rounded-md hover:bg-[#254EDB] transition shadow-md hover:shadow">
                                    <i class="fa-solid fa-eye mr-1.5"></i> Lihat Detail
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 text-[11px] text-gray-400 font-semibold shadow-inner">
            Menampilkan 3 dari 12 Permintaan Menunggu Penghapusan Data
        </div>
    </div>


    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 flex justify-between items-center bg-white border-b border-gray-50">
            <h2 class="text-base font-bold text-gray-800">Riwayat Persetujuan</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F3F4F3] border-b border-gray-100">
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Tanggal Pengajuan</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Modul</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest">Nama Data</th>
                        <th class="px-6 py-4 text-center text-[11px] font-bold text-gray-900 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-xs text-gray-600">
                    <tr class="hover:bg-gray-50/40 transition">
                        <td class="px-6 py-4 text-gray-500 font-medium">12 Okt 2023, 09:30</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 bg-purple-100 text-purple-600 rounded-full text-[10px] font-bold uppercase">Pasien</span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">Budi Santoso (RM-1029)</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center space-x-1 text-[#137333] font-bold bg-[#E6F4EA] w-24 mx-auto py-1 rounded-full text-[11px]">
                                <i class="fa-solid fa-check text-[10px]"></i>
                                <span>Disetujui</span>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/40 transition">
                        <td class="px-6 py-4 text-gray-500 font-medium">11 Okt 2023, 14:15</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-600 rounded-full text-[10px] font-bold uppercase">Jenis Gigi</span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">Prosedur Pemutihan Lama</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center space-x-1 text-[#137333] font-bold bg-[#E6F4EA] w-24 mx-auto py-1 rounded-full text-[11px]">
                                <i class="fa-solid fa-check text-[10px]"></i>
                                <span>Disetujui</span>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/40 transition">
                        <td class="px-6 py-4 text-gray-500 font-medium">11 Okt 2023, 14:15</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 bg-sky-100 text-sky-600 rounded-full text-[10px] font-bold uppercase">Dokter</span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">drg. Santos</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center space-x-1 text-[#C5221F] font-bold bg-[#FCE8E6] w-24 mx-auto py-1 rounded-full text-[11px]">
                                <i class="fa-solid fa-xmark text-[10px]"></i>
                                <span>Ditolak</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 text-[11px] text-gray-400 font-semibold shadow-inner">
            Menampilkan 3 dari 12 Permintaan Persetujuan Penghapusan Data
        </div>
    </div>

</div>

<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto animate-fade-in" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" onclick="closeDetailModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">

            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-base font-bold text-gray-800" id="modal-title">Detail Permintaan Penghapusan</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-4 text-sm text-gray-600">
                <div class="grid grid-cols-3 border-b border-gray-50 pb-2.5">
                    <span class="font-bold text-gray-400 uppercase text-[11px] tracking-wider">Tanggal Pengajuan</span>
                    <span id="modal-tanggal" class="col-span-2 font-medium text-gray-800"></span>
                </div>
                <div class="grid grid-cols-3 border-b border-gray-50 pb-2.5">
                    <span class="font-bold text-gray-400 uppercase text-[11px] tracking-wider">Modul</span>
                    <div class="col-span-2">
                        <span id="modal-modul" class="px-2.5 py-0.5 bg-purple-100 text-purple-600 rounded-full text-[10px] font-bold uppercase"></span>
                    </div>
                </div>
                <div class="grid grid-cols-3 border-b border-gray-50 pb-2.5">
                    <span class="font-bold text-gray-400 uppercase text-[11px] tracking-wider">Nama Data</span>
                    <span id="modal-nama" class="col-span-2 font-semibold text-gray-800"></span>
                </div>
                <div class="grid grid-cols-3 border-b border-gray-50 pb-2.5">
                    <span class="font-bold text-gray-400 uppercase text-[11px] tracking-wider">Pemohon</span>
                    <span id="modal-pemohon" class="col-span-2 font-medium text-gray-800"></span>
                </div>
                <div class="grid grid-cols-3 pb-1">
                    <span class="font-bold text-gray-400 uppercase text-[11px] tracking-wider">Alasan</span>
                    <p id="modal-alasan" class="col-span-2 font-medium text-gray-700 bg-gray-50 p-3 rounded-xl border border-gray-100 leading-relaxed text-xs"></p>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-2.5">
                <button type="button" onclick="closeDetailModal()" class="flex items-center px-4 py-2 bg-[#d65f5f] text-white text-xs font-bold rounded-md hover:bg-[#b54d4d] transition shadow-md hover:shadow">
                    Tidak Setujui
                </button>

                <form id="approveForm" action="#" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-2 bg-[#d65f5f] text-white text-xs font-bold rounded-md hover:bg-[#b54d4d] transition shadow-md hover:shadow">
                        <i class="fa-solid fa-trash mr-1.5"></i> Setujui Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openDetailModal(tanggal, modul, nama, pemohon, alasan) {
        document.getElementById('modal-tanggal').innerText = tanggal;
        document.getElementById('modal-modul').innerText = modul;
        document.getElementById('modal-nama').innerText = nama;
        document.getElementById('modal-pemohon').innerText = pemohon;
        document.getElementById('modal-alasan').innerText = alasan;

        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection
