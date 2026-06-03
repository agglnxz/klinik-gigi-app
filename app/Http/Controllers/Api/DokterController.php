<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DokterController extends Controller
{
    public function index()
    {
        $dokter = Dokter::orderBy('id', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $dokter]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'   => 'required|string|max:100',
            'kontak' => 'required|string|max:20'
        ]);

        if ($validator->fails()) return response()->json(['status' => 'error', 'pesan' => $validator->errors()], 422);

        $dokter = Dokter::create([
            'nama'   => $request->nama,
            'kontak' => $request->kontak,
        ]);

        return response()->json(['status' => 'success', 'pesan' => 'Data Dokter berhasil ditambahkan!', 'data' => $dokter], 201);
    }

    public function update(Request $request, $id)
    {
        $dokter = Dokter::find($id);
        if (!$dokter) return response()->json(['status' => 'error', 'pesan' => 'Data tidak ditemukan!'], 404);

        $validator = Validator::make($request->all(), [
            'nama'     => 'required|string|max:100',
            'kontak'   => 'required|string|max:20',
            'is_aktif' => 'required|boolean'
        ]);

        if ($validator->fails()) return response()->json(['status' => 'error', 'pesan' => $validator->errors()], 422);

        $dokter->update([
            'nama'     => $request->nama,
            'kontak'   => $request->kontak,
            'is_aktif' => $request->is_aktif,
        ]);

        return response()->json(['status' => 'success', 'pesan' => 'Data Dokter berhasil diperbarui!', 'data' => $dokter]);
    }

    public function destroy($id)
    {
        $dokter = Dokter::find($id);
        if (!$dokter) return response()->json(['status' => 'error', 'pesan' => 'Data tidak ditemukan!'], 404);

        $dokter->update(['is_aktif' => false]);
        $dokter->delete();

        return response()->json(['status' => 'success', 'pesan' => 'Data dokter dinonaktifkan!']);
    }

    public function semua()
    {
        return response()->json(['status' => 'success', 'data' => Dokter::onlyTrashed()->orderBy('id', 'desc')->get()]);
    }

    public function restore($id)
    {
        $dokter = Dokter::withTrashed()->find($id);
        if (!$dokter) return response()->json(['status' => 'error', 'pesan' => 'Data tidak ditemukan!'], 404);

        $dokter->restore();
        $dokter->update(['is_aktif' => true]);

        return response()->json(['status' => 'success', 'pesan' => 'Data dokter berhasil diaktifkan kembali!']);
    }
}
