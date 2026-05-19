@extends('layouts.main')

@section('title', isset($pemeriksaan) ? 'Edit Pemeriksaan' : 'Tambah Pemeriksaan')

@section('content')
    <div class="space-y-6">

        <div class="flex justify-between items-end">
            <div>
                <h3 class="text-2xl font-bold text-black-1000">
                    {{ isset($pemeriksaan) ? 'Edit Pemeriksaan Gigi' : 'Formulir Pemeriksaan Gigi' }}
                </h3>

                <p class="text-sm text-gray-500 font-light">
                    Lengkapi detail pemeriksaan pasien untuk pencatatan rekam medis yang akurat.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-5xl">

            {{-- FORM --}}
            <form
                action="{{ isset($pemeriksaan) ? route('pemeriksaan.update', $pemeriksaan->id) : route('pemeriksaan.store') }}"
                method="POST">

                @csrf

                {{-- METHOD UNTUK EDIT --}}
                @if (isset($pemeriksaan))
                    @method('PUT')
                @endif

                <div class="space-y-6 mb-12">

                    {{-- Baris 1: Nomor Pemeriksaan (Wajib Ada) --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                            Nomor Pemeriksaan
                        </label>
                        <input type="text" name="no_pemeriksaan"
                            value="{{ old('no_pemeriksaan', $pemeriksaan->no_pemeriksaan ?? '') }}"
                            placeholder="Contoh: PRX-20260511-001"
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('no_pemeriksaan') ring-2 ring-red-500 @enderror">
                        @error('no_pemeriksaan')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Baris 2: Pasien & Tanggal --}}
                    <div class="grid grid-cols-2 gap-x-8">

                        {{-- PASIEN --}}
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Pasien
                            </label>

                            <div class="relative">
                                <select name="pasien"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('pasien') ring-2 ring-red-500 @enderror">

                                    <option value="" disabled
                                        {{ old('pasien', $pemeriksaan->id_pasien ?? '') ? '' : 'selected' }}>Pilih Pasien
                                    </option>

                                    @foreach ($pasien as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('pasien', $pemeriksaan->id_pasien ?? '') == $item->id ? 'selected' : '' }}>
                                            {{ $item->no_rm }} - {{ $item->nama }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            @error('pasien')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- TANGGAL --}}
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Tanggal Pemeriksaan
                            </label>

                            <input type="date" name="tanggal_pemeriksaan"
                                value="{{ old('tanggal_pemeriksaan', $pemeriksaan->tanggal ?? date('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('tanggal_pemeriksaan') ring-2 ring-red-500 @enderror">
                            @error('tanggal_pemeriksaan')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Baris 3: Dokter & Asisten --}}
                    <div class="grid grid-cols-2 gap-x-8">

                        {{-- DOKTER --}}
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Dokter Gigi
                            </label>

                            <select name="dokter_gigi"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('dokter_gigi') ring-2 ring-red-500 @enderror">

                                <option value="" disabled
                                    {{ old('dokter_gigi', $pemeriksaan->id_dokter ?? '') ? '' : 'selected' }}>Pilih Dokter
                                </option>

                                @foreach ($dokter as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('dokter_gigi', $pemeriksaan->id_dokter ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach

                            </select>
                            @error('dokter_gigi')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ASISTEN --}}
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Asisten Dokter <span class="normal-case font-normal text-gray-400">(opsional)</span>
                            </label>

                            <select name="asisten_dokter"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('asisten_dokter') ring-2 ring-red-500 @enderror">

                                <option value=""
                                    {{ old('asisten_dokter', $pemeriksaan->id_asisten ?? '') ? '' : 'selected' }}>Tidak ada
                                    asisten</option>

                                @foreach ($asisten as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('asisten_dokter', $pemeriksaan->id_asisten ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach

                            </select>
                            @error('asisten_dokter')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- CATATAN --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                            Catatan Klinis & Keluhan
                        </label>

                        <textarea name="catatan_klinis" rows="5" placeholder="Tuliskan detail pemeriksaan..."
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm resize-none @error('catatan_klinis') ring-2 ring-red-500 @enderror">{{ old('catatan_klinis', $pemeriksaan->catatan ?? '') }}</textarea>
                        @error('catatan_klinis')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end space-x-4 border-t border-gray-100 pt-8">

                    <a href="{{ route('pemeriksaan.index') }}"
                        class="px-12 py-2.5 border border-gray-300 rounded-lg text-gray-500 text-sm font-semibold hover:bg-gray-50 transition text-center min-w-[160px]">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-12 py-2.5 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-sm font-semibold">
                        <i class="fa-solid fa-save mr-2"></i>
                        {{ isset($pemeriksaan) ? 'Update Pemeriksaan' : 'Simpan Pemeriksaan' }}
                    </button>

                </div>

            </form>

        </div>
    </div>
@endsection
