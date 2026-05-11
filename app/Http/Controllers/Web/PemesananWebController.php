<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Pemeriksaan;
use App\Models\Laboratorium;
use App\Models\JenisGigi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemesananWebController extends Controller
{
    public function index()
    {
        $data = Pemesanan::with(['pemeriksaan', 'lab', 'jenisGigi'])->get();
        return view('pemesanan.index', compact('data'));
    }

    public function create()
    {
        return view('pemesanan.create', [
            'pemeriksaan' => Pemeriksaan::all(),
            'lab'         => Laboratorium::where('is_aktif', true)->get(),
            'jenis_gigi'  => JenisGigi::where('is_aktif', true)->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pemesanan'     => 'required|unique:pemesanan',
            'tanggal_dikirim'  => 'required',
            'estimasi_selesai' => 'required',
            'biaya_lab'        => 'required',
            'harga_pasien'     => 'required',
            'status_bayar_lab' => 'required',
            'status_pemesanan' => 'required',
            'id_pemeriksaan'   => 'required',
            'id_lab'           => 'required',
            'id_jenis_gigi'    => 'required',
        ]);

        Pemesanan::create($request->all());

        return redirect()->route('pemesanan.index')
            ->with('success', 'Berhasil tambah data');
    }

    public function edit(int $id)
    {
        return view('pemesanan.update', [
            'data'        => Pemesanan::findOrFail($id),
            'pemeriksaan' => Pemeriksaan::all(),
            'lab'         => Laboratorium::where('is_aktif', true)->get(),
            'jenis_gigi'  => JenisGigi::where('is_aktif', true)->get()
        ]);
    }

    public function update(Request $request, int $id)
    {
        $data = Pemesanan::findOrFail($id);

        $request->validate([
            'no_pemesanan'     => 'required|unique:pemesanan,no_pemesanan,' . $id,
            'tanggal_dikirim'  => 'required',
            'estimasi_selesai' => 'required',
            'biaya_lab'        => 'required',
            'harga_pasien'     => 'required',
        ]);

        $data->update($request->all());

        return redirect()->route('pemesanan.index')
            ->with('success', 'Berhasil update');
    }

    public function destroy(int $id)
    {
        if (Auth::user()->role !== 'direktur') {
            return redirect()->route('pemesanan.index')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak menghapus data.');
        }

        Pemesanan::findOrFail($id)->delete();

        return redirect()->route('pemesanan.index')
            ->with('success', 'Berhasil hapus');
    }
}
