<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asisten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AsistenController extends Controller
{
    public function index()
    {
        $asisten = Asisten::orderBy('id', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $asisten]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'   => 'required|string|max:100',
            'kontak' => 'required|string|max:20'
        ]);

        if ($validator->fails()) return response()->json(['status' => 'error', 'pesan' => $validator->errors()], 422);

        $asisten = Asisten::create([
            'nama'   => $request->nama,
            'kontak' => $request->kontak,
        ]);

        return response()->json(['status' => 'success', 'pesan' => 'Data Asisten berhasil ditambahkan!', 'data' => $asisten], 201);
    }

    public function update(Request $request, $id)
    {
        $asisten = Asisten::find($id);
        if (!$asisten) return response()->json(['status' => 'error', 'pesan' => 'Data tidak ditemukan!'], 404);

        $validator = Validator::make($request->all(), [
            'nama'     => 'required|string|max:100',
            'kontak'   => 'required|string|max:20',
            'is_aktif' => 'required|boolean'
        ]);

        if ($validator->fails()) return response()->json(['status' => 'error', 'pesan' => $validator->errors()], 422);

        $asisten->update([
            'nama'     => $request->nama,
            'kontak'   => $request->kontak,
            'is_aktif' => $request->is_aktif,
        ]);

        return response()->json(['status' => 'success', 'pesan' => 'Data Asisten berhasil diperbarui!', 'data' => $asisten]);
    }

    public function destroy($id)
    {
        $asisten = Asisten::find($id);
        if (!$asisten) return response()->json(['status' => 'error', 'pesan' => 'Data tidak ditemukan!'], 404);

        $asisten->update(['is_aktif' => false]);
        $asisten->delete();

        return response()->json(['status' => 'success', 'pesan' => 'Data asisten dinonaktifkan!']);
    }

    public function semua()
    {
        return response()->json(['status' => 'success', 'data' => Asisten::onlyTrashed()->orderBy('id', 'desc')->get()]);
    }

    public function restore($id)
    {
        $asisten = Asisten::withTrashed()->find($id);
        if (!$asisten) return response()->json(['status' => 'error', 'pesan' => 'Data tidak ditemukan!'], 404);

        $asisten->restore();
        $asisten->update(['is_aktif' => true]);

        return response()->json(['status' => 'success', 'pesan' => 'Data asisten berhasil diaktifkan kembali!']);
    }
}
