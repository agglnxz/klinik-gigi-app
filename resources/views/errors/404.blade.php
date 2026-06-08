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
<body class="bg-white min-h-screen flex items-center justify-center p-4">

    <div class="max-w-sm w-full text-center space-y-5">

        {{-- BADGE --}}
        <div class="flex justify-center">
            <span class="inline-flex items-center gap-1.5 border border-gray-300 text-gray-600 text-xs font-medium px-4 py-1.5 rounded-full">
                <i class="fa-regular fa-circle-question text-xs"></i>
                Klinik Gigi Winardi
            </span>
        </div>

        {{-- ANGKA 404 --}}
        <h1 class="text-[108px] font-black text-[#1D9E75] leading-none tracking-tighter">404</h1>

        {{-- GARIS BAWAH --}}
        <div class="w-10 h-[3px] bg-[#1D9E75] rounded-full mx-auto"></div>

        {{-- PESAN --}}
        <div class="space-y-2 pt-1">
            <h2 class="text-xl font-bold text-gray-800">Halaman tidak ditemukan</h2>
            <p class="text-sm text-gray-500 leading-relaxed">
                Halaman yang Anda cari mungkin telah dipindahkan, dihapus,<br>
                atau tidak tersedia. Silakan kembali ke dashboard.
            </p>
        </div>

        {{-- TOMBOL --}}
        <div class="flex flex-col items-center gap-3 pt-2">
            <a href="{{ route('dashboard') }}"
               class="w-64 inline-flex items-center justify-center gap-2 bg-[#529e85] hover:bg-[#43846f] text-white py-2.5 rounded-lg text-sm font-semibold transition shadow-md shadow-teal-900/20">
                <i class="fa-solid fa-house text-xs"></i>
                Kembali ke Dashboard
            </a>
            <a href="javascript:history.back()"
               class="w-64 inline-flex items-center justify-center gap-2 border border-gray-300 text-gray-500 hover:bg-gray-50 py-2.5 rounded-lg text-sm font-semibold transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Halaman Sebelumnya
            </a>
        </div>

    </div>

</body>
</html>
