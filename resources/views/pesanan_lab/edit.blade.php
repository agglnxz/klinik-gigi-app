@extends('layouts.main')

@section('title', 'Edit Pesanan Laboratorium')

@section('content')
    <div class="space-y-6 max-w-4xl">
        {{-- HEADER --}}
        <div class="flex justify-between items-end">
            <div>
                <a href="{{ route('pesanan-lab.index') }}" class="text-[11px] text-gray-400 hover:text-[#176851] font-bold uppercase tracking-widest flex items-center gap-1.5 mb-2 transition">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke Daftar Pesanan Lab
                </a>
                <div class="flex items-center gap-3">
                    <h3 class="text-2xl font-bold text-black-1000">Edit Pesanan Laboratorium</h3>
                    <span class="bg-amber-100 text-amber-800 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">
                        <i class="fa-solid fa-shield-halved mr-1"></i> Khusus Direktur
                    </span>
                </div>
                <p class="text-sm text-gray-500 font-light mt-0.5">Koreksi rincian work-order, vendor laboratorium, biaya modal lab, dan pencatatan tanggal kedatangan barang.</p>
            </div>
        </div>

        {{-- INFORMASI PEMESANAN PASIEN (READONLY) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Referensi Pemesanan Pasien</p>
            <div class="grid grid-cols-3 gap-6 text-xs">
                <div>
                    <span class="text-gray-400 block font-medium">Nomor Pemesanan:</span>
                    <span class="font-bold text-gray-800 text-sm">{{ $data->pemesanan->no_pemesanan ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium">Nama Pasien:</span>
                    <span class="font-bold text-gray-800 text-sm">
                        {{ $data->pemesanan->pemeriksaan->pasien->nama ?? 'Pasien Terhapus' }}
                        <span class="text-gray-400 text-xs font-normal">(RM: {{ $data->pemesanan->pemeriksaan->pasien->no_rm ?? '-' }})</span>
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 block font-medium">Status Booking Pasien:</span>
                    <span class="inline-block mt-0.5 px-2.5 py-0.5 bg-blue-50 text-blue-700 font-bold rounded-full uppercase text-[11px]">
                        {{ strtoupper(str_replace('_', ' ', $data->pemesanan->status_booking ?? 'pending')) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- FORM EDIT PESANAN LAB --}}
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">
            <form action="{{ route('pesanan-lab.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6 mb-8">

                    {{-- Baris 1: Vendor Laboratorium & Biaya Lab --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Vendor Laboratorium Mitra <span class="text-red-500">*</span>
                            </label>
                            <select name="id_lab" required
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm font-semibold text-gray-800 @error('id_lab') ring-2 ring-red-500 @enderror cursor-pointer">
                                @foreach ($laboratorium as $l)
                                    <option value="{{ $l->id }}" {{ old('id_lab', $data->id_lab) == $l->id ? 'selected' : '' }}>
                                        {{ $l->nama_lab }} ({{ $l->alamat ?? 'Aktif' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_lab') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Biaya Tagihan Lab / Modal (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="biaya_lab" name="biaya_lab"
                                value="{{ number_format((float) old('biaya_lab', $data->biaya_lab), 0, '', '.') }}" required
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm font-semibold text-gray-800 @error('biaya_lab') ring-2 ring-red-500 @enderror">
                            @error('biaya_lab') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Baris 2: Tanggal Dikirim & Estimasi Selesai --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Tanggal Dikirim ke Lab <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_dikirim" id="tanggal_dikirim"
                                value="{{ old('tanggal_dikirim', \Carbon\Carbon::parse($data->tanggal_dikirim)->format('Y-m-d')) }}" required
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm font-medium text-gray-800 @error('tanggal_dikirim') ring-2 ring-red-500 @enderror">
                            @error('tanggal_dikirim') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Estimasi Selesai / Tiba <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="estimasi_selesai" id="estimasi_selesai"
                                value="{{ old('estimasi_selesai', \Carbon\Carbon::parse($data->estimasi_selesai)->format('Y-m-d')) }}" required
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm font-medium text-gray-800 @error('estimasi_selesai') ring-2 ring-red-500 @enderror">
                            @error('estimasi_selesai') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Baris 3: Tanggal Tiba di Klinik & Status Bayar Lab --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Tanggal Tiba di Klinik (Opsional)
                            </label>
                            <input type="date" name="tanggal_tiba" id="tanggal_tiba"
                                value="{{ old('tanggal_tiba', $data->tanggal_tiba ? \Carbon\Carbon::parse($data->tanggal_tiba)->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm font-medium text-gray-800 @error('tanggal_tiba') ring-2 ring-red-500 @enderror">
                            <p class="text-[11px] text-gray-400 mt-1">Kosongkan jika protesa gigi masih dalam pengerjaan oleh pihak laboratorium.</p>
                            @error('tanggal_tiba') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Status Pembayaran Tagihan Lab <span class="text-red-500">*</span>
                            </label>
                            <select name="status_bayar_lab" required
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm font-semibold">
                                <option value="belum_lunas" {{ old('status_bayar_lab', $data->status_bayar_lab) == 'belum_lunas' ? 'selected' : '' }}>
                                    Belum Lunas (Utang Klinik)
                                </option>
                                <option value="sudah_lunas" {{ old('status_bayar_lab', $data->status_bayar_lab) == 'sudah_lunas' ? 'selected' : '' }}>
                                    Sudah Lunas (Lunas Dibayar)
                                </option>
                            </select>
                            @error('status_bayar_lab') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="flex justify-end space-x-4 border-t border-gray-100 pt-6">
                    <a href="{{ route('pesanan-lab.index') }}"
                        class="px-8 py-2.5 border border-gray-300 rounded-lg text-gray-500 text-sm font-semibold hover:bg-gray-50 transition text-center min-w-[140px]">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-8 py-2.5 bg-[#176851] hover:bg-[#357a66] text-white rounded-lg text-sm font-semibold min-w-[160px] cursor-pointer shadow-sm transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save mr-1"></i> Update Pesanan Lab
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- LOGIKA JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputBiayaLab = document.getElementById('biaya_lab');
            const tglKirim = document.getElementById('tanggal_dikirim');
            const tglSelesai = document.getElementById('estimasi_selesai');

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

