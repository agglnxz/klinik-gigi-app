<?php

namespace App\Http\Controllers;

use App\Models\JenisGigi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisGigiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenis_gigi = JenisGigi::orderBy('id', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $jenis_gigi]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Pengecekan agar kode gigi tidak boleh kembar saat input baru
            'kode_gigi'      => 'required|string|max:20|unique:jenis_gigi,kode_gigi',
            'nama_jenis'     => 'required|string|max:100',
            'estimasi_biaya' => 'required|numeric'
        ]);

        if ($validator->fails()) return response()->json(['status' => 'error', 'pesan' => $validator->errors()], 422);

        $jenis_gigi = JenisGigi::create([
            'kode_gigi'      => $request->kode_gigi,
            'nama_jenis'     => $request->nama_jenis,
            'estimasi_biaya' => $request->estimasi_biaya,
        ]);

        return response()->json(['status' => 'success', 'pesan' => 'Jenis Gigi berhasil ditambahkan!', 'data' => $jenis_gigi], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisGigi $jenisGigi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $id)
    {
        $jenis_gigi = JenisGigi::find($id);
        if (!$jenis_gigi) return response()->json(['status' => 'error', 'pesan' => 'Data tidak ditemukan!'], 404);

        $validator = Validator::make($request->all(), [
            // Pengecekan unik tapi mengecualikan ID miliknya sendiri agar bisa di-save ulang
            'kode_gigi'      => 'required|string|max:20|unique:jenis_gigi,kode_gigi,' . $id,
            'nama_jenis'     => 'required|string|max:100',
            'estimasi_biaya' => 'required|numeric'
        ]);

        if ($validator->fails()) return response()->json(['status' => 'error', 'pesan' => $validator->errors()], 422);

        $jenis_gigi->update([
            'kode_gigi'      => $request->kode_gigi,
            'nama_jenis'     => $request->nama_jenis,
            'estimasi_biaya' => $request->estimasi_biaya,
        ]);

        return response()->json(['status' => 'success', 'pesan' => 'Jenis Gigi berhasil diperbarui!', 'data' => $jenis_gigi]);
    }

     /**
     * DELETE: nonaktifkan + soft delete
     */
    public function destroy($id)
    {
        $jenis_gigi = JenisGigi::find($id);
        if (!$jenis_gigi) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data tidak ditemukan!'
            ], 404);
        }

        $jenis_gigi->update(['is_aktif' => false]);
        $jenis_gigi->delete();

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data jenis_gigi dinonaktifkan!'
        ]);
    }

    /**
     * GET: semua data yang dihapus
     */
    public function semua()
    {
        $jenis_gigi = JenisGigi::onlyTrashed()->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $jenis_gigi
        ]);
    }

    /**
     * PUT: restore jenis_gigi
     */
    public function restore($id)
    {
        $jenis_gigi = JenisGigi::withTrashed()->find($id);
        if (!$jenis_gigi) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data tidak ditemukan!'
            ], 404);
        }

        $jenis_gigi->restore();
        $jenis_gigi->update(['is_aktif' => true]);

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data jenis_gigi berhasil diaktifkan kembali!'
        ]);
    }
}
