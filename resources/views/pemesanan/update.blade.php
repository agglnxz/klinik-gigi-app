@extends('layouts.main')

@section('title', 'Edit Pemesanan Pasien')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-black-1000">Edit Pemesanan Pasien</h3>
                <p class="text-sm text-gray-500 font-light">Perbarui rincian tindakan pasien, biaya pemasangan, diskon, dan status booking klinis.</p>
            </div>
        </div>

        {{-- TEMPLATE MASTER UNTUK BARIS DINAMIS --}}
        <template id="row-template">
            <tr class="border-t border-gray-100 item-row">
                <td class="px-4 py-3">
                    <select name="items[]" required
                        class="item-select w-full px-4 py-2 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm cursor-pointer">
                        <option value="" data-price="0" disabled selected>Pilih Item Gigi</option>
                        @foreach ($jenis_gigi as $j)
                            <option value="{{ $j->id }}" data-price="{{ $j->estimasi_biaya }}">
                                {{ $j->nama_jenis }} (Rp {{ number_format($j->estimasi_biaya, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="px-4 py-3 text-right font-semibold text-gray-700 text-sm item-price-display">
                    Rp 0
                </td>
                <td class="px-4 py-3 text-center">
                    <button type="button"
                        class="delete-btn text-red-400 hover:text-red-600 text-xs font-semibold transition cursor-pointer">
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

                    {{-- Baris 1: No Pemesanan | Pemeriksaan Pasien --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Nomor Pemesanan
                            </label>
                            <input type="text" name="no_pemesanan" value="{{ old('no_pemesanan', $data->no_pemesanan) }}" readonly
                                class="w-full px-4 py-3 bg-gray-100 border-none rounded-lg text-sm text-gray-500 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Pilih Pemeriksaan (Pasien)
                            </label>
                            <select name="id_pemeriksaan" required
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('id_pemeriksaan') ring-2 ring-red-500 @enderror">
                                @foreach ($pemeriksaan as $p)
                                    <option value="{{ $p->id }}" {{ old('id_pemeriksaan', $data->id_pemeriksaan) == $p->id ? 'selected' : '' }}>
                                        {{ $p->no_pemeriksaan }} - {{ $p->pasien->nama ?? 'Pasien' }} (RM: {{ $p->pasien->no_rm ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pemeriksaan')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris 2: Status Booking Pasien | Status Bayar Pasien --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Status Booking Alur Klinis
                            </label>
                            <select name="status_booking" required
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm font-semibold">
                                <option value="pending" {{ old('status_booking', $data->status_booking) == 'pending' ? 'selected' : '' }}>
                                    Pending (Belum ke Lab)
                                </option>
                                <option value="proses_lab" {{ old('status_booking', $data->status_booking) == 'proses_lab' ? 'selected' : '' }}>
                                    Proses Lab (Dikerjakan Lab)
                                </option>
                                <option value="siap_pasang" {{ old('status_booking', $data->status_booking) == 'siap_pasang' ? 'selected' : '' }}>
                                    Siap Pasang (Gigi Tiba di Klinik)
                                </option>
                                <option value="selesai" {{ old('status_booking', $data->status_booking) == 'selesai' ? 'selected' : '' }}>
                                    Selesai (Sudah Dipasang ke Pasien)
                                </option>
                            </select>
                            @error('status_booking')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Status Pembayaran Pasien
                            </label>
                            <select name="status_bayar_pasien" required
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm font-semibold">
                                <option value="belum_lunas" {{ old('status_bayar_pasien', $data->status_bayar_pasien) == 'belum_lunas' ? 'selected' : '' }}>
                                    Belum Lunas
                                </option>
                                <option value="sudah_lunas" {{ old('status_bayar_pasien', $data->status_bayar_pasien) == 'sudah_lunas' ? 'selected' : '' }}>
                                    Sudah Lunas
                                </option>
                            </select>
                            @error('status_bayar_pasien')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris 3: RINCIAN BANYAK GIGI --}}
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <h4 class="text-sm font-bold text-[#529e85]">Rincian Pilihan Gigi (Multiple Items)</h4>
                                <p class="text-[11px] text-gray-400">Daftar item jenis gigi / protesa yang terlampir pada transaksi ini.</p>
                            </div>
                            <button type="button" id="tambah-item-btn"
                                class="px-4 py-2 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-xs font-semibold flex items-center transition shadow-sm cursor-pointer">
                                <i class="fa-solid fa-plus mr-2"></i> Tambah Item Gigi
                            </button>
                        </div>

                        @error('items')
                            <p class="text-xs text-red-500 mb-2 font-bold">⚠️ Minimal pilih 1 item gigi!</p>
                        @enderror

                        <div class="rounded-lg overflow-hidden border border-gray-100">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-4 py-3 text-[11px] font-bold text-gray-600 uppercase tracking-widest w-7/12">
                                            Pilih Jenis Gigi / Protesa
                                        </th>
                                        <th class="text-right px-4 py-3 text-[11px] font-bold text-gray-600 uppercase tracking-widest w-3/12">
                                            Harga Satuan (Est)
                                        </th>
                                        <th class="text-center px-4 py-3 text-[11px] font-bold text-gray-600 uppercase tracking-widest w-2/12">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="item-table-body">
                                    @forelse ($data->items as $idx => $existingItem)
                                        <tr class="border-t border-gray-100 item-row">
                                            <td class="px-4 py-3">
                                                <select name="items[]" required
                                                    class="item-select w-full px-4 py-2 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm cursor-pointer">
                                                    @foreach ($jenis_gigi as $j)
                                                        <option value="{{ $j->id }}" data-price="{{ $j->estimasi_biaya }}"
                                                            {{ $existingItem->id_jenis_gigi == $j->id ? 'selected' : '' }}>
                                                            {{ $j->nama_jenis }} (Rp {{ number_format($j->estimasi_biaya, 0, ',', '.') }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-4 py-3 text-right font-semibold text-gray-700 text-sm item-price-display">
                                                Rp {{ number_format($existingItem->harga_jual_pasien ?? ($existingItem->jenisGigi->estimasi_biaya ?? 0), 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <button type="button"
                                                    class="delete-btn text-red-400 hover:text-red-600 text-xs font-semibold transition cursor-pointer">
                                                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-[#fcfdfd] border-t border-gray-100">
                                    <tr>
                                        <td class="px-4 py-3.5 text-right font-bold text-gray-600 text-xs uppercase tracking-wider">
                                            Total Biaya Item Gigi:
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

                    {{-- Baris 4: BIAYA PEMASANGAN & DISKON --}}
                    <div class="grid grid-cols-2 gap-x-8 pt-4 border-t border-gray-50">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Biaya Tindakan / Pemasangan (Rp)
                            </label>
                            <input type="text" id="biaya_pemasangan" name="biaya_pemasangan"
                                value="{{ number_format((float) old('biaya_pemasangan', $data->biaya_pemasangan ?? 0), 0, '', '.') }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm font-semibold text-gray-800">
                            @error('biaya_pemasangan')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Diskon Tagihan Pasien (%)
                            </label>
                            <select id="diskon" name="diskon"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                                @for ($i = 0; $i <= 100; $i += 5)
                                    <option value="{{ $i }}" {{ old('diskon', (int) $data->diskon) == $i ? 'selected' : '' }}>
                                        {{ $i }}% {{ $i == 0 ? '(Tanpa Diskon)' : ($i == 100 ? '(Gratis)' : '') }}
                                    </option>
                                @endfor
                            </select>
                            @error('diskon')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris 5: TOTAL HARGA PASIEN --}}
                    <div class="pt-2">
                        <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                            Total Tagihan Pasien (Otomatis)
                        </label>
                        <input type="text" id="harga_pasien" name="harga_pasien"
                            value="Rp {{ number_format((float) old('harga_pasien', $data->harga_pasien ?? 0), 0, ',', '.') }}" readonly
                            class="w-full px-4 py-3 bg-teal-50/50 border border-teal-100 rounded-lg text-sm text-teal-800 font-black cursor-not-allowed">
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="flex justify-end space-x-4 border-t border-gray-100 pt-8">
                    <a href="{{ route('pemesanan.index') }}"
                        class="px-12 py-2.5 border border-gray-300 rounded-lg text-gray-500 text-sm font-semibold hover:bg-gray-50 transition text-center min-w-[160px]">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-12 py-2.5 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-sm font-semibold min-w-[160px] cursor-pointer shadow-sm transition">
                        <i class="fa-solid fa-save mr-2"></i> Update Pemesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- LOGIKA JAVASCRIPT REAKTIF --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tbody = document.getElementById('item-table-body');
            const template = document.getElementById('row-template');
            const subtotalDisplay = document.getElementById('subtotal-display');
            const tambahBtn = document.getElementById('tambah-item-btn');
            const inputBiayaPasang = document.getElementById('biaya_pemasangan');
            const inputDiskon = document.getElementById('diskon');
            const inputHargaPasien = document.getElementById('harga_pasien');

            function hitungTotal() {
                let totalGigi = 0;

                const selects = tbody.querySelectorAll('.item-select');
                selects.forEach(select => {
                    const selectedOption = select.selectedOptions[0];
                    if (selectedOption) {
                        totalGigi += parseInt(selectedOption.getAttribute('data-price')) || 0;
                    }
                });
                subtotalDisplay.innerText = 'Rp ' + totalGigi.toLocaleString('id-ID');

                let biayaPasangStr = inputBiayaPasang.value.replace(/[^,\d]/g, '');
                let biayaPemasangan = parseInt(biayaPasangStr) || 0;

                let diskonPersen = parseInt(inputDiskon.value) || 0;

                let subtotal = totalGigi + biayaPemasangan;
                let nominalDiskon = subtotal * (diskonPersen / 100);
                let hargaPasien = subtotal - nominalDiskon;

                hargaPasien = hargaPasien > 0 ? Math.round(hargaPasien) : 0;
                inputHargaPasien.value = 'Rp ' + hargaPasien.toLocaleString('id-ID');
            }

            inputBiayaPasang.addEventListener('input', function() {
                let value = this.value.replace(/[^,\d]/g, '');
                let split = value.split(',');
                let sisa = split[0].length % 3;
                let rupiah = split[0].substr(0, sisa);
                let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                this.value = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                hitungTotal();
            });

            inputDiskon.addEventListener('change', hitungTotal);

            // Pasang event listener ke baris yang sudah ada
            tbody.querySelectorAll('.item-row').forEach(row => {
                const select = row.querySelector('.item-select');
                const priceDisplay = row.querySelector('.item-price-display');
                const deleteBtn = row.querySelector('.delete-btn');

                select.addEventListener('change', function() {
                    const price = parseInt(this.selectedOptions[0].getAttribute('data-price')) || 0;
                    priceDisplay.innerText = 'Rp ' + price.toLocaleString('id-ID');
                    hitungTotal();
                });

                deleteBtn.addEventListener('click', function() {
                    if (tbody.querySelectorAll('.item-row').length > 1) {
                        row.remove();
                        hitungTotal();
                    } else {
                        alert('Minimal harus ada 1 item gigi.');
                    }
                });
            });

            function tambahBaris() {
                const clone = template.content.cloneNode(true);
                const newRow = clone.querySelector('tr');
                const selectElement = clone.querySelector('.item-select');
                const priceDisplay = clone.querySelector('.item-price-display');
                const deleteBtn = clone.querySelector('.delete-btn');

                selectElement.addEventListener('change', function() {
                    const price = parseInt(this.selectedOptions[0].getAttribute('data-price')) || 0;
                    priceDisplay.innerText = 'Rp ' + price.toLocaleString('id-ID');
                    hitungTotal();
                });

                deleteBtn.addEventListener('click', function() {
                    if (tbody.querySelectorAll('.item-row').length > 1) {
                        newRow.remove();
                        hitungTotal();
                    } else {
                        alert('Minimal harus ada 1 item gigi.');
                    }
                });

                tbody.appendChild(clone);
            }

            tambahBtn.addEventListener('click', tambahBaris);

            // Hitung pertama saat halaman dimuat
            hitungTotal();
        });
    </script>
@endsection
