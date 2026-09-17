<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $data->no_pemesanan }} - Klinik Winardi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ==========================================================
           HALAMAN INI SENGAJA TIDAK MEMANGGIL @extends('layouts.main')
           agar terisolasi total dari sidebar, navbar, dan elemen
           navigasi lain — cocok untuk dicetak langsung atau disimpan
           sebagai PDF.
           ========================================================== */
        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background: #eef1f0;
            color: #1f2937;
            margin: 0;
            padding: 32px 16px;
        }

        .lembar-invoice {
            width: 210mm;         /* Ukuran A4 */
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 16mm 14mm;
            border-radius: 8px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }

        .header-klinik {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #176851;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .header-klinik .identitas {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .header-klinik img.logo {
            height: 56px;
            width: 56px;
            object-fit: contain;
        }

        .header-klinik h1 {
            font-size: 20px;
            font-weight: 900;
            color: #176851;
            margin: 0;
            letter-spacing: -0.3px;
        }

        .header-klinik p {
            font-size: 11px;
            color: #6b7280;
            margin: 2px 0 0;
            line-height: 1.4;
        }

        .header-klinik .label-invoice {
            text-align: right;
        }

        .header-klinik .label-invoice h2 {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 2px;
            color: #111827;
            margin: 0;
        }

        .header-klinik .label-invoice p {
            font-size: 11px;
            color: #6b7280;
            margin: 2px 0 0;
        }

        .grid-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 22px;
        }

        .kotak-info {
            background: #f8faf9;
            border: 1px solid #eef1f0;
            border-radius: 10px;
            padding: 14px 16px;
        }

        .kotak-info .judul {
            font-size: 9.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #9ca3af;
            margin: 0 0 8px;
        }

        .baris-info {
            display: flex;
            justify-content: space-between;
            font-size: 11.5px;
            padding: 3px 0;
        }

        .baris-info .k { color: #6b7280; }
        .baris-info .v { font-weight: 700; color: #1f2937; text-align: right; }

        table.tabel-item {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11.5px;
        }

        table.tabel-item thead th {
            background: #176851;
            color: #ffffff;
            text-align: left;
            padding: 9px 10px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        table.tabel-item thead th.text-right { text-align: right; }
        table.tabel-item thead th.text-center { text-align: center; }

        table.tabel-item tbody td {
            padding: 9px 10px;
            border-bottom: 1px solid #f0f1f0;
            vertical-align: top;
        }

        table.tabel-item tbody td.text-right { text-align: right; }
        table.tabel-item tbody td.text-center { text-align: center; }

        table.tabel-item tbody tr:last-child td { border-bottom: none; }

        .ringkasan-bayar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 26px;
        }

        .kotak-ringkasan {
            width: 300px;
        }

        .kotak-ringkasan .baris {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            padding: 6px 0;
            color: #4b5563;
        }

        .kotak-ringkasan .baris.total {
            border-top: 2px solid #176851;
            margin-top: 6px;
            padding-top: 10px;
            font-size: 15px;
            font-weight: 900;
            color: #176851;
        }

        .badge-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .badge-lunas   { background: #dcfce7; color: #15803d; }
        .badge-belum   { background: #fee2e2; color: #b91c1c; }

        .footer-invoice {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 40px;
            padding-top: 16px;
            border-top: 1px dashed #d1d5db;
        }

        .footer-invoice .catatan {
            font-size: 10px;
            color: #9ca3af;
            max-width: 320px;
            line-height: 1.5;
        }

        .footer-invoice .ttd {
            text-align: center;
            font-size: 11px;
            color: #4b5563;
        }

        .footer-invoice .ttd .garis {
            margin-top: 46px;
            border-top: 1px solid #9ca3af;
            padding-top: 4px;
            font-weight: 700;
            color: #1f2937;
        }

        /* Toolbar aksi hanya tampil di layar, tidak pernah ikut tercetak */
        .toolbar-aksi {
            width: 210mm;
            margin: 0 auto 14px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .toolbar-aksi button, .toolbar-aksi a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-cetak { background: #176851; color: #fff; }
        .btn-cetak:hover { background: #145a46; }
        .btn-kembali { background: #e5e7eb; color: #374151; }
        .btn-kembali:hover { background: #d1d5db; }

        /* ==========================================================
           MODE CETAK: sembunyikan toolbar & isolasi dokumen faktur
           dari elemen non-cetak, samakan ukuran fisik ke A4.
           ========================================================== */
        @media print {
            @page {
                size: A4;
                margin: 12mm;
            }

            body {
                background: #ffffff;
                padding: 0;
            }

            .toolbar-aksi,
            .no-print {
                display: none !important;
            }

            .lembar-invoice {
                width: auto;
                min-height: 0;
                margin: 0;
                padding: 0;
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    {{-- TOOLBAR AKSI (tidak ikut tercetak) --}}
    <div class="toolbar-aksi no-print">
        <a href="{{ route('pemesanan.show', $data->id) }}" class="btn-kembali">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <button type="button" class="btn-cetak" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
        </button>
    </div>

    <div class="lembar-invoice">

        {{-- HEADER KLINIK --}}
        <div class="header-klinik">
            <div class="identitas">
                <img src="{{ asset('images/logo-winardi.png') }}" alt="Logo Klinik Winardi" class="logo">
                <div>
                    <h1>Klinik Winardi</h1>
                    <p>
                        Klinik Gigi &amp; Layanan Protesa Gigi<br>
                        Sistem Manajemen Pemesanan Laboratorium Gigi
                    </p>
                </div>
            </div>
            <div class="label-invoice">
                <h2>INVOICE</h2>
                <p>No. {{ $data->no_pemesanan }}</p>
                <p>Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        {{-- INFORMASI PASIEN & PEMESANAN --}}
        <div class="grid-info">
            <div class="kotak-info">
                <p class="judul">Data Pasien</p>
                <div class="baris-info">
                    <span class="k">Nama Pasien</span>
                    <span class="v">{{ $data->pemeriksaan->pasien->nama ?? 'Pasien Terhapus' }}</span>
                </div>
                <div class="baris-info">
                    <span class="k">No. Rekam Medis</span>
                    <span class="v">{{ $data->pemeriksaan->pasien->no_rm ?? '-' }}</span>
                </div>
                <div class="baris-info">
                    <span class="k">Dokter Pemeriksa</span>
                    <span class="v">{{ $data->pemeriksaan->dokter->nama ?? '-' }}</span>
                </div>
                <div class="baris-info">
                    <span class="k">No. Pemeriksaan</span>
                    <span class="v">{{ $data->pemeriksaan->no_pemeriksaan ?? '-' }}</span>
                </div>
            </div>

            <div class="kotak-info">
                <p class="judul">Data Pemesanan</p>
                <div class="baris-info">
                    <span class="k">No. Pemesanan</span>
                    <span class="v">{{ $data->no_pemesanan }}</span>
                </div>
                <div class="baris-info">
                    <span class="k">Laboratorium</span>
                    <span class="v">{{ $data->lab->nama_lab ?? '-' }}</span>
                </div>
                <div class="baris-info">
                    <span class="k">Tanggal Dikirim</span>
                    <span class="v">{{ \Carbon\Carbon::parse($data->tanggal_dikirim)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="baris-info">
                    <span class="k">Estimasi Selesai</span>
                    <span class="v">{{ \Carbon\Carbon::parse($data->estimasi_selesai)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="baris-info">
                    <span class="k">Status Pemesanan</span>
                    <span class="v">{{ str_replace('_', ' ', ucwords($data->status_pemesanan)) }}</span>
                </div>
            </div>
        </div>

        {{-- RINCIAN PRODUK / TINDAKAN PROTESA GIGI --}}
        <table class="tabel-item">
            <thead>
                <tr>
                    <th style="width:32px;">No</th>
                    <th>Jenis Protesa Gigi</th>
                    <th class="text-center" style="width:90px;">Jumlah</th>
                    <th class="text-right" style="width:130px;">Harga Acuan</th>
                    <th class="text-right" style="width:140px;">Subtotal Acuan</th>
                </tr>
            </thead>
            <tbody>
                @php $totalAcuan = 0; @endphp
                @forelse($groupedItems as $namaJenis => $items)
                    @php
                        $hargaSatuan = optional($items->first()->jenisGigi)->estimasi_biaya ?? 0;
                        $jumlah = $items->count();
                        $subtotal = $hargaSatuan * $jumlah;
                        $totalAcuan += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $namaJenis }}</td>
                        <td class="text-center">{{ $jumlah }}</td>
                        <td class="text-right">Rp {{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#9ca3af; font-style:italic;">
                            Tidak ada item gigi tercatat pada pemesanan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- RINGKASAN PEMBAYARAN --}}
        <div class="ringkasan-bayar">
            <div class="kotak-ringkasan">
                <div class="baris">
                    <span>Total Harga Acuan Item</span>
                    <span>Rp {{ number_format($totalAcuan, 0, ',', '.') }}</span>
                </div>
                <div class="baris">
                    <span>Biaya Laboratorium</span>
                    <span>Rp {{ number_format($data->biaya_lab, 0, ',', '.') }}</span>
                </div>
                <div class="baris">
                    <span>Status Bayar Lab</span>
                    <span>
                        @if($data->status_bayar_lab === 'sudah_lunas')
                            <span class="badge-status badge-lunas">Lunas</span>
                        @else
                            <span class="badge-status badge-belum">Belum Lunas</span>
                        @endif
                    </span>
                </div>
                <div class="baris total">
                    <span>Total Tagihan Pasien</span>
                    <span>Rp {{ number_format($data->harga_pasien, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- FOOTER: CATATAN & TANDA TANGAN --}}
        <div class="footer-invoice">
            <p class="catatan">
                Invoice ini merupakan bukti resmi transaksi pemesanan gigi palsu (protesa) yang
                dihasilkan otomatis oleh sistem Klinik Winardi. Simpan invoice ini sebagai
                arsip pembayaran Anda.
            </p>
            <div class="ttd">
                <p>Hormat kami,</p>
                <p class="garis">Admin Klinik Winardi</p>
            </div>
        </div>
    </div>

    <script>
        // Membuka dialog cetak bawaan browser secara otomatis setelah
        // halaman faktur selesai dirender, sesuai alur cetak invoice.
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
