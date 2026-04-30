<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Pemeriksaan;
use App\Models\Laboratorium;
use App\Models\JenisGigi;
use Illuminate\Http\Request;

class PemesananWebController extends Controller
{
    public function index()
    {
        $data = Pemesanan::with(['pemeriksaan','lab','jenisGigi'])->get();
        return view('pemesanan.index', compact('data'));
    }

    public function create()
    {
        return view('pemesanan.create', [
            'pemeriksaan' => Pemeriksaan::all(),
            'lab' => Laboratorium::all(),
            'jenis_gigi' => JenisGigi::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pemesanan' => 'required|unique:pemesanan',
            'tanggal_dikirim' => 'required',
            'estimasi_selesai' => 'required',
            'biaya_lab' => 'required',
            'harga_pasien' => 'required',
            'status_bayar_lab' => 'required',
            'status_pemesanan' => 'required',
            'id_pemeriksaan' => 'required',
            'id_lab' => 'required',
            'id_jenis_gigi' => 'required',
        ]);

        Pemesanan::create($request->all());

        return redirect()->route('pemesanan.index')
            ->with('success', 'Berhasil tambah data');
    }

    public function edit($id)
    {
        return view('pemesanan.edit', [
            'data' => Pemesanan::findOrFail($id),
            'pemeriksaan' => Pemeriksaan::all(),
            'lab' => Laboratorium::all(),
            'jenis_gigi' => JenisGigi::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = Pemesanan::findOrFail($id);

        $request->validate([
            'no_pemesanan' => 'required|unique:pemesanan,no_pemesanan,'.$id,
            'tanggal_dikirim' => 'required',
            'estimasi_selesai' => 'required',
            'biaya_lab' => 'required',
            'harga_pasien' => 'required',
        ]);

        $data->update($request->all());

        return redirect()->route('pemesanan.index')
            ->with('success', 'Berhasil update');
    }

    public function destroy($id)
    {
        Pemesanan::findOrFail($id)->delete();

        return redirect()->route('pemesanan.index')
            ->with('success', 'Berhasil hapus');
    }

    public function semua()
    {
        $data = Pemesanan::onlyTrashed()->get();
        return view('pemesanan.semua', compact('data'));
    }

    public function restore($id)
    {
        Pemesanan::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('pemesanan.semua')
            ->with('success', 'Berhasil restore');
    }
}