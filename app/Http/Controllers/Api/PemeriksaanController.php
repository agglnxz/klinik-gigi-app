<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;

class PemeriksaanController extends Controller
{
    public function index()
    {
        $pemeriksaan = Pemeriksaan::with(['pasien', 'dokter', 'asisten'])->orderBy('id', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $pemeriksaan]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pemeriksaan' => 'required|string|unique:pemeriksaan,no_pemeriksaan',
            'tanggal'        => 'required|date',
            'catatan'        => 'required|string',
            'id_pasien'      => 'required|exists:pasien,id',
            'id_dokter'      => 'required|exists:dokter,id',
            'id_asisten'     => 'required|exists:asisten,id',
        ]);

        $pemeriksaan = Pemeriksaan::create($request->all());
        $pemeriksaan->load(['pasien', 'dokter', 'asisten']);

        return response()->json(['status' => 'success', 'data' => $pemeriksaan], 201);
    }

    public function show($id)
    {
        $pemeriksaan = Pemeriksaan::with(['pasien', 'dokter', 'asisten'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $pemeriksaan]);
    }

    public function update(Request $request, $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $request->validate([
            'no_pemeriksaan' => 'required|string|unique:pemeriksaan,no_pemeriksaan,'.$id,
            'tanggal'        => 'required|date',
            'catatan'        => 'required|string',
            'id_pasien'      => 'required|exists:pasien,id',
            'id_dokter'      => 'required|exists:dokter,id',
            'id_asisten'     => 'required|exists:asisten,id',
        ]);

        $pemeriksaan->update($request->all());
        $pemeriksaan->load(['pasien', 'dokter', 'asisten']);

        return response()->json(['status' => 'success', 'data' => $pemeriksaan]);
    }

    public function destroy($id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->delete();

        return response()->json(['status' => 'success', 'pesan' => 'Data Pemeriksaan berhasil dihapus!']);
    }

    public function semua()
    {
        return response()->json(['status' => 'success', 'data' => Pemeriksaan::onlyTrashed()->get()]);
    }

    public function restore($id)
    {
        $data = Pemeriksaan::withTrashed()->find($id);
        if (!$data) return response()->json(['status' => 'error', 'pesan' => 'Data tidak ditemukan'], 404);

        $data->restore();
        return response()->json(['status' => 'success', 'pesan' => 'Data berhasil direstore']);
    }
}
