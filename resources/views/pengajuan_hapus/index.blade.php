@extends('layouts.main')

@section('title', 'Persetujuan Penghapusan Data')

@section('content')

@php
use Illuminate\Support\Str;
@endphp

<div class="space-y-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Persetujuan Penghapusan Data</h1>
        <p class="text-sm text-gray-500 mt-0.5">Kelola permintaan penghapusan data dari admin modul.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.15em]">Menunggu Persetujuan</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $menunggu }}
                </h3>
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
                <h3 class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $disetujui }}
                </h3>
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
                <h3 class="text-3xl font-bold text-gray-800 mt-1">
                    {{ $ditolak }}
                </h3>
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
                        <th class="w-[250px] px-8 py-4 text-[11px] font-bold text-gray-900 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($pengajuan->where('status_approval', 'Pending') as $item)
                    <tr class="hover:bg-gray-50/30 transition">
                        <td class="px-8 py-6 text-gray-600 font-medium">
                            {{ $item->created_at->format('d M Y H:i') }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 bg-purple-100 text-purple-600 rounded-full text-[10px] font-bold uppercase">
                                {{ $item->nama_tabel }}
                            </span>
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $item->nama_data }}
                        </td>

                        <td class="px-8 py-6 text-gray-600">
                            {{ $item->pemohon->name ?? '-' }}
                        </td>

                        <td class="px-8 py-6 text-gray-600">
                            {{ Str::limit($item->alasan_hapus, 40) }}
                        </td>

                        <td class="px-8 py-6 text-center">
                            <button type="button" onclick="openHapusModal('modal-{{ $item->id }}')"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#176851] hover:bg-[#238066] text-white text-xs font-bold rounded-lg transition shadow-sm">
                                <i class="fa-solid fa-eye"></i>
                                <span>Lihat Detail</span>
                            </button>

                            <div id="modal-{{ $item->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 text-left">
                                <div class="bg-white rounded-2xl border border-gray-100 shadow-xl max-w-lg w-full overflow-hidden transform transition-all flex flex-col">
                                    
                                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-2">
                                            <i class="fa-solid fa-file-shield text-amber-500"></i>
                                            Detail Validasi Penghapusan Data
                                        </h3>
                                        <button type="button" onclick="closeHapusModal('modal-{{ $item->id }}')" class="text-gray-400 hover:text-gray-600 transition">
                                            <i class="fa-solid fa-xmark text-base"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="p-6 space-y-4 text-xs font-medium text-gray-700">
                                        <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-2.5">
                                            <span class="text-gray-400 font-bold uppercase tracking-wide">Tanggal Masuk</span>
                                            <span class="col-span-2 text-gray-800 font-semibold">: {{ $item->created_at->format('d M Y H:i') }}</span>
                                        </div>
                                        <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-2.5">
                                            <span class="text-gray-400 font-bold uppercase tracking-wide">Modul / Tabel</span>
                                            <span class="col-span-2">: 
                                                <span class="px-2.5 py-0.5 bg-purple-100 text-purple-600 rounded-full text-[10px] font-bold uppercase ml-1">
                                                    {{ $item->nama_tabel }}
                                                </span>
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-2.5">
                                            <span class="text-gray-400 font-bold uppercase tracking-wide">Nama Data</span>
                                            <span class="col-span-2 text-gray-900 font-bold">: {{ $item->nama_data }}</span>
                                        </div>
                                        <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-2.5">
                                            <span class="text-gray-400 font-bold uppercase tracking-wide">Pemohon Hapus</span>
                                            <span class="col-span-2 text-gray-800 font-semibold">: {{ $item->pemohon->name ?? '-' }}</span>
                                        </div>
                                        <div class="space-y-2 pt-1">
                                            <span class="text-gray-400 font-bold uppercase tracking-wide block">Alasan Yang Diajukan:</span>
                                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-gray-600 font-normal leading-relaxed whitespace-pre-line text-xs">
                                                {{ $item->alasan_hapus }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2.5">
                                        <button type="button" onclick="closeHapusModal('modal-{{ $item->id }}')"
                                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-500 text-xs font-semibold hover:bg-gray-100 transition shadow-sm">
                                            Batal
                                        </button>
                                        
                                        <form action="{{ route('pengajuan-hapus.reject', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Apakah Anda yakin menolak pengajuan ini?')"
                                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition shadow-sm flex items-center gap-1.5">
                                                <i class="fa-solid fa-xmark"></i>
                                                <span>Tolak</span>
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('pengajuan-hapus.approve', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('PENTING: Menyetujui akan menghapus data permanen dari database. Lanjutkan?')"
                                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition shadow-sm flex items-center gap-1.5">
                                                <i class="fa-solid fa-check"></i>
                                                <span>Setujui</span>
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            Tidak ada pengajuan yang menunggu persetujuan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 text-[11px] text-gray-400 font-semibold shadow-inner">
            Menampilkan {{ $pengajuan->where('status_approval', 'Pending')->count() }} Permintaan Menunggu Penghapusan Data
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
                    @forelse($pengajuan->where('status_approval', '!=', 'Pending') as $item)
                    <tr class="hover:bg-gray-50/40 transition">

                        <td class="px-6 py-4 text-gray-500 font-medium">
                            {{ $item->created_at->format('d M Y H:i') }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 bg-purple-100 text-purple-600 rounded-full text-[10px] font-bold uppercase">
                                {{ $item->nama_tabel }}
                            </span>
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-800">
                            {{ $item->nama_data }}
                        </td>

                        <td class="px-6 py-4">
                            @if($item->status_approval == 'Disetujui')
                                <div class="flex justify-center items-center space-x-1 text-[#137333] font-bold bg-[#E6F4EA] w-24 mx-auto py-1 rounded-full text-[11px]">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                    <span>Disetujui</span>
                                </div>
                            @else
                                <div class="flex justify-center items-center space-x-1 text-[#C5221F] font-bold bg-[#FCE8E6] w-24 mx-auto py-1 rounded-full text-[11px]">
                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                    <span>Ditolak</span>
                                </div>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500">
                            Belum ada riwayat persetujuan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 text-[11px] text-gray-400 font-semibold shadow-inner">
            Menampilkan {{ $pengajuan->where('status_approval', '!=', 'Pending')->count() }} Riwayat Persetujuan Penghapusan Data
        </div>
    </div>

</div>

<script>
    function openHapusModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden'; // Kunci scroll latar belakang
        }
    }

    // PERBAIKAN: Nama fungsi disamakan dengan yang dipanggil tombol (closeHapusModal)
    function closeHapusModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Aktifkan scroll kembali
        }
    }

    // Menutup modal otomatis jika pengguna mengklik area luar (backdrop hitam transparan)
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('fixed')) {
            event.target.classList.remove('flex');
            event.target.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });
</script>

@endsection