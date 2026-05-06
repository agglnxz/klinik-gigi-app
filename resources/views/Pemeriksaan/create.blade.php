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
            <form action="#" method="POST">
                @csrf
                <div class="space-y-6 mb-12">

                    {{-- Row: Pasien | Tanggal Pemeriksaan --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label for="pasien"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Pasien</label>
                            <div class="relative">
                                <select name="pasien" id="pasien"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-400 appearance-none cursor-pointer transition">
                                    <option value="" disabled selected>Cari Pasien</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="tanggal_pemeriksaan"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Tanggal
                                Pemeriksaan</label>
                            <input type="date" name="tanggal_pemeriksaan" id="tanggal_pemeriksaan"
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 transition">
                        </div>
                    </div>

                    {{-- Row: Dokter Gigi | Asisten Dokter --}}
                    <div class="grid grid-cols-2 gap-x-8">
                        <div>
                            <label for="dokter_gigi"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Dokter
                                Gigi</label>
                            <div class="relative">
                                <select name="dokter_gigi" id="dokter_gigi"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-400 appearance-none cursor-pointer transition">
                                    <option value="" disabled selected>Pilih Dokter</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="asisten_dokter"
                                class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Asisten
                                Dokter <span class="normal-case font-normal text-gray-400">(opsional)</span></label>
                            <div class="relative">
                                <select name="asisten_dokter" id="asisten_dokter"
                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-400 appearance-none cursor-pointer transition">
                                    <option value="" selected>Tidak ada asisten</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Catatan Klinis & Keluhan (full width) --}}
                    <div>
                        <label for="catatan_klinis"
                            class="block text-[11px] font-bold text-gray-800 uppercase tracking-widest mb-2">Catatan Klinis
                            & Keluhan</label>
                        <textarea name="catatan_klinis" id="catatan_klinis" rows="5"
                            placeholder="Tuliskan detail pemeriksaan, keluhan pasien, atau diagnosa awal di sini..."
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-lg focus:ring-2 focus:ring-teal-500 focus:shadow-md text-sm text-gray-600 placeholder-gray-400 transition resize-none"></textarea>
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
