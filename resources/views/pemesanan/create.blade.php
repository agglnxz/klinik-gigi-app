@extends('layouts.main')

@section('title', 'Registrasi Pemesanan Baru')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-black-1000">Registrasi Pemesanan Baru</h3>
                <p class="text-sm text-gray-500 font-light">Satu pemesanan dapat menampung banyak item gigi sekaligus dengan estimasi harga otomatis.</p>
            </div>
        </div>

        {{-- TEMPLATE MASTER UNTUK BARIS DINAMIS (Aman dari Serangan XSS) --}}
        <template id="row-template">
            <tr class="border-t border-gray-100 item-row">
                <td class="px-4 py-3">
                    {{-- Atribut data-price disisipkan pada setiap option untuk dibaca oleh JavaScript --}}
                    <select name="items[]" required class="item-select w-full px-4 py-2 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm cursor-pointer">
                        <option value="" data-price="0" disabled selected>Pilih Item Gigi</option>
                        @foreach ($jenis_gigi as $j)
                            <option value="{{ $j->id }}" data-price="{{ $j->estimasi_biaya }}">
                                {{ $j->nama_jenis }}
                            </option>
                        @endforeach
                    </select>
                </td>
                {{-- Tempat merender harga satuan item yang dipilih --}}
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
            <form action="{{ route('pemesanan.store') }}" method="POST">
                @csrf
                <div class="space-y-6 mb-12">

                    {{-- Baris 1: No Pemesanan | Pemeriksaan --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Nomor Pemesanan</label>
                            <input type="text" name="no_pemesanan" value="{{ $no_pemesanan }}" readonly
                                class="w-full px-4 py-3 bg-gray-100 border-none rounded-lg text-sm text-gray-500 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Pilih Pemeriksaan (Pasien)</label>
                            <select name="id_pemeriksaan" class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('id_pemeriksaan') ring-2 ring-red-500 @enderror">
                                <option value="" disabled selected>Pilih Rekam Medis</option>
                                @foreach ($pemeriksaan as $p)
                                    <option value="{{ $p->id }}" {{ old('id_pemeriksaan') == $p->id ? 'selected' : '' }}>
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
                                <option value="" disabled selected>Pilih Lab</option>
                                @foreach ($lab as $l)
                                    <option value="{{ $l->id }}" {{ old('id_lab') == $l->id ? 'selected' : '' }}>{{ $l->nama_lab }}</option>
                                @endforeach
                            </select>
                            @error('id_lab') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Status Pemesanan</label>
                            {{-- KOREKSI: Atribut value diselaraskan mutlak dengan ENUM database --}}
                            <select name="status_pemesanan" class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                                <option value="dalam_proses" {{ old('status_pemesanan') == 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                                <option value="tiba_di_klinik" {{ old('status_pemesanan') == 'tiba_di_klinik' ? 'selected' : '' }}>Telah Tiba di Klinik</option>
                                <option value="selesai" {{ old('status_pemesanan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>

                    {{-- Baris 3: Tanggal Kirim | Estimasi Selesai --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Tanggal Dikirim</label>
                            <input type="date" name="tanggal_dikirim" value="{{ old('tanggal_dikirim', date('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('tanggal_dikirim') ring-2 ring-red-500 @enderror">
                            @error('tanggal_dikirim') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Estimasi Selesai</label>
                            <input type="date" name="estimasi_selesai" value="{{ old('estimasi_selesai') }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('estimasi_selesai') ring-2 ring-red-500 @enderror">
                            @error('estimasi_selesai') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- BARIS 4: RINCIAN BANYAK GIGI --}}
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <h4 class="text-sm font-bold text-[#529e85]">Rincian Pilihan Gigi (Multiple Items)</h4>
                                <p class="text-[11px] text-gray-400">Pilih item untuk melihat akumulasi harga modal dasar.</p>
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
                                    {{-- Baris pertama akan di-render secara dinamis oleh JavaScript saat halaman dimuat --}}
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

                    {{-- BARIS 5: BIAYA LAB & HARGA PASIEN --}}
                    <div class="grid grid-cols-2 gap-x-8 pt-4 border-t border-gray-50">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Biaya Tagihan Lab Akhir (Rp)</label>
                            <input type="number" name="biaya_lab" value="{{ old('biaya_lab') }}" placeholder="Input real tagihan dari lab..."
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('biaya_lab') ring-2 ring-red-500 @enderror">
                            @error('biaya_lab') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Total Biaya Dikenakan Ke Pasien (Rp)</label>
                            <input type="number" name="harga_pasien" value="{{ old('harga_pasien') }}" placeholder="Input harga jual final ke pasien..."
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('harga_pasien') ring-2 ring-red-500 @enderror">
                            @error('harga_pasien') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Baris 6: Status Bayar Lab --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Status Pembayaran Ke Lab</label>
                        {{-- KOREKSI: Mengubah opsi 'lunas' menjadi 'sudah_lunas' --}}
                        <select name="status_bayar_lab" class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm w-1/2">
                            <option value="belum_lunas" {{ old('status_bayar_lab') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="sudah_lunas" {{ old('status_bayar_lab') == 'sudah_lunas' ? 'selected' : '' }}>Sudah Lunas</option>
                        </select>
                    </div>

                </div>

                {{-- BUTTONS --}}
                <div class="flex justify-end space-x-4 border-t border-gray-100 pt-8">
                    <a href="{{ route('pemesanan.index') }}" class="px-12 py-2.5 border border-gray-300 rounded-lg text-gray-500 text-sm font-semibold hover:bg-gray-50 transition text-center min-w-[160px]">Batal</a>
                    <button type="submit" class="px-12 py-2.5 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-sm font-semibold min-w-[160px]">
                        <i class="fa-solid fa-save mr-2"></i> Simpan Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- LOGIKA JAVASCRIPT REAKTIF (Terpisah Mutlak dari Blade) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tbody = document.getElementById('item-table-body');
            const template = document.getElementById('row-template');
            const subtotalDisplay = document.getElementById('subtotal-display');
            const tambahBtn = document.getElementById('tambah-item-btn');

            // Fungsi menghitung akumulasi total semua baris yang terpilih
            function hitungTotalGigi() {
                let total = 0;
                const selects = tbody.querySelectorAll('.item-select');

                selects.forEach(select => {
                    const selectedOption = select.selectedOptions[0];
                    if (selectedOption) {
                        total += parseInt(selectedOption.getAttribute('data-price')) || 0;
                    }
                });

                subtotalDisplay.innerText = 'Rp ' + total.toLocaleString('id-ID');
            }

            // Fungsi memvalidasi ketersediaan baris
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

            // Fungsi utama menambahkan baris baru
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
            tambahBaris();
        });
    </script>
@endsection
