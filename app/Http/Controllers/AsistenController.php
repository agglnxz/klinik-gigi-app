<?php

namespace App\Http\Controllers;

use App\Models\Asisten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AsistenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asisten = Asisten::orderBy('id', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $asisten]);
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Asisten $asisten)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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

     /**
     * DELETE: nonaktifkan + soft delete
     */
    public function destroy($id)
    {
        $asisten = Asisten::find($id);
        if (!$asisten) { 
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data tidak ditemukan!'
            ], 404);
        }

        $asisten->update(['is_aktif' => false]);
        $asisten->delete();

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data asiste$asisten dinonaktifkan!'
        ]);
    }

    /**
     * GET: semua data yang dihapus
     */
    public function semua()
    {
        $asisten = Asisten::onlyTrashed()->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $asisten
        ]);
    }

    /**
     * PUT: restore asiste$asisten
     */
    public function restore($id)
    {
        $asisten = Asisten::withTrashed()->find($id);
        if (!$asisten) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data tidak ditemukan!'
            ], 404);
        }

        $asisten->restore();
        $asisten->update(['is_aktif' => true]);

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data asiste$asisten berhasil diaktifkan kembali!'
        ]);
    }
}
