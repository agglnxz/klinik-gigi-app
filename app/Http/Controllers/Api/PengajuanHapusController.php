<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PengajuanHapus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PengajuanHapusController extends Controller
{
    public function index()
    {
        $data = PengajuanHapus::with('pemohon')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $tabelDiizinkan = [
            'pasien',
            'pemesanan',
            'pemeriksaan',
            'dokter',
            'asisten',
            'laboratorium',
            'jenis_gigi'
        ];

        $validator = Validator::make($request->all(), [
            'nama_tabel'   => 'required|in:' . implode(',', $tabelDiizinkan),
            'id_referensi' => 'required|integer',
            'nama_data'    => 'required|string',
            'alasan_hapus' => 'required|string|min:5',
            'id_pemohon'   => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'pesan' => $validator->errors()
            ], 422);
        }

        $dataValid = DB::table($request->nama_tabel)
            ->where('id', $request->id_referensi)
            ->exists();

        if (!$dataValid) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data referensi tidak ditemukan'
            ], 404);
        }

        $data = PengajuanHapus::create([
            'nama_tabel'      => $request->nama_tabel,
            'id_referensi'    => $request->id_referensi,
            'nama_data'       => $request->nama_data,
            'alasan_hapus'    => $request->alasan_hapus,
            'status_approval' => 'Pending',
            'id_pemohon'      => $request->id_pemohon,
        ]);

        return response()->json([
            'status' => 'success',
            'pesan' => 'Pengajuan berhasil dibuat',
            'data' => $data
        ], 201);
    }

    public function approve($id)
    {
        $pengajuan = PengajuanHapus::find($id);

        if (!$pengajuan) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data tidak ditemukan'
            ], 404);
        }

        if ($pengajuan->status_approval !== 'Pending') {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Pengajuan sudah diproses'
            ], 400);
        }

        try {
            DB::table($pengajuan->nama_tabel)
                ->where('id', $pengajuan->id_referensi)
                ->delete();

            $pengajuan->update([
                'status_approval' => 'Disetujui'
            ]);

            return response()->json([
                'status' => 'success',
                'pesan' => 'Pengajuan disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data memiliki relasi sehingga gagal dihapus'
            ], 500);
        }
    }

    public function reject($id)
    {
        $pengajuan = PengajuanHapus::find($id);

        if (!$pengajuan) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Data tidak ditemukan'
            ], 404);
        }

        $pengajuan->update([
            'status_approval' => 'Ditolak'
        ]);

        return response()->json([
            'status' => 'success',
            'pesan' => 'Pengajuan ditolak'
        ]);
    }
}