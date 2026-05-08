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
                action="{{ isset($pemeriksaan)
                    ? route('pemeriksaan.update', $pemeriksaan->id)
                    : route('pemeriksaan.store') }}"
                method="POST">

                @csrf

                {{-- METHOD UNTUK EDIT --}}
                @if (isset($pemeriksaan))
                    @method('PUT')
                @endif

                <div class="space-y-6 mb-12">

                    {{-- Pasien & Tanggal --}}
                    <div class="grid grid-cols-2 gap-x-8">

                        {{-- PASIEN --}}
                        <div>
                            <label
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Pasien
                            </label>

                            <div class="relative">
                                <select name="pasien"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">

                                    <option disabled>Pilih Pasien</option>

                                    @foreach ($pasien as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old('pasien', $pemeriksaan->pasien_id ?? '') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        {{-- TANGGAL --}}
                        <div>
                            <label
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Tanggal Pemeriksaan
                            </label>

                            <input
                                type="date"
                                name="tanggal_pemeriksaan"
                                value="{{ old('tanggal_pemeriksaan', $pemeriksaan->tanggal_pemeriksaan ?? '') }}"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                        </div>

                    </div>

                    {{-- Dokter & Asisten --}}
                    <div class="grid grid-cols-2 gap-x-8">

                        {{-- DOKTER --}}
                        <div>
                            <label
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Dokter Gigi
                            </label>

                            <select name="dokter_gigi"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">

                                <option disabled>Pilih Dokter</option>

                                @foreach ($dokter as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('dokter_gigi', $pemeriksaan->dokter_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{-- ASISTEN --}}
                        <div>
                            <label
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                                Asisten Dokter
                            </label>

                            <select name="asisten_dokter"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">

                                <option value="">Tidak ada asisten</option>

                                @foreach ($asisten as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('asisten_dokter', $pemeriksaan->asisten_id ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>

                    {{-- CATATAN --}}
                    <div>
                        <label
                            class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">
                            Catatan Klinis & Keluhan
                        </label>

                        <textarea
                            name="catatan_klinis"
                            rows="5"
                            placeholder="Tuliskan detail pemeriksaan..."
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 text-sm resize-none">{{ old('catatan_klinis', $pemeriksaan->catatan_klinis ?? '') }}</textarea>
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
