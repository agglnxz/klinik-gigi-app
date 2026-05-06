@extends('layouts.main')

@section('title', 'Tambah Item Gigi')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-black-1000">Tambah Item Gigi</h3>
                <p class="text-sm text-gray-500 font-light">Silakan lengkapi formulir di bawah ini untuk mengirimkan detail
                    pemesanan protesa gigi ke laboratorium.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-5xl">
            <form action="#" method="POST">
                @csrf
                <div class="space-y-6 mb-10">

                    {{-- Row: Jenis Gigi / Prosedur | Jumlah Item --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label for="jenis_gigi"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Jenis Gigi /
                                Prosedur</label>
                            <div class="relative">
                                <select name="jenis_gigi" id="jenis_gigi"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-400 appearance-none cursor-pointer transition">
                                    <option value="" disabled selected>Pilih jenis prostesis</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="jumlah_item"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Jumlah
                                Item</label>
                            <input type="number" name="jumlah_item" id="jumlah_item" placeholder="Contoh: 1" min="1"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition">
                        </div>
                    </div>

                    {{-- Row: Harga Satuan | Total Harga Item --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label for="harga_satuan"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Harga Satuan
                                (RP)</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm text-gray-400 font-medium pointer-events-none">Rp</span>
                                <input type="text" inputmode="numeric" name="harga_satuan" id="harga_satuan"
                                    placeholder="0"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition">
                            </div>
                        </div>
                        <div>
                            <label for="total_harga"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Total Harga
                                Item</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm text-gray-400 font-medium pointer-events-none">Rp</span>
                                <input type="text" inputmode="numeric" name="total_harga" id="total_harga"
                                    placeholder="0" readonly
                                    class="w-full pl-10 pr-10 py-3 bg-gray-50 border-none rounded-lg text-sm text-gray-600 placeholder-gray-400 cursor-not-allowed transition">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div class="flex items-start gap-4 bg-teal-50 border border-teal-100 rounded-xl px-5 py-4">
                        <div class="mt-0.5 flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-teal-500 flex items-center justify-center">
                                <i class="fa-solid fa-circle-info text-white text-sm"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-teal-700 mb-1">Informasi Biaya</p>
                            <p class="text-xs text-teal-600 leading-relaxed">Pastikan harga yang dimasukkan sesuai dengan
                                katalog laboratorium terbaru. Total harga akan otomatis dikalkulasi berdasarkan kuantitas
                                yang dimasukkan.</p>
                        </div>
                    </div>

                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end space-x-4 border-t border-gray-100 pt-8">
                    <a href="{{ route('pemesanan.create') }}"
                        class="px-12 py-2.5 border border-gray-300 rounded-lg text-gray-500 text-sm font-semibold hover:bg-gray-50 transition text-center shadow-md min-w-[160px]">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-12 py-2.5 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-sm font-semibold flex items-center justify-center transition shadow-md shadow-teal-900/20 min-w-[160px]">
                        <i class="fa-solid fa-save mr-2"></i> Simpan Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const hargaSatuan = document.getElementById('harga_satuan');
        const jumlahItem = document.getElementById('jumlah_item');
        const totalHarga = document.getElementById('total_harga');

        function hitungTotal() {
            const harga = parseFloat(hargaSatuan.value.replace(/\D/g, '')) || 0;
            const jumlah = parseFloat(jumlahItem.value) || 0;
            totalHarga.value = (harga * jumlah).toLocaleString('id-ID');
        }

        hargaSatuan.addEventListener('input', hitungTotal);
        jumlahItem.addEventListener('input', hitungTotal);
    </script>
@endsection
