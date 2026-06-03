<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaboratoriumController extends Controller
{
    public function index()
    {
        $lab = Laboratorium::orderBy('id', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $lab]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lab' => 'required|string|max:100',
            'kontak'   => 'required|string|max:20',
            'alamat'   => 'nullable|string'
        ]);

        if ($validator->fails()) return response()->json(['status' => 'error', 'pesan' => $validator->errors()], 422);

        $lab = Laboratorium::create([
            'nama_lab' => $request->nama_lab,
            'kontak'   => $request->kontak,
            'alamat'   => $request->alamat,
        ]);

        return response()->json(['status' => 'success', 'pesan' => 'Data Laboratorium berhasil ditambahkan!', 'data' => $lab], 201);
    }

    public function update(Request $request, $id)
    {
        $lab = Laboratorium::find($id);
        if (!$lab) return response()->json(['status' => 'error', 'pesan' => 'Data tidak ditemukan!'], 404);

        $validator = Validator::make($request->all(), [
            'nama_lab' => 'required|string|max:100',
            'kontak'   => 'required|string|max:20',
            'alamat'   => 'nullable|string',
            'is_aktif' => 'boolean'
        ]);

        if ($validator->fails()) return response()->json(['status' => 'error', 'pesan' => $validator->errors()], 422);

        $lab->update([
            'nama_lab' => $request->nama_lab,
            'kontak'   => $request->kontak,
            'alamat'   => $request->alamat,
            'is_aktif' => $request->is_aktif,
        ]);

        return response()->json(['status' => 'success', 'pesan' => 'Data Laboratorium berhasil diperbarui!', 'data' => $lab]);
    }

    public function destroy($id)
    {
        $laboratorium = Laboratorium::find($id);
        if (!$laboratorium) return response()->json(['status' => 'error', 'pesan' => 'Data tidak ditemukan!'], 404);

        $laboratorium->update(['is_aktif' => false]);
        $laboratorium->delete();

        return response()->json(['status' => 'success', 'pesan' => 'Data laboratorium dinonaktifkan!']);
    }

    public function semua()
    {
        return response()->json(['status' => 'success', 'data' => Laboratorium::onlyTrashed()->orderBy('id', 'desc')->get()]);
    }

    public function restore($id)
    {
        $laboratorium = Laboratorium::withTrashed()->find($id);
        if (!$laboratorium) return response()->json(['status' => 'error', 'pesan' => 'Data tidak ditemukan!'], 404);

        $laboratorium->restore();
        $laboratorium->update(['is_aktif' => true]);

        return response()->json(['status' => 'success', 'pesan' => 'Data laboratorium berhasil diaktifkan kembali!']);
    }
}
