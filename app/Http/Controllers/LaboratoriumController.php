<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaboratoriumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lab = Laboratorium::orderBy('id', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'data' => $lab
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi inputan admin
        $validator = Validator::make($request->all(), [
            'nama_lab' => 'required|string|max:100',
            'kontak'   => 'required|string|max:20',
            'alamat'   => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'pesan' => $validator->errors()], 422);
        }

        // Simpan ke database
        $lab = Laboratorium::create([
            'nama_lab' => $request->nama_lab,
            'kontak'   => $request->kontak,
            'alamat'   => $request->alamat,
        ]);

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data Laboratorium berhasil ditambahkan!',
            'data'   => $lab
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Laboratorium $laboratorium)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
// 3. Fungsi UBAH DATA (Update)
    public function update(Request $request, $id)
    {
        $lab = Laboratorium::find($id);

        if (!$lab) {
            return response()->json(['status' => 'error', 'pesan' => 'Data tidak ditemukan!'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_lab' => 'required|string|max:100',
            'kontak'   => 'required|string|max:20',
            'alamat'   => 'nullable|string',
            'is_aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'pesan' => $validator->errors()], 422);
        }

        $lab->update([
            'nama_lab' => $request->nama_lab,
            'kontak'   => $request->kontak,
            'alamat'   => $request->alamat,
            'is_aktif' => $request->is_aktif,
        ]);

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Data Laboratorium berhasil diperbarui!',
            'data'   => $lab
        ]);
    }


}
