<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found - Klinik Gigi Winardi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f0f2f0] min-h-screen flex items-center justify-center p-6">

    <div class="max-w-sm w-full text-center space-y-5">

        {{-- IKON --}}
        <div class="flex justify-center">
            <div class="w-20 h-20 bg-[#fde8e8] rounded-2xl flex items-center justify-center animate-bounce">
                <i class="fa-solid fa-map-location-dot text-[#e05252] text-3xl"></i>
            </div>
        </div>

        {{-- ANGKA & JUDUL --}}
        <div class="space-y-1">
            <h1 class="text-6xl font-black text-gray-800 leading-none">404</h1>
            <h2 class="text-base font-bold text-gray-800">Halaman Tidak Ditemukan!</h2>
        </div>

        {{-- DESKRIPSI --}}
        <p class="text-xs text-gray-500 leading-relaxed">
            Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan. Silakan kembali ke area aman Anda.
        </p>

        {{-- TOMBOL --}}
        <div class="flex items-center justify-center gap-3 pt-1">
            <a href="javascript:history.back()"
               class="flex-1 inline-flex flex-col items-center justify-center border border-gray-200 bg-white hover:bg-gray-50 text-gray-600 py-2.5 rounded-lg text-[10px] font-bold uppercase tracking-wide transition">
                <i class="fa-solid fa-arrow-left mb-1"></i>
                HALAMAN<br>SEBELUMNYA
            </a>
            <a href="{{ route('dashboard') }}"
               class="flex-1 inline-flex flex-col items-center justify-center bg-[#1a6b52] hover:bg-[#155a44] text-white py-2.5 rounded-lg text-[10px] font-bold uppercase tracking-wide transition shadow-md">
                <i class="fa-solid fa-house mb-1"></i>
                KE<br>DASHBOARD
            </a>
        </div>

    </div>

</body>
</html>
