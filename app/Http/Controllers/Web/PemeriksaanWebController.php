<?php

// namespace App\Http\Controllers\Web;

// use App\Http\Controllers\Controller;
// use App\Models\Pemeriksaan;
// use App\Models\Pasien;
// use App\Models\Dokter;
// use App\Models\Asisten;
// use Illuminate\Http\Request;

// class PemeriksaanWebController extends Controller
// {
//     public function index()
//     {
//         // Eager loading untuk menghindari N+1 Query Problem
//         $pemeriksaan = Pemeriksaan::with(['pasien', 'dokter', 'asisten'])->orderBy('id', 'desc')->get();
//         return view('pemeriksaan.index', compact('pemeriksaan'));
//     }

//     public function create()
//     {
//         // Hanya ambil data yang aktif untuk form input
//         $pasien = Pasien::where('is_aktif', true)->get();
//         $dokter = Dokter::where('is_aktif', true)->get();
//         $asisten = Asisten::where('is_aktif', true)->get();
//         return view('pemeriksaan.create', compact('pasien', 'dokter', 'asisten'));
//     }

//     public function store(Request $request)
//     {
//         // $request->validate([
//         //     'no_pemeriksaan' => 'required|string|unique:pemeriksaan,no_pemeriksaan',
//         //     'tanggal'        => 'required|date',
//         //     'catatan'        => 'required|string',
//         //     'id_pasien'      => 'required|exists:pasien,id',
//         //     'id_dokter'      => 'required|exists:dokter,id',
//         //     'id_asisten'     => 'required|exists:asisten,id',
//         // ]);

//                     $request->validate([
//                 'no_pemeriksaan'      => 'required|string|unique:pemeriksaan,no_pemeriksaan,' . $id,
//                 'tanggal_pemeriksaan' => 'required|date',
//                 'catatan_klinis'      => 'required|string',
//                 'pasien'              => 'required|exists:pasien,id',
//                 'dokter_gigi'         => 'required|exists:dokter,id',
//                 'asisten_dokter'      => 'nullable|exists:asisten,id',
//             ]);

//         Pemeriksaan::create($request->all());

//         return redirect()->route('pemeriksaan.index')->with('success', 'Data Pemeriksaan berhasil disimpan!');
//     }

//     public function edit($id)
//     {
//         $pemeriksaan = Pemeriksaan::findOrFail($id);
//         $pasien = Pasien::where('is_aktif', true)->get();
//         $dokter = Dokter::where('is_aktif', true)->get();
//         $asisten = Asisten::where('is_aktif', true)->get();
//         return view('pemeriksaan.edit', compact('pemeriksaan', 'pasien', 'dokter', 'asisten'));
//     }

//     public function update(Request $request, $id)
//     {
//         $pemeriksaan = Pemeriksaan::findOrFail($id);

//         // $request->validate([
//         //     'no_pemeriksaan' => 'required|string|unique:pemeriksaan,no_pemeriksaan,'.$id,
//         //     'tanggal'        => 'required|date',
//         //     'catatan'        => 'required|string',
//         //     'id_pasien'      => 'required|exists:pasien,id',
//         //     'id_dokter'      => 'required|exists:dokter,id',
//         //     'id_asisten'     => 'required|exists:asisten,id',
//         // ]);
//         $pemeriksaan->update([
//     'no_pemeriksaan' => $request->no_pemeriksaan,
//     'tanggal'        => $request->tanggal_pemeriksaan,
//     'catatan'        => $request->catatan_klinis,
//     'id_pasien'      => $request->pasien,
//     'id_dokter'      => $request->dokter_gigi,
//     'id_asisten'     => $request->asisten_dokter,
// ]);

//         // $pemeriksaan->update($request->all());

//         return redirect()->route('pemeriksaan.index')->with('success', 'Data Pemeriksaan berhasil diperbarui!');
//     }

//     public function destroy($id)
//     {
//         $pemeriksaan = Pemeriksaan::findOrFail($id);
//         $pemeriksaan->delete();
//         return redirect()->route('pemeriksaan.index')->with('success', 'Data Pemeriksaan berhasil dihapus!');
//     }

