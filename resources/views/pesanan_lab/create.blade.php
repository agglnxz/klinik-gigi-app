@extends('layouts.main')

@section('title', 'Kirim Order ke Laboratorium')

@section('content')
    <div class="space-y-6">
        {{-- HEADER --}}
        <div class="flex justify-between items-end">
            <div>
                <a href="{{ route('pemesanan.index') }}" class="text-[11px] text-gray-400 hover:text-[#176851] font-bold uppercase tracking-widest flex items-center gap-1.5 mb-2 transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Daftar Pemesanan
                </a>
                <h3 class="text-2xl font-bold text-black-1000">Kirim Order ke Laboratorium</h3>
                <p class="text-sm text-gray-500 font-light">Pilih transaksi pasien yang berstatus Pending untuk diteruskan ke vendor laboratorium mitra.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-4xl">
            <form action="{{ route('pesanan-lab.store') }}" method="POST">
                @csrf

                <div class="space-y-6 mb-10">

                    {{-- 1. PILIH PEMESANAN PASIEN PENDING --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                            Pilih Pemesanan Pasien (Status Pending) <span class="text-red-500">*</span>
                        </label>

                        @if ($pemesanan_pending->isEmpty())
                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-center gap-3 text-amber-800 text-xs">
                                <i class="fa-solid fa-circle-exclamation text-base shrink-0"></i>
                                <span>Saat ini tidak ada pesanan pasien yang berstatus <strong>Pending</strong>. Semua pesanan sudah diproses ke lab atau selesai.</span>
                            </div>
                        @else
                            <select name="id_pemesanan" id="id_pemesanan" required
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm font-semibold text-gray-800 @error('id_pemesanan') ring-2 ring-red-500 @enderror cursor-pointer">
                                <option value="" disabled {{ !isset($selected_id) ? 'selected' : '' }}>-- Pilih Pemesanan Pasien --</option>
                                @foreach ($pemesanan_pending as $psn)
                                    <option value="{{ $psn->id }}"
                                        {{ (old('id_pemesanan', $selected_id ?? null) == $psn->id) ? 'selected' : '' }}
                                        data-pasien="{{ $psn->pemeriksaan->pasien->nama ?? 'Pasien' }}"
                                        data-rm="{{ $psn->pemeriksaan->pasien->no_rm ?? '-' }}"
                                        data-items="{{ $psn->items->pluck('jenisGigi.nama_jenis')->join(', ') ?: '-' }}"
                                        data-total-items="{{ $psn->items->count() }}">
                                        {{ $psn->no_pemesanan }} — {{ $psn->pemeriksaan->pasien->nama ?? 'Pasien' }} ({{ $psn->items->count() }} item gigi)
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pemesanan')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror

                            {{-- PREVIEW DETAIL ITEM PASIEN YANG DIPILIH --}}
                            <div id="preview-pesanan" class="mt-4 p-4 bg-[#f8faf9] border border-gray-100 rounded-xl hidden">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Detail Pilihan Pasien</p>
                                <div class="grid grid-cols-2 gap-4 text-xs">
                                    <div>
                                        <span class="text-gray-400 block">Nama Pasien:</span>
                                        <span class="font-bold text-gray-800" id="preview-nama">-</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400 block">No. Rekam Medis:</span>
                                        <span class="font-bold text-gray-800" id="preview-rm">-</span>
                                    </div>
                                    <div class="col-span-2">
                                        <span class="text-gray-400 block">Item Gigi yang Dikerjakan Lab:</span>
                                        <span class="font-semibold text-teal-800" id="preview-items">-</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- 2. VENDOR LABORATORIUM & BIAYA LAB --}}
                    <div class="grid grid-cols-2 gap-x-8 pt-4 border-t border-gray-100">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Laboratorium Mitra <span class="text-red-500">*</span>
                            </label>
                            <select name="id_lab" required
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('id_lab') ring-2 ring-red-500 @enderror cursor-pointer">
                                <option value="" disabled selected>-- Pilih Laboratorium --</option>
                                @foreach ($laboratorium as $l)
                                    <option value="{{ $l->id }}" {{ old('id_lab') == $l->id ? 'selected' : '' }}>
                                        {{ $l->nama_lab }} ({{ $l->alamat ?? 'Aktif' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_lab')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Biaya Tagihan Lab / Modal (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="biaya_lab" name="biaya_lab" value="{{ old('biaya_lab') }}"
                                placeholder="Contoh: 150.000" required
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm font-semibold text-gray-800 @error('biaya_lab') ring-2 ring-red-500 @enderror">
                            @error('biaya_lab')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- 3. TANGGAL DIKIRIM & ESTIMASI SELESAI --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Tanggal Dikirim ke Lab <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_dikirim" id="tanggal_dikirim"
                                value="{{ old('tanggal_dikirim', date('Y-m-d')) }}" required
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm font-medium text-gray-800 @error('tanggal_dikirim') ring-2 ring-red-500 @enderror">
                            @error('tanggal_dikirim')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Estimasi Selesai / Tiba <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="estimasi_selesai" id="estimasi_selesai"
                                value="{{ old('estimasi_selesai', date('Y-m-d', strtotime('+3 days'))) }}" required
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm font-medium text-gray-800 @error('estimasi_selesai') ring-2 ring-red-500 @enderror">
                            @error('estimasi_selesai')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- 4. STATUS PEMBAYARAN KE LAB --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                            Status Pembayaran Tagihan Lab
                        </label>
                        <select name="status_bayar_lab"
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                            <option value="belum_lunas" {{ old('status_bayar_lab') == 'belum_lunas' ? 'selected' : '' }}>
                                Belum Lunas (Utang Klinik)
                            </option>
                            <option value="sudah_lunas" {{ old('status_bayar_lab') == 'sudah_lunas' ? 'selected' : '' }}>
                                Sudah Lunas (Langsung Dibayar)
                            </option>
                        </select>
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="flex justify-end space-x-4 border-t border-gray-100 pt-6">
                    <a href="{{ route('pemesanan.index') }}"
                        class="px-8 py-2.5 border border-gray-300 rounded-lg text-gray-500 text-sm font-semibold hover:bg-gray-50 transition text-center min-w-[140px]">
                        Batal
                    </a>
                    <button type="submit" {{ $pemesanan_pending->isEmpty() ? 'disabled' : '' }}
                        class="px-8 py-2.5 bg-[#176851] hover:bg-[#357a66] disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg text-sm font-semibold min-w-[160px] cursor-pointer shadow-sm transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane text-xs"></i> Kirim Order ke Lab
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- LOGIKA JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectPemesanan = document.getElementById('id_pemesanan');
            const previewBox = document.getElementById('preview-pesanan');
            const previewNama = document.getElementById('preview-nama');
            const previewRm = document.getElementById('preview-rm');
            const previewItems = document.getElementById('preview-items');
            const inputBiayaLab = document.getElementById('biaya_lab');
            const tglKirim = document.getElementById('tanggal_dikirim');
            const tglSelesai = document.getElementById('estimasi_selesai');

            // 1. Tampilkan Preview Pemesanan saat dipilih
            function updatePreview() {
                if (!selectPemesanan) return;
                const opt = selectPemesanan.selectedOptions[0];
                if (opt && opt.value) {
                    previewNama.innerText = opt.getAttribute('data-pasien') || '-';
                    previewRm.innerText = opt.getAttribute('data-rm') || '-';
                    previewItems.innerText = opt.getAttribute('data-items') || '-';
                    previewBox.classList.remove('hidden');
                } else {
                    previewBox.classList.add('hidden');
                }
            }

            if (selectPemesanan) {
                selectPemesanan.addEventListener('change', updatePreview);
                updatePreview();
            }

            // 2. Format Rupiah Input Biaya Lab
            if (inputBiayaLab) {
                inputBiayaLab.addEventListener('input', function() {
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
                });
            }

            // 3. Validasi Tanggal: Estimasi Selesai minimal >= Tanggal Kirim
            if (tglKirim && tglSelesai) {
                tglKirim.addEventListener('change', function() {
                    tglSelesai.min = this.value;
                    if (tglSelesai.value < this.value) {
                        tglSelesai.value = this.value;
                    }
                });
                tglSelesai.min = tglKirim.value;
            }
        });
    </script>
@endsection

