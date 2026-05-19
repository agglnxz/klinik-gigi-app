<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::with('pemesanan')
            ->orderByDesc('created_at')
            ->get();

        return view('notifikasi', compact('notifikasi'));
    }
}
