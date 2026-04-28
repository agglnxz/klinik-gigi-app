<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    /**
     * GET: list pasien aktif + search
     */
    public function index(Request $request)
    {
        $query = Pasien::where('is_aktif', true)->orderBy('id', 'desc');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('no_rm', 'like', '%' . $request->search . '%');
            });
        }

        $pasien = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $pasien
        ]);
    }

    /**
     * POST: tambah pasien
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_rm'          => 'required|string|max:20|unique:pasien,no_rm',
            'nama'           => 'required|string|max:150',
            'kontak'         => 'required|string|max:20',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'alamat'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan' => $validator->errors()
            ], 422);
        }

        $pasien = Pasien::create([
            'no_rm'         => $request->no_rm,
            'nama'          => $request->nama,
            'kontak'        => $request->kontak,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat'        => $request->alamat,
            'is_aktif'      => true
        ]);

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data pasien berhasil ditambahkan!',
            'data' => $pasien
        ], 201);
    }

    /**
     * PUT: update pasien
     */
    public function update(Request $request, $id)
    {
        $pasien = Pasien::find($id);
        if (!$pasien) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data tidak ditemukan!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'no_rm'          => 'required|string|max:20|unique:pasien,no_rm,' . $id,
            'nama'           => 'required|string|max:150',
            'kontak'         => 'required|string|max:20',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'alamat'         => 'nullable|string',
            'is_aktif'       => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan' => $validator->errors()
            ], 422);
        }

        $pasien->update([
            'no_rm'         => $request->no_rm,
            'nama'          => $request->nama,
            'kontak'        => $request->kontak,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat'        => $request->alamat,
            'is_aktif'      => $request->is_aktif
        ]);

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data pasien berhasil diperbarui!',
            'data' => $pasien
        ]);
    }

    /**
     * DELETE: nonaktifkan + soft delete
     */
    public function destroy($id)
    {
        $pasien = Pasien::find($id);
        if (!$pasien) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data tidak ditemukan!'
            ], 404);
        }

        $pasien->update(['is_aktif' => false]);
        $pasien->delete();

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data pasien dinonaktifkan!'
        ]);
    }

    /**
     * GET: semua data (termasuk terhapus)
     */
    public function semua()
    {
        $pasien = Pasien::onlyTrashed()->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $pasien
        ]);
    }

    /**
     * PUT: restore pasien
     */
    public function restore($id)
    {
        $pasien = Pasien::withTrashed()->find($id);
        if (!$pasien) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data tidak ditemukan!'
            ], 404);
        }

        $pasien->restore();
        $pasien->update(['is_aktif' => true]);

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data pasien berhasil diaktifkan kembali!'
        ]);
    }
}