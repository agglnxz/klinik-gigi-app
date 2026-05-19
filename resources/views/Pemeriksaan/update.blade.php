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
            <form action="{{ isset($pemeriksaan) ? route('pemeriksaan.update', $pemeriksaan->id) : route('pemeriksaan.store') }}" method="POST">
                @csrf

                {{-- METHOD UNTUK EDIT --}}
                @if (isset($pemeriksaan))
                    @method('PUT')
                @endif

                <div class="space-y-6 mb-12">

                    {{-- Baris 1: Nomor Pemeriksaan (Readonly / Locked) --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                            Nomor Pemeriksaan
                        </label>
                        <input type="text" name="no_pemeriksaan"
                            value="{{ isset($pemeriksaan) ? $pemeriksaan->no_pemeriksaan : ($no_pemeriksaan ?? '') }}"
                            readonly
                            class="w-full px-4 py-3 bg-gray-100 border-none rounded-lg text-sm text-gray-500 cursor-not-allowed transition">
                    </div>

                    {{-- Baris 2: Pasien & Tanggal --}}
                    <div class="grid grid-cols-2 gap-x-8">

                        {{-- KOREKSI: Atribut name diubah menjadi id_pasien --}}
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Pasien
                            </label>

                            <div class="relative">
                                <select name="id_pasien"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('id_pasien') ring-2 ring-red-500 @enderror">

                                    <option value="" disabled {{ old('id_pasien', $pemeriksaan->id_pasien ?? '') ? '' : 'selected' }}>
                                        Pilih Pasien
                                    </option>

                                    @foreach ($pasien as $item)
                                        <option value="{{ $item->id }}" {{ old('id_pasien', $pemeriksaan->id_pasien ?? '') == $item->id ? 'selected' : '' }}>
                                            {{ $item->no_rm }} - {{ $item->nama }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            @error('id_pasien')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- KOREKSI: Atribut name diubah menjadi tanggal --}}
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Tanggal Pemeriksaan
                            </label>

                            <input type="date" name="tanggal"
                                value="{{ old('tanggal', isset($pemeriksaan) ? \Carbon\Carbon::parse($pemeriksaan->tanggal)->format('Y-m-d') : date('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('tanggal') ring-2 ring-red-500 @enderror">
                            @error('tanggal')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Baris 3: Dokter & Asisten --}}
                    <div class="grid grid-cols-2 gap-x-8">

                        {{-- KOREKSI: Atribut name diubah menjadi id_dokter --}}
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Dokter Gigi
                            </label>

                            <select name="id_dokter"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('id_dokter') ring-2 ring-red-500 @enderror">

                                <option value="" disabled {{ old('id_dokter', $pemeriksaan->id_dokter ?? '') ? '' : 'selected' }}>
                                    Pilih Dokter
                                </option>

                                @foreach ($dokter as $item)
                                    <option value="{{ $item->id }}" {{ old('id_dokter', $pemeriksaan->id_dokter ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach

                            </select>
                            @error('id_dokter')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- KOREKSI: Atribut name diubah menjadi id_asisten --}}
                        <div>
                            <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Asisten Dokter <span class="normal-case font-normal text-gray-400">(opsional)</span>
                            </label>

                            <select name="id_asisten"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm @error('id_asisten') ring-2 ring-red-500 @enderror">

                                <option value="" {{ old('id_asisten', $pemeriksaan->id_asisten ?? '') ? '' : 'selected' }}>
                                    Tidak ada asisten
                                </option>

                                @foreach ($asisten as $item)
                                    <option value="{{ $item->id }}" {{ old('id_asisten', $pemeriksaan->id_asisten ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach

                            </select>
                            @error('id_asisten')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- KOREKSI: Atribut name diubah menjadi catatan --}}
                    <div>
                        <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                            Catatan Klinis & Keluhan
                        </label>

                        <textarea name="catatan" rows="5" placeholder="Tuliskan rincian keluhan, diagnosa awal, atau tindakan medis..."
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm resize-none @error('catatan') ring-2 ring-red-500 @enderror">{{ old('catatan', $pemeriksaan->catatan ?? '') }}</textarea>
                        @error('catatan')
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

                    <button type="submit" class="px-12 py-2.5 bg-[#529e85] hover:bg-[#43846f] text-white rounded-lg text-sm font-semibold transition shadow-sm">
                        <i class="fa-solid fa-save mr-2"></i>
                        {{ isset($pemeriksaan) ? 'Update Pemeriksaan' : 'Simpan Pemeriksaan' }}
                    </button>

                </div>

            </form>

        </div>
    </div>
@endsection