//         public function semua()
//     {
//         $data = Pemeriksaan::onlyTrashed()->get();
//         return view('pemeriksaan.semua', compact('data'));
//     }

//     public function restore($id)
//     {
//         Pemeriksaan::withTrashed()->findOrFail($id)->restore();

//         return redirect()->route('pemeriksaan.semua')
//             ->with('success', 'Berhasil restore');
//     }
// }


namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemeriksaan;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Asisten;
use Illuminate\Http\Request;

class PemeriksaanWebController extends Controller
{
    public function index()
    {
        // Eager loading untuk menghindari N+1 Query Problem
        $pemeriksaan = Pemeriksaan::with(['pasien', 'dokter', 'asisten'])
            ->orderBy('id', 'desc')
            ->get();

        return view('pemeriksaan.index', compact('pemeriksaan'));
    }

    public function create()
    {
        // Hanya ambil data yang aktif untuk form input
        $pasien = Pasien::where('is_aktif', true)->get();
        $dokter = Dokter::where('is_aktif', true)->get();
        $asisten = Asisten::where('is_aktif', true)->get();

        return view('pemeriksaan.create', compact('pasien', 'dokter', 'asisten'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pemeriksaan'      => 'required|string|unique:pemeriksaan,no_pemeriksaan',
            'tanggal_pemeriksaan' => 'required|date',
            'catatan_klinis'      => 'required|string',
            'pasien'              => 'required|exists:pasien,id',
            'dokter_gigi'         => 'required|exists:dokter,id',
            'asisten_dokter'      => 'nullable|exists:asisten,id',
        ]);

        Pemeriksaan::create([
            'no_pemeriksaan' => $request->no_pemeriksaan,
            'tanggal'        => $request->tanggal_pemeriksaan,
            'catatan'        => $request->catatan_klinis,
            'id_pasien'      => $request->pasien,
            'id_dokter'      => $request->dokter_gigi,
            'id_asisten'     => $request->asisten_dokter,
        ]);

        return redirect()->route('pemeriksaan.index')
            ->with('success', 'Data Pemeriksaan berhasil disimpan!');
    }

    public function edit($id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $pasien = Pasien::where('is_aktif', true)->get();
        $dokter = Dokter::where('is_aktif', true)->get();
        $asisten = Asisten::where('is_aktif', true)->get();

        // FILE VIEW KAMU = edit.blade.php
        return view('pemeriksaan.edit', compact(
            'pemeriksaan',
            'pasien',
            'dokter',
            'asisten'
        ));
    }

    public function update(Request $request, $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $request->validate([
            'no_pemeriksaan'      => 'required|string|unique:pemeriksaan,no_pemeriksaan,' . $id,
            'tanggal_pemeriksaan' => 'required|date',
            'catatan_klinis'      => 'required|string',
            'pasien'              => 'required|exists:pasien,id',
            'dokter_gigi'         => 'required|exists:dokter,id',
            'asisten_dokter'      => 'nullable|exists:asisten,id',
        ]);

        $pemeriksaan->update([
            'no_pemeriksaan' => $request->no_pemeriksaan,
            'tanggal'        => $request->tanggal_pemeriksaan,
            'catatan'        => $request->catatan_klinis,
            'id_pasien'      => $request->pasien,
            'id_dokter'      => $request->dokter_gigi,
            'id_asisten'     => $request->asisten_dokter,
        ]);

        return redirect()->route('pemeriksaan.index')
            ->with('success', 'Data Pemeriksaan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $pemeriksaan->delete();

        return redirect()->route('pemeriksaan.index')
            ->with('success', 'Data Pemeriksaan berhasil dihapus!');
    }

    public function semua()
    {
        $data = Pemeriksaan::onlyTrashed()->get();

        return view('pemeriksaan.semua', compact('data'));
    }

    public function restore($id)
    {
        Pemeriksaan::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('pemeriksaan.semua')
            ->with('success', 'Berhasil restore');
    }
}
