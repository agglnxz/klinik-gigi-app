<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiWebController extends Controller
{
        public function index()
    {
        $notifikasi = Notifikasi::with('pemesanan')
            ->orderByDesc('created_at')
            ->get();

        return view('notifikasi', compact('notifikasi'));
    }
}
