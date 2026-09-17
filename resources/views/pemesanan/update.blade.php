@extends('layouts.main')

@section('title', 'Edit Pemesanan')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-black-1000">Edit Pemesanan</h3>
                <p class="text-sm text-gray-500 font-light">Perbarui data pemesanan laboratorium dan rincian pilihan gigi pasien.</p>
            </div>
        </div>

        {{-- TEMPLATE MASTER UNTUK BARIS DINAMIS (Aman dari Serangan XSS) --}}
        <template id="row-template">
            <tr class="border-t border-gray-100 item-row">
                <td class="px-4 py-3">
                    <select name="items[]" required class="item-select w-full px-4 py-2 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm cursor-pointer">
                        <option value="" data-price="0" disabled selected>Pilih Item Gigi</option>
                        @foreach ($jenis_gigi as $j)
                            <option value="{{ $j->id }}" data-price="{{ $j->estimasi_biaya }}">
                                {{ $j->nama_jenis }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="px-4 py-3 text-right font-semibold text-gray-700 text-sm item-price-display">
                    Rp 0
                </td>
                <td class="px-4 py-3 text-center">
                    <button type="button" class="delete-btn text-red-400 hover:text-red-600 text-xs font-semibold transition cursor-pointer">
                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                    </button>
                </td>
            </tr>
        </template>

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-5xl">
            <form action="{{ route('pemesanan.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6 mb-12">

                    {{-- Baris 1: No Pemesanan | Pemeriksaan --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Nomor Pemesanan</label>
                            <input type="text" name="no_pemesanan" value="{{ old('no_pemesanan', $data->no_pemesanan) }}" readonly
                                class="w-full px-4 py-3 bg-gray-100 border-none rounded-lg text-sm text-gray-500 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Pilih Pemeriksaan (Pasien)</label>
                            <select name="id_pemeriksaan" class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('id_pemeriksaan') ring-2 ring-red-500 @enderror">
                                @foreach ($pemeriksaan as $p)
                                    <option value="{{ $p->id }}" {{ old('id_pemeriksaan', $data->id_pemeriksaan) == $p->id ? 'selected' : '' }}>
                                        {{ $p->no_pemeriksaan }} - {{ $p->pasien->nama ?? 'Pasien' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pemeriksaan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Baris 2: Laboratorium | Status Pemesanan --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Laboratorium Mitra</label>
                            <select name="id_lab" class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('id_lab') ring-2 ring-red-500 @enderror">
                                @foreach ($lab as $l)
                                    <option value="{{ $l->id }}" {{ old('id_lab', $data->id_lab) == $l->id ? 'selected' : '' }}>{{ $l->nama_lab }}</option>
                                @endforeach
                            </select>
                            @error('id_lab') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Status Pemesanan</label>
                            <select name="status_pemesanan" class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                                <option value="dalam_proses" {{ old('status_pemesanan', $data->status_pemesanan) == 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                                <option value="tiba_di_klinik" {{ old('status_pemesanan', $data->status_pemesanan) == 'tiba_di_klinik' ? 'selected' : '' }}>Telah Tiba di Klinik</option>
                                <option value="selesai" {{ old('status_pemesanan', $data->status_pemesanan) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>

                    {{-- Baris 3: Tanggal Kirim | Estimasi Selesai --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Tanggal Dikirim</label>
                            <input type="date" name="tanggal_dikirim" value="{{ old('tanggal_dikirim', \Carbon\Carbon::parse($data->tanggal_dikirim)->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('tanggal_dikirim') ring-2 ring-red-500 @enderror">
                            @error('tanggal_dikirim') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Estimasi Selesai</label>
                            <input type="date" name="estimasi_selesai" value="{{ old('estimasi_selesai', \Carbon\Carbon::parse($data->estimasi_selesai)->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('estimasi_selesai') ring-2 ring-red-500 @enderror">
                            @error('estimasi_selesai') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- BARIS 4: RINCIAN BANYAK GIGI --}}
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <h4 class="text-sm font-bold text-[#529e85]">Rincian Pilihan Gigi (Multiple Items)</h4>
                                <p class="text-[11px] text-gray-400">Daftar item gigi yang terlampir pada pesanan ini.</p>
                            </div>
                            <button type="button" id="tambah-item-btn"
                                class="px-4 py-2 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-xs font-semibold flex items-center transition shadow-sm cursor-pointer">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah Item Gigi
                            </button>
                        </div>

                        @error('items') <p class="text-xs text-red-500 mb-2 font-bold">⚠️ Minimal pilih 1 item gigi!</p> @enderror

                        <div class="rounded-lg overflow-hidden border border-gray-100">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-4 py-3 text-[11px] font-bold text-gray-600 uppercase tracking-widest w-7/12">Pilih Jenis Gigi / Protesa</th>
                                        <th class="text-right px-4 py-3 text-[11px] font-bold text-gray-600 uppercase tracking-widest w-3/12">Harga Satuan (Est)</th>
                                        <th class="text-center px-4 py-3 text-[11px] font-bold text-gray-600 uppercase tracking-widest w-2/12">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="item-table-body">
                                    {{-- Merender data gigi yang sudah tersimpan di database sebelumnya --}}
                                    @forelse ($data->items as $idx => $existingItem)
                                        <tr class="border-t border-gray-100 item-row">
                                            <td class="px-4 py-3">
                                                <select name="items[]" required class="item-select w-full px-4 py-2 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm cursor-pointer">
                                                    @foreach ($jenis_gigi as $j)
                                                        <option value="{{ $j->id }}" data-price="{{ $j->estimasi_biaya }}"
                                                            {{ $existingItem->id_jenis_gigi == $j->id ? 'selected' : '' }}>
                                                            {{ $j->nama_jenis }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-4 py-3 text-right font-semibold text-gray-700 text-sm item-price-display">
                                                Rp {{ number_format($existingItem->jenisGigi->estimasi_biaya ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <button type="button" class="delete-btn text-red-400 hover:text-red-600 text-xs font-semibold transition cursor-pointer">
                                                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        {{-- Diisi dinamis jika tidak ada item --}}
                                    @endforelse
                                </tbody>
                                {{-- FOOTER UNTUK TOTAL AKUMULASI HARGA GIGI --}}
                                <tfoot class="bg-[#fcfdfd] border-t border-gray-100">
                                    <tr>
                                        <td class="px-4 py-3.5 text-right font-bold text-gray-600 text-xs uppercase tracking-wider">
                                            Total Estimasi Item Gigi:
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-black text-teal-700 text-base" id="subtotal-display">
                                            Rp 0
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- BARIS 5: BIAYA LAB & DISKON --}}
                    <div class="grid grid-cols-2 gap-x-8 pt-4 border-t border-gray-50">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Biaya Tagihan Lab Akhir (Rp)</label>
                            <input type="number" id="biaya_lab" name="biaya_lab" value="{{ old('biaya_lab', $data->biaya_lab ?? 0) }}" class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Diskon (Rp)</label>
                            <input type="number" id="diskon" name="diskon" value="{{ old('diskon', $data->diskon ?? 0) }}" class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                        </div>
                    </div>

                    {{-- Baris 6: Status Bayar Lab & Total Biaya Pasien --}}
                    <div class="grid grid-cols-2 gap-x-8 mt-6">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Status Pembayaran Ke Lab</label>
                            <select name="status_bayar_lab" class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                                <option value="belum_lunas" {{ old('status_bayar_lab') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="sudah_lunas" {{ old('status_bayar_lab') == 'sudah_lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Total Harga Pasien (Otomatis)</label>
                            <input type="number" id="harga_pasien" name="harga_pasien" value="{{ old('harga_pasien', $data->harga_pasien ?? 0) }}" readonly class="w-full px-4 py-3 bg-gray-200 border-none rounded-lg text-sm text-gray-600 font-bold cursor-not-allowed">
                        </div>
                    </div>

                    {{-- Baris 6: Status Bayar Lab --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Status Pembayaran Ke Lab</label>
                        <select name="status_bayar_lab" class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm w-1/2">
                            <option value="belum_lunas" {{ old('status_bayar_lab', $data->status_bayar_lab) == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="sudah_lunas" {{ old('status_bayar_lab', $data->status_bayar_lab) == 'sudah_lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                        </select>
                    </div>

                </div>

                {{-- BUTTONS --}}
                <div class="flex justify-end space-x-4 border-t border-gray-100 pt-8">
                    <a href="{{ route('pemesanan.index') }}" class="px-12 py-2.5 border border-gray-300 rounded-lg text-gray-500 text-sm font-semibold hover:bg-gray-50 transition text-center min-w-[160px]">Batal</a>
                    <button type="submit" class="px-12 py-2.5 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-sm font-semibold min-w-[160px]">
                        <i class="fa-solid fa-save mr-2"></i> Update Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- LOGIKA JAVASCRIPT REAKTIF --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tbody = document.getElementById('item-table-body');
            const template = document.getElementById('row-template');
            const subtotalDisplay = document.getElementById('subtotal-display');
            const tambahBtn = document.getElementById('tambah-item-btn');

            // Kalkulasi akumulasi total
            function hitungTotalGigi() {
                let totalGigi = 0;
                // 1. Hitung total semua item gigi
                const selects = tbody.querySelectorAll('.item-select');
                selects.forEach(select => {
                    const selectedOption = select.selectedOptions[0];
                    if (selectedOption) {
                        totalGigi += parseInt(selectedOption.getAttribute('data-price')) || 0;
                    }
                });
                subtotalDisplay.innerText = 'Rp ' + totalGigi.toLocaleString('id-ID');

                // 2. Kalkulasi Total Akhir (Gigi + Lab - Diskon)
                const biayaLab = parseInt(document.getElementById('biaya_lab').value) || 0;
                const diskon = parseInt(document.getElementById('diskon').value) || 0;

                let totalAkhir = (totalGigi + biayaLab) - diskon;
                totalAkhir = totalAkhir > 0 ? totalAkhir : 0; // Cegah minus
                document.getElementById('harga_pasien').value = totalAkhir;
            }

            // Tambahkan Event Listener ini agar total berubah saat Biaya Lab / Diskon diketik
            document.getElementById('biaya_lab').addEventListener('input', hitungTotalGigi);
            document.getElementById('diskon').addEventListener('input', hitungTotalGigi);

            // Pesan kosong jika seluruh baris dihapus
            function cekTabelKosong() {
                if (tbody.children.length === 0) {
                    tbody.innerHTML = `
                        <tr id="empty-row">
                            <td colspan="3" class="px-4 py-6 text-sm text-gray-400 text-center italic">
                                Belum ada item gigi yang dipilih. Klik tombol "Tambah Item Gigi" di atas.
                            </td>
                        </tr>
                    `;
                    subtotalDisplay.innerText = 'Rp 0';
                }
            }

            // 1. DAFTARKAN EVENT PADA BARIS YANG SUDAH ADA DI DATABASE
            tbody.querySelectorAll('.item-row').forEach(row => {
                const select = row.querySelector('.item-select');
                const priceDisplay = row.querySelector('.item-price-display');
                const deleteBtn = row.querySelector('.delete-btn');

                select.addEventListener('change', function () {
                    const price = parseInt(this.selectedOptions[0].getAttribute('data-price')) || 0;
                    priceDisplay.innerText = 'Rp ' + price.toLocaleString('id-ID');
                    hitungTotalGigi();
                });

                deleteBtn.addEventListener('click', function () {
                    row.remove();
                    cekTabelKosong();
                    hitungTotalGigi();
                });
            });

            // 2. FUNGSI UNTUK MENAMBAH BARIS BARU
            function tambahBaris() {
                const emptyRow = document.getElementById('empty-row');
                if (emptyRow) emptyRow.remove();

                const clone = template.content.cloneNode(true);
                const newRow = clone.querySelector('tr');
                const selectElement = clone.querySelector('.item-select');
                const priceDisplay = clone.querySelector('.item-price-display');
                const deleteBtn = clone.querySelector('.delete-btn');

                selectElement.addEventListener('change', function () {
                    const price = parseInt(this.selectedOptions[0].getAttribute('data-price')) || 0;
                    priceDisplay.innerText = 'Rp ' + price.toLocaleString('id-ID');
                    hitungTotalGigi();
                });

                deleteBtn.addEventListener('click', function () {
                    newRow.remove();
                    cekTabelKosong();
                    hitungTotalGigi();
                });

                tbody.appendChild(clone);
            }

            tambahBtn.addEventListener('click', tambahBaris);

            // Inisialisasi awal saat halaman dimuat
            hitungTotalGigi();
            cekTabelKosong();
        });
    </script>
@endsection
