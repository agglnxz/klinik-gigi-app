@extends('layouts.main')

@section('title', 'Edit Pemesanan')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-black-1000">Edit Pemesanan</h3>
                <p class="text-sm text-gray-500 font-light">Perbarui data pemesanan dan daftar gigi yang terlampir.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-5xl">
            <form action="{{ route('pemesanan.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-6 mb-12">

                    {{-- Baris 1: No Pemesanan | Pemeriksaan --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Nomor Pemesanan</label>
                            <input type="text" name="no_pemesanan" value="{{ old('no_pemesanan', $data->no_pemesanan) }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('no_pemesanan') ring-2 ring-red-500 @enderror">
                            @error('no_pemesanan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
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
                                <option value="diproses" {{ old('status_pemesanan', $data->status_pemesanan) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai" {{ old('status_pemesanan', $data->status_pemesanan) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ old('status_pemesanan', $data->status_pemesanan) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                    </div>

                    {{-- Baris 3: Tanggal Kirim | Estimasi Selesai --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Tanggal Dikirim</label>
                            <input type="date" name="tanggal_dikirim" value="{{ old('tanggal_dikirim', $data->tanggal_dikirim) }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('tanggal_dikirim') ring-2 ring-red-500 @enderror">
                            @error('tanggal_dikirim') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Estimasi Selesai</label>
                            <input type="date" name="estimasi_selesai" value="{{ old('estimasi_selesai', $data->estimasi_selesai) }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('estimasi_selesai') ring-2 ring-red-500 @enderror">
                            @error('estimasi_selesai') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Baris 4: Biaya Lab | Harga Pasien --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Biaya Tagihan Lab (Rp)</label>
                            <input type="number" name="biaya_lab" value="{{ old('biaya_lab', $data->biaya_lab) }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('biaya_lab') ring-2 ring-red-500 @enderror">
                            @error('biaya_lab') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Total Harga Pasien (Rp)</label>
                            <input type="number" name="harga_pasien" value="{{ old('harga_pasien', $data->harga_pasien) }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('harga_pasien') ring-2 ring-red-500 @enderror">
                            @error('harga_pasien') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Baris 5: Status Bayar Lab --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Status Bayar Lab</label>
                        <select name="status_bayar_lab" class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm w-1/2">
                            <option value="belum_lunas" {{ old('status_bayar_lab', $data->status_bayar_lab) == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="lunas" {{ old('status_bayar_lab', $data->status_bayar_lab) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>

                    {{-- RINCIAN BANYAK GIGI (MULTIPLE CHOICE TABLE) --}}
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-sm font-bold text-[#529e85]">Rincian Pilihan Gigi (Multiple Items)</h4>
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
                                        <th class="text-left px-4 py-3 text-[11px] font-bold text-gray-600 uppercase tracking-widest w-5/6">Pilih Jenis Gigi / Protesa</th>
                                        <th class="text-center px-4 py-3 text-[11px] font-bold text-gray-600 uppercase tracking-widest w-1/6">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="item-table-body">
                                    {{-- Render gigi yang sudah ada di database --}}
                                    @forelse ($data->items as $idx => $existingItem)
                                        <tr class="border-t border-gray-100" id="item-row-{{ $idx }}">
                                            <td class="px-4 py-3">
                                                <select name="items[]" required class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm cursor-pointer">
                                                    @foreach ($jenis_gigi as $j)
                                                        <option value="{{ $j->id }}" {{ $existingItem->id_jenis_gigi == $j->id ? 'selected' : '' }}>
                                                            {{ $j->nama_jenis }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <button type="button" onclick="hapusItem({{ $idx }})" class="text-red-400 hover:text-red-600 text-xs font-semibold transition cursor-pointer">
                                                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="empty-row">
                                            <td colspan="2" class="px-4 py-6 text-sm text-gray-400 text-center italic">Belum ada item gigi. Klik "Tambah Item Gigi" di atas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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

    {{-- DYNAMIC MULTIPLE ITEMS JAVASCRIPT --}}
    <script>
        document.getElementById('tambah-item-btn').addEventListener('click', function() {
            const tbody = document.getElementById('item-table-body');
            const emptyRow = document.getElementById('empty-row');
            if (emptyRow) emptyRow.remove();

            const rowId = Date.now();
            const tr = document.createElement('tr');
            tr.className = "border-t border-gray-100";
            tr.id = `item-row-${rowId}`;

            tr.innerHTML = `
                <td class="px-4 py-3">
                    <select name="items[]" required class="w-full px-4 py-2 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm cursor-pointer">
                        <option value="" disabled selected>Pilih Item Gigi</option>
                        @foreach ($jenis_gigi as $j)
                            <option value="{{ $j->id }}">{{ $j->nama_jenis }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="px-4 py-3 text-center">
                    <button type="button" onclick="hapusItem(${rowId})" class="text-red-400 hover:text-red-600 text-xs font-semibold transition cursor-pointer">
                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                    </button>
                </td>
            `;

            tbody.appendChild(tr);
        });

        function hapusItem(id) {
            const row = document.getElementById(`item-row-${id}`);
            if (row) row.remove();

            const tbody = document.getElementById('item-table-body');
            if (tbody.children.length === 0) {
                tbody.innerHTML = `
                    <tr id="empty-row">
                        <td colspan="2" class="px-4 py-6 text-sm text-gray-400 text-center italic">Belum ada item gigi. Klik "Tambah Item Gigi" di atas.</td>
                    </tr>
                `;
            }
        }
    </script>
@endsection
