<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Tidak Tersedia - 503</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center font-sans">

    <div class="text-center p-6 max-w-md w-full">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-orange-50 text-orange-500 rounded-3xl mb-6 shadow-sm border border-orange-100">
            <i class="fa-solid fa-server text-4xl animate-bounce"></i>
        </div>

        <h1 class="text-7xl font-black text-gray-800 tracking-tight mb-2">503</h1>
        <h2 class="text-xl font-bold text-gray-700 mb-3">Service Unavailable</h2>
        <p class="text-sm text-gray-500 font-light leading-relaxed mb-8">
            Sistem sedang dalam pemeliharaan atau layanan sementara tidak tersedia. Silakan coba kembali beberapa saat lagi.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
            <button onclick="window.location.reload()"
                class="w-full sm:w-auto px-5 py-3 bg-white ring-1 ring-gray-200 text-gray-700 font-bold rounded-xl text-xs uppercase tracking-wider hover:bg-gray-50 transition-all shadow-sm cursor-pointer">
                <i class="fa-solid fa-rotate-right mr-2"></i> Coba Lagi
            </button>
        </div>
    </div>

</body>
</html>