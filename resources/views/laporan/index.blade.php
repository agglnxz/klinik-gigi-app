@extends('layouts.main')

@section('title', 'Laporan Pemesanan')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        :root {
            --bg: #F0FDFA;
            --panel: #FFFFFF;
            --ink: #1F2937;
            --muted: #6B7280;
            --line: #F3F4F6;
            --green: #529E85;
            --green-dark: #43846F;
            --green-tint: #CCFBF1;
            --green-tint-ink: #0F766E;
            --coral: #C4573F;
            --coral-tint: #FCE7E3;
            --amber: #A16207;
            --amber-tint: #FEF3C7;
            --shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.08), 0 2px 4px -2px rgba(15, 23, 42, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        .laporan-page {
            margin: -2rem;
            padding: 2rem;
            min-height: calc(100% + 4rem);
            background: var(--bg);
            color: var(--ink);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        .page {
            width: 100%;
            max-width: 1180px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 22px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .header h1 {
            font-size: 26px;
            font-weight: 800;
            margin: 0 0 4px;
            letter-spacing: -0.01em;
        }

        .header .sub {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .header-meta {
            flex: 0 0 auto;
            min-width: 190px;
            padding-right: 2px;
            text-align: right;
            font-size: 12.5px;
            color: var(--muted);
            line-height: 1.6;
            white-space: nowrap;
        }

        .header-meta strong {
            color: var(--ink);
            font-weight: 600;
        }

        /* Stat cards */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 16px;
        }

        .stat-card {
            background: var(--panel);
            border-radius: 16px;
            padding: 18px 20px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon svg {
            width: 20px;
            height: 20px;
        }

        .stat-icon i {
            font-size: 20px;
        }

        .rupiah-symbol {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -0.04em;
        }

        .stat-icon.total {
            background: var(--green-tint);
            color: var(--green);
        }

        .stat-icon.done {
            background: var(--green-tint);
            color: var(--green);
        }

        .stat-icon.progress {
            background: var(--coral-tint);
            color: var(--coral);
        }

        .stat-icon.value {
            background: var(--amber-tint);
            color: var(--amber);
        }

        .stat-body .num {
            font-size: 22px;
            font-weight: 800;
            line-height: 1.1;
            display: block;
        }

        .stat-body .label {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
        }

        /* Toolbar / filters */
        .toolbar {
            background: var(--panel);
            border-radius: 16px;
            box-shadow: var(--shadow);
            padding: 16px 18px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1 1 220px;
            display: flex;
            align-items: center;
            gap: 8px;
            background: #F9FAFB;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 9px 16px;
            min-width: 200px;
        }

        .search-box svg {
            width: 16px;
            height: 16px;
            color: var(--muted);
            flex-shrink: 0;
        }

        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            font-family: 'Inter', sans-serif;
            font-size: 13.5px;
            width: 100%;
            color: var(--ink);
        }

        .search-box input::placeholder {
            color: var(--muted);
        }

        .pill-field {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #F9FAFB;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 8px 14px;
        }

        .pill-field label {
            font-size: 11.5px;
            color: var(--muted);
            font-weight: 600;
            white-space: nowrap;
        }

        .pill-field input,
        .pill-field select {
            border: none;
            background: transparent;
            outline: none;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
            cursor: pointer;
        }

        .pill-field input[type="date"] {
            width: 118px;
            cursor: text;
        }

        .pill-field .sep {
            color: var(--muted);
            font-size: 12px;
        }

        .btn {
            font-family: 'Inter', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 999px;
            border: 1px solid transparent;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .btn svg {
            width: 15px;
            height: 15px;
        }

        .btn-ghost {
            background: #F9FAFB;
            border-color: var(--line);
            color: var(--ink);
        }

        .btn-ghost:hover {
            background: #F3F4F6;
        }

        .btn-primary {
            background: var(--green);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--green-dark);
        }

        /* Table card */
        .table-card {
            background: var(--panel);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            min-width: 1600px;
        }

        thead th {
            text-align: left;
            font-weight: 600;
            font-size: 11.5px;
            color: var(--muted);
            text-transform: none;
            padding: 14px 20px;
            background: #F9FAFB;
            border-bottom: 1px solid var(--line);
            white-space: nowrap;
        }

        tbody td {
            padding: 14px 20px;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #F9FAFB;
        }

        td.num,
        th.num {
            text-align: right;
        }

        td.muted {
            color: var(--muted);
        }

        .patient {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--green-tint);
            color: var(--green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12.5px;
            flex-shrink: 0;
        }

        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 600;
        }

        .status.selesai {
            background: var(--green-tint);
            color: var(--green-dark);
        }

        .status.proses {
            background: var(--coral-tint);
            color: var(--coral);
        }

        .status.cetak {
            background: var(--amber-tint);
            color: var(--amber);
        }

        .empty {
            padding: 60px 20px;
            text-align: center;
            color: var(--muted);
            font-size: 14px;
        }

        .footer-note {
            padding: 14px 4px 0;
            font-size: 12px;
            color: var(--muted);
        }

        .btn-sm {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            background: var(--green);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-sm:hover {
            background: var(--green-dark);
        }

        @media (max-width:920px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width:640px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
            }

            .header-meta {
                text-align: left;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    </head>
    <div class="laporan-page">

        <div class="page">

            <div class="header">
                <div>
                    <h1>Laporan Pemesanan Gigi Palsu</h1>
                    <p class="sub">Rekap pesanan laboratorium sebelum diekspor</p>
                </div>
                <div class="header-meta">
                    Dicetak: <strong id="printedAt"></strong><br>
                    Total baris ditampilkan: <strong id="printedCount">0</strong>
                </div>
            </div>

            <div class="stats">
                <div class="stat-card">
                    <div class="stat-icon total">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <path d="M3 10h18" />
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                        </svg>
                    </div>
                    <div class="stat-body">
                        <span class="num" id="sumTotal">0</span>
                        <span class="label">Total pesanan</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon done">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5" />
                        </svg>
                    </div>
                    <div class="stat-body">
                        <span class="num" id="sumSelesai">0</span>
                        <span class="label">Selesai / diambil</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon progress">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 7v5l3 3" />
                        </svg>
                    </div>
                    <div class="stat-body">
                        <span class="num" id="sumProses">0</span>
                        <span class="label">Masih diproses</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon value">
                        <span class="rupiah-symbol" aria-label="Rupiah">Rp</span>
                    </div>
                    <div class="stat-body">
                        <span class="num" id="sumOmzet">Rp 0</span>
                        <span class="label">Total nilai pesanan</span>
                    </div>
                </div>
            </div>

            <div class="toolbar">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                    <input type="text" id="fCari" placeholder="Cari nama pasien atau dokter...">
                </div>

                <div class="pill-field">
                    <label>Dari</label>
                    <input type="date" id="fDari" aria-label="Dari tanggal">
                    <span class="sep">&ndash;</span>
                    <input type="date" id="fSampai" aria-label="Sampai tanggal">
                </div>

                <div class="pill-field">
                    <label>Status</label>
                    <select id="fStatus">
                        <option value="">Semua status</option>
                        <option value="dalam_proses">Belum Tiba</option>
                        <option value="tiba_di_klinik">Tiba di Klinik</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>

                <button class="btn btn-ghost" id="btnReset">Atur ulang</button>
                <button class="btn btn-primary" id="btnExport">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 3v12" />
                        <path d="m7 10 5 5 5-5" />
                        <path d="M5 21h14" />
                    </svg>
                    Ekspor ke Excel
                </button>
            </div>

            <div class="table-card">
                <div class="table-wrap">
                    <table id="tabel">
                        <thead>
                            <tr>
                                <th>NO PEMESANAN</th>
                                <th>NAMA PASIEN</th>
                                <th>JENIS GIGI</th>
                                <th>TANGGAL KIRIM</th>
                                <th>ESTIMASI SELESAI</th>
                                <th>LABORATORIUM</th>
                                <th>BIAYA LAB</th>
                                <th>BAYAR LAB</th>
                                <th>HARGA PASIEN</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="tbody"></tbody>
                    </table>
                    <div class="empty" id="emptyState" style="display:none;">Tidak ada pesanan yang cocok dengan filter
                        ini.</div>
                </div>
            </div>

            <p class="footer-note">Gunakan filter di atas untuk mempersempit rentang sebelum menekan "Ekspor ke Excel"
                &mdash; file akan berisi persis baris yang sedang tampil.</p>

        </div>

        <script>
            const legacyData = [{
                    no: 1,
                    tgl: "2026-01-08",
                    pasien: "Sutrisno Wibowo",
                    jenis: "Gigi tiruan penuh",
                    bahan: "Akrilik",
                    dokter: "drg. Amelia Putri",
                    status: "Selesai",
                    tglSelesai: "2026-01-20",
                    harga: 2500000
                },
                {
                    no: 2,
                    tgl: "2026-01-15",
                    pasien: "Rahmawati Sari",
                    jenis: "Gigi tiruan sebagian",
                    bahan: "Valplast",
                    dokter: "drg. Bagus Nugraha",
                    status: "Diambil",
                    tglSelesai: "2026-01-28",
                    harga: 1800000
                },
                {
                    no: 3,
                    tgl: "2026-02-03",
                    pasien: "Hendra Kusuma",
                    jenis: "Gigi tiruan kerangka logam",
                    bahan: "Metal frame",
                    dokter: "Klinik Dental Sehat",
                    status: "Diproses",
                    tglSelesai: "",
                    harga: 3200000
                },
                {
                    no: 4,
                    tgl: "2026-02-11",
                    pasien: "Siti Nur Aini",
                    jenis: "Gigi tiruan penuh",
                    bahan: "Akrilik",
                    dokter: "drg. Amelia Putri",
                    status: "Menunggu Cetakan",
                    tglSelesai: "",
                    harga: 2500000
                },
                {
                    no: 5,
                    tgl: "2026-02-19",
                    pasien: "Bambang Setiawan",
                    jenis: "Gigi tiruan sebagian",
                    bahan: "Flexible denture",
                    dokter: "drg. Citra Dewi",
                    status: "Selesai",
                    tglSelesai: "2026-03-02",
                    harga: 2100000
                },
                {
                    no: 6,
                    tgl: "2026-03-05",
                    pasien: "Yuliana Marpaung",
                    jenis: "Gigi tiruan penuh",
                    bahan: "Akrilik",
                    dokter: "Klinik Dental Sehat",
                    status: "Diambil",
                    tglSelesai: "2026-03-18",
                    harga: 2450000
                },
                {
                    no: 7,
                    tgl: "2026-03-12",
                    pasien: "Agus Prasetyo",
                    jenis: "Gigi tiruan kerangka logam",
                    bahan: "Metal frame",
                    dokter: "drg. Bagus Nugraha",
                    status: "Diproses",
                    tglSelesai: "",
                    harga: 3300000
                },
                {
                    no: 8,
                    tgl: "2026-03-22",
                    pasien: "Dewi Anggraini",
                    jenis: "Gigi tiruan sebagian",
                    bahan: "Valplast",
                    dokter: "drg. Citra Dewi",
                    status: "Selesai",
                    tglSelesai: "2026-04-01",
                    harga: 1900000
                },
                {
                    no: 9,
                    tgl: "2026-04-02",
                    pasien: "Muhammad Rizal",
                    jenis: "Gigi tiruan penuh",
                    bahan: "Akrilik",
                    dokter: "Klinik Dental Sehat",
                    status: "Menunggu Cetakan",
                    tglSelesai: "",
                    harga: 2600000
                },
                {
                    no: 10,
                    tgl: "2026-04-14",
                    pasien: "Ratna Kusumawati",
                    jenis: "Gigi tiruan sebagian",
                    bahan: "Flexible denture",
                    dokter: "drg. Amelia Putri",
                    status: "Diambil",
                    tglSelesai: "2026-04-27",
                    harga: 2050000
                },
                {
                    no: 11,
                    tgl: "2025-11-09",
                    pasien: "Fajar Nugroho",
                    jenis: "Gigi tiruan penuh",
                    bahan: "Akrilik",
                    dokter: "drg. Bagus Nugraha",
                    status: "Diambil",
                    tglSelesai: "2025-11-22",
                    harga: 2400000
                },
                {
                    no: 12,
                    tgl: "2025-12-18",
                    pasien: "Wulan Sari",
                    jenis: "Gigi tiruan kerangka logam",
                    bahan: "Metal frame",
                    dokter: "Klinik Dental Sehat",
                    status: "Diambil",
                    tglSelesai: "2026-01-03",
                    harga: 3150000
                },
            ];

            const data = @json($reportRows);

            const rupiah = n => "Rp " + Number(n).toLocaleString("id-ID");
            const tglIndo = s => {
                if (!s) return "&mdash;";
                const [y, m, d] = s.split("-");
                const bln = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
                return `${d} ${bln[parseInt(m,10)-1]} ${y}`;
            };
            const statusLabel = s => ({
                dalam_proses: "Belum Tiba",
                tiba_di_klinik: "Tiba di Klinik",
                selesai: "Selesai",
                dibatalkan: "Dibatalkan"
            } [s] || s);
            const statusClass = s => s === "selesai" || s === "tiba_di_klinik" ? "selesai" : "proses";
            const initials = name => name.split(" ").filter(Boolean).slice(0, 2).map(w => w[0]).join("").toUpperCase();

            function getFiltered() {
                const cari = document.getElementById('fCari').value.trim().toLowerCase();
                const dari = document.getElementById('fDari').value;
                const sampai = document.getElementById('fSampai').value;
                const status = document.getElementById('fStatus').value;

                return data.filter(row => {
                    if (dari && row.tgl < dari) return false;
                    if (sampai && row.tgl > sampai) return false;
                    if (status && row.status !== status) return false;
                    if (cari && !(row.pasien.toLowerCase().includes(cari) || row.laboratorium.toLowerCase().includes(
                                cari) ||
                            row.jenis.toLowerCase().includes(cari)))
                        return false;
                    return true;
                });
            }

            function render() {
                const rows = getFiltered();
                const tbody = document.getElementById('tbody');
                const emptyState = document.getElementById('emptyState');
                tbody.innerHTML = "";

                if (rows.length === 0) {
                    emptyState.style.display = "block";
                } else {
                    emptyState.style.display = "none";
                    rows.forEach((r, i) => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                <td class="muted">${r.no}</td>
        <td>
          <div class="patient">
            <div class="avatar">${initials(r.pasien)}</div>
            <span>${r.pasien}</span>
          </div>
        </td>
        <td>${r.jenis}</td>
                <td>${tglIndo(r.tgl)}</td>
                <td>${tglIndo(r.tglSelesai)}</td>
                <td>${r.laboratorium}</td>
                <td>${rupiah(r.biayaLab)}</td>
                <td>${r.bayarLab > 0 ? rupiah(r.bayarLab) : 'Belum Lunas'}</td>
                <td>${rupiah(r.harga)}</td>
                <td><span class="status ${statusClass(r.status)}">${statusLabel(r.status)}</span></td>
                <td><a class="btn-sm" href="${@json(url('/pemesanan'))}/${r.id}">Detail</a></td>
      `;
                        tbody.appendChild(tr);
                    });
                }

                document.getElementById('sumTotal').textContent = rows.length;
                document.getElementById('sumSelesai').textContent = rows.filter(r => r.status === "selesai" || r.status ===
                    "tiba_di_klinik").length;
                document.getElementById('sumProses').textContent = rows.filter(r => r.status === "dalam_proses").length;
                document.getElementById('sumOmzet').textContent = rupiah(rows.reduce((a, r) => a + r.harga, 0));
                document.getElementById('printedCount').textContent = rows.length;

                return rows;
            }

            document.getElementById('printedAt').textContent = new Date().toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            ['fCari', 'fDari', 'fSampai', 'fStatus'].forEach(id => {
                const el = document.getElementById(id);
                el.addEventListener(el.tagName === 'INPUT' && el.type === 'text' ? 'input' : 'change', render);
            });

            document.getElementById('btnReset').addEventListener('click', () => {
                ['fCari', 'fDari', 'fSampai', 'fStatus'].forEach(id => document.getElementById(id).value = "");
                render();
            });

            document.getElementById('btnExport').addEventListener('click', () => {
                const rows = getFiltered();
                if (rows.length === 0) {
                    alert('Tidak ada data untuk diekspor pada filter ini.');
                    return;
                }

                const params = new URLSearchParams({
                    search: document.getElementById('fCari').value.trim(),
                    dari: document.getElementById('fDari').value,
                    sampai: document.getElementById('fSampai').value,
                    status: document.getElementById('fStatus').value,
                });

                window.location.href = `{{ route('laporan.export') }}?${params.toString()}`;
            });

            render();
        </script>

    @endsection
