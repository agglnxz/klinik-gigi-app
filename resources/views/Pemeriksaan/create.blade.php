@extends('layouts.main')

@section('title', 'Tambah Pemeriksaan')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-black-1000">Formulir Pemeriksaan Gigi</h3>
                <p class="text-sm text-gray-500 font-light">Lengkapi detail pemeriksaan pasien untuk pencatatan rekam medis
                    yang akurat.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-5xl">
            <form action="{{ route('pemeriksaan.store') }}" method="POST">
                @csrf
                <div class="space-y-6 mb-12">

                    {{-- Row 1: Nomor Pemeriksaan | Tanggal Pemeriksaan --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label for="no_pemeriksaan"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Nomor Pemeriksaan
                            </label>
                            <input type="text" readonly  name="no_pemeriksaan" id="no_pemeriksaan"
                                value="{{ $no_pemeriksaan }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 transition @error('no_pemeriksaan') ring-2 ring-red-500 @enderror">
                                <p class="text-[10px] text-gray-400 mt-1">*Dihasilkan secara otomatis oleh sistem</p>
                        </div>

                        <div>
                            <label for="tanggal"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Tanggal Pemeriksaan
                            </label>
                            {{-- Menggunakan nilai default tanggal hari ini --}}
                            <input type="date" name="tanggal" id="tanggal"
                                value="{{ old('tanggal', date('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 transition @error('tanggal') ring-2 ring-red-500 @enderror">
                            @error('tanggal')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 2: Pasien | Dokter Gigi --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label for="id_pasien"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Pasien
                            </label>
                            <div class="relative">
                                <select name="id_pasien" id="id_pasien"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 appearance-none cursor-pointer transition @error('id_pasien') ring-2 ring-red-500 @enderror">
                                    <option value="" disabled {{ old('id_pasien') ? '' : 'selected' }}>Pilih Pasien
                                    </option>
                                    @foreach ($pasien as $p)
                                        <option value="{{ $p->id }}" {{ old('id_pasien') == $p->id ? 'selected' : '' }}>
                                            {{ $p->no_rm }} - {{ $p->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('id_pasien')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="id_dokter"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Dokter Gigi
                            </label>
                            <div class="relative">
                                <select name="id_dokter" id="id_dokter"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 appearance-none cursor-pointer transition @error('id_dokter') ring-2 ring-red-500 @enderror">
                                    <option value="" disabled {{ old('id_dokter') ? '' : 'selected' }}>Pilih Dokter
                                    </option>
                                    @foreach ($dokter as $d)
                                        <option value="{{ $d->id }}"
                                            {{ old('id_dokter') == $d->id ? 'selected' : '' }}>
                                            {{ $d->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('id_dokter')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Row 3: Asisten Dokter (setengah grid) --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label for="id_asisten"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Asisten Dokter <span class="normal-case font-normal text-gray-400"></span>
                            </label>
                            <div class="relative">
                                <select name="id_asisten" id="id_asisten"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 appearance-none cursor-pointer transition @error('id_asisten') ring-2 ring-red-500 @enderror">
                                    <option value="" {{ old('id_asisten') ? '' : 'selected' }}>Tidak ada asisten
                                    </option>
                                    @foreach ($asisten as $a)
                                        <option value="{{ $a->id }}"
                                            {{ old('id_asisten') == $a->id ? 'selected' : '' }}>
                                            {{ $a->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('id_asisten')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Catatan Klinis & Keluhan (full width) --}}
                    <div>
                        <label for="catatan"
                            class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                            Catatan Klinis & Keluhan
                        </label>
                        <textarea name="catatan" id="catatan" rows="5"
                            placeholder="Tuliskan detail pemeriksaan, keluhan pasien, atau diagnosa awal di sini..."
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition resize-none @error('catatan') ring-2 ring-red-500 @enderror">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end space-x-4 border-t border-gray-100 pt-8">
                    <a href="{{ route('pemeriksaan.index') }}"
                        class="px-12 py-2.5 border border-gray-300 rounded-lg text-gray-500 text-sm font-semibold hover:bg-gray-50 transition text-center shadow-md min-w-[160px]">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-12 py-2.5 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-sm font-semibold flex items-center justify-center transition shadow-md shadow-teal-900/20 min-w-[160px]">
                        <i class="fa-solid fa-save mr-2"></i> Simpan Pemeriksaan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
