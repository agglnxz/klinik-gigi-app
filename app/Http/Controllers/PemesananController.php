<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PemesananController extends Controller
{
    // GET: list + search
    public function index(Request $request)
    {
        $query = Pemesanan::with(['pemeriksaan', 'lab', 'jenisGigi'])
                    ->orderBy('id', 'desc');

        if ($request->search) {
            $query->where('no_pemesanan', 'like', '%' . $request->search . '%');
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get()
        ]);
    }

    // POST: tambah
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_pemesanan'      => 'required|unique:pemesanan,no_pemesanan',
            'tanggal_dikirim'   => 'required|date',
            'estimasi_selesai'  => 'required|date',
            'biaya_lab'         => 'required|numeric',
            'harga_pasien'      => 'required|numeric',
            'status_bayar_lab'  => 'required|in:Belum Lunas,Sudah Lunas',
            'status_pemesanan'  => 'required|in:Proses Lab,Telah Tiba,Sudah Dipasang',
            'id_pemeriksaan'    => 'required|exists:pemeriksaan,id',
            'id_lab'            => 'required|exists:laboratorium,id',
            'id_jenis_gigi'     => 'required|exists:jenis_gigi,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan' => $validator->errors()
            ], 422);
        }

        $data = Pemesanan::create($request->all());

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data pemesanan berhasil ditambahkan!',
            'data' => $data
        ], 201);
    }

    // PUT: update
    public function update(Request $request, $id)
    {
        $data = Pemesanan::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'no_pemesanan'      => 'required|unique:pemesanan,no_pemesanan,' . $id,
            'tanggal_dikirim'   => 'required|date',
            'estimasi_selesai'  => 'required|date',
            'biaya_lab'         => 'required|numeric',
            'harga_pasien'      => 'required|numeric',
            'status_bayar_lab'  => 'required|in:Belum Lunas,Sudah Lunas',
            'status_pemesanan'  => 'required|in:Proses Lab,Telah Tiba,Sudah Dipasang',
            'id_pemeriksaan'    => 'required|exists:pemeriksaan,id',
            'id_lab'            => 'required|exists:laboratorium,id',
            'id_jenis_gigi'     => 'required|exists:jenis_gigi,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan' => $validator->errors()
            ], 422);
        }

        $data->update($request->all());

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data berhasil diupdate',
            'data' => $data
        ]);
    }

    // DELETE (soft delete)
    public function destroy($id)
    {
        $data = Pemesanan::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data berhasil dihapus'
        ]);
    }

    // GET: semua (trash)
    public function semua()
    {
        return response()->json([
            'status' => 'success',
            'data' => Pemesanan::onlyTrashed()->get()
        ]);
    }

    // RESTORE
    public function restore($id)
    {
        $data = Pemesanan::withTrashed()->find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->restore();

        return response()->json([
            'status' => 'success',
            'pesan' => 'Data berhasil direstore'
        ]);
    }
}