@extends('layouts.main')

@section('title', 'Registrasi Pemesanan Baru')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-black-1000">Registrasi Pemesanan Baru</h3>
                <p class="text-sm text-gray-500 font-light">Silakan lengkapi formulir di bawah ini untuk mengirimkan detail
                    pemesanan protesa gigi ke laboratorium.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-5xl">
            <form action="#" method="POST">
                @csrf
                <div class="space-y-6 mb-12">

                    {{-- Id Pemeriksaan (full width dropdown) --}}
                    <div>
                        <label for="id_pemeriksaan"
                            class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Id
                            Pemeriksaan</label>
                        <div class="relative">
                            <select name="id_pemeriksaan" id="id_pemeriksaan" disabled
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-400 appearance-none cursor-pointer transition">
                                <option value="" disabled selected>Pilih id pameriksaan</option>
                            </select>

                        </div>
                    </div>

                    {{-- Row: Nama Pasien | Tanggal Kirim --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label for="nama_pasien"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Nama
                                Pasien</label>
                            <div class="relative">
                                <select name="nama_pasien" id="nama_pasien"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-400 appearance-none cursor-pointer transition">
                                    <option value="" disabled selected>Nama Pasien</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="tanggal_kirim"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Tanggal
                                Kirim</label>
                            <input type="date" name="tanggal_kirim" id="tanggal_kirim"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 transition">
                        </div>
                    </div>

                    {{-- Row: Laboratorium | Estimasi Selesai --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label for="laboratorium"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Laboratorium</label>
                            <div class="relative">
                                <select name="laboratorium" id="laboratorium"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-400 appearance-none cursor-pointer transition">
                                    <option value="" disabled selected>Pilih Laboratorium</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="estimasi_selesai"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Estimasi
                                Selesai</label>
                            <input type="date" name="estimasi_selesai" id="estimasi_selesai"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 transition">
                        </div>
                    </div>

                    {{-- Row: Status Pembayaran Laboratorium | Biaya Laboratorium --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label for="status_pembayaran"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Status
                                Pembayaran Laboratorium</label>
                            <div class="relative">
                                <select name="status_pembayaran" id="status_pembayaran"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 appearance-none cursor-pointer transition">
                                    <option value="lunas">Lunas</option>
                                    <option value="belum_lunas">Belum Lunas</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="biaya_laboratorium"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Biaya
                                Laboratorium</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm text-gray-400 font-medium pointer-events-none">Rp</span>
                                <input type="text" inputmode="numeric" name="biaya_laboratorium" id="biaya_laboratorium"
                                    placeholder=""
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 transition">
                            </div>
                        </div>
                    </div>

                    {{-- Row: Status Pemesanan | Biaya Ekspedisi --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label for="status_pemesanan"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Status
                                Pemesanan</label>
                            <div class="relative">
                                <select name="status_pemesanan" id="status_pemesanan"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 appearance-none cursor-pointer transition">
                                    <option value="diproses">Diproses</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="biaya_ekspedisi"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Biaya
                                Ekspedisi</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm text-gray-400 font-medium pointer-events-none">Rp</span>
                                <input type="text" inputmode="numeric" name="biaya_ekspedisi" id="biaya_ekspedisi"
                                    placeholder=""
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 transition">
                            </div>
                        </div>
                    </div>

                    {{-- Rincian Item Gigi --}}
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-sm font-bold text-[#529e85]">Rincian Item Gigi</h4>
                            <button type="button" id="tambah-item-btn"
                                class="px-4 py-2 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-xs font-semibold flex items-center transition shadow-sm">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah Item
                            </button>
                        </div>
                        <div class="rounded-lg overflow-hidden border border-gray-100">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="text-left px-4 py-3 text-[11px] font-bold text-gray-600 uppercase tracking-widest w-1/2">
                                            Jenis Gigi / Prosedur</th>
                                        <th
                                            class="text-left px-4 py-3 text-[11px] font-bold text-gray-600 uppercase tracking-widest">
                                            Harga (RP)</th>
                                        <th
                                            class="text-left px-4 py-3 text-[11px] font-bold text-gray-600 uppercase tracking-widest">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="item-table-body">
                                    <tr id="empty-row">
                                        <td class="px-4 py-6 text-sm text-gray-400">Belum ada item ditambahkan...</td>
                                        <td class="px-4 py-6 text-sm text-gray-400 text-center">-</td>
                                        <td class="px-4 py-6 text-sm text-gray-400 text-center">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Catatan Pesanan --}}
                    <div>
                        <label for="catatan_pesanan"
                            class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Catatan
                            Pesanan</label>
                        <textarea name="catatan_pesanan" id="catatan_pesanan" rows="4"
                            placeholder="Tuliskan jenis pesanan, catatan tambahan, atau permintaan khusus lainnya di sini..."
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition resize-none"></textarea>
                    </div>

                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end space-x-4 border-t border-gray-100 pt-8">
                    <a href="{{ route('pemesanan.index') }}"
                        class="px-12 py-2.5 border border-gray-300 rounded-lg text-gray-500 text-sm font-semibold hover:bg-gray-50 transition text-center shadow-md min-w-[160px]">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-12 py-2.5 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-sm font-semibold flex items-center justify-center transition shadow-md shadow-teal-900/20 min-w-[160px]">
                        <i class="fa-solid fa-save mr-2"></i> Simpan Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let itemCount = 0;

        document.getElementById('tambah-item-btn').addEventListener('click', function() {
            window.location.href = '{{ route('pemesanan.tambahitem') }}';
        });

        function hapusItem(id) {
            const row = document.getElementById(`item-row-${id}`);
            if (row) row.remove();

            const tbody = document.getElementById('item-table-body');
            if (tbody.children.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.id = 'empty-row';
                emptyRow.innerHTML = `
                <td class="px-4 py-6 text-sm text-gray-400">Belum ada item ditambahkan...</td>
                <td class="px-4 py-6 text-sm text-gray-400 text-center">-</td>
                <td class="px-4 py-6 text-sm text-gray-400 text-center">-</td>
            `;
                tbody.appendChild(emptyRow);
            }
        }
    </script>
@endsection
