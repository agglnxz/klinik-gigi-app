<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokter = Dokter::orderBy('id', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $dokter]);
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

        // Eksekusi Aman
        $dokter = Dokter::create([
            'nama'   => $request->nama,
            'kontak' => $request->kontak,
        ]);

        return response()->json(['status' => 'success', 'pesan' => 'Data Dokter berhasil ditambahkan!', 'data' => $dokter], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Dokter $dokter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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

        // Eksekusi Aman
        $dokter->update([
            'nama'     => $request->nama,
            'kontak'   => $request->kontak,
            'is_aktif' => $request->is_aktif,
        ]);

        return response()->json(['status' => 'success', 'pesan' => 'Data Dokter berhasil diperbarui!', 'data' => $dokter]);
    }
    
}
