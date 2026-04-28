<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Klinik Gigi Winardi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body class="bg-teal-50 flex items-center justify-center min-h-screen">

    <div class="flex flex-col md:flex-row bg-white rounded-lg shadow-2xl overflow-hidden max-w-4xl w-full mx-4">

        <div class="md:w-1/2 bg-gradient-custom p-12 flex flex-col items-center justify-center text-center relative">
            <div class="absolute top-0 left-0 w-full h-full opacity-20 pointer-events-none">
                <img src="{{ asset('images/background-winardi.png') }}"
                    alt="Background"
                    class="w-full h-full object-cover">
            </div>

            <img src="{{ asset('images/logo-winardi.png') }}" alt="Logo" class="w-32 mb-6 relative z-10">
            <h1 class="text-3xl font-bold text-[#1a535c] tracking-wider relative z-10">KLINIK GIGI</h1>
            <h2 class="text-4xl font-extrabold text-[#1a535c] mb-8 relative z-10">WINARDI</h2>
            <p class="text-teal-700 italic text-sm relative z-10">"Senyum Sehat, Hidup Lebih Percaya Diri"</p>
        </div>

        <div class="md:w-1/2 p-12 flex flex-col justify-center">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-800">Selamat Datang</h3>
                <p class="text-teal-700 font-semibold">di Sistem Klinik Gigi Winardi</p>
                <p class="text-gray-500 text-sm mt-4">Silahkan login menggunakan akun yang telah diberikan.</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fa-regular fa-user"></i>
                        </span>
                        <input type="text" name="email" placeholder="Username / Email"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>

                <div class="mb-4">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" placeholder="Password"
                            class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 cursor-pointer">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                    </div>
                </div>

                @if($errors->any())
                <div class="bg-red-100 border border-red-200 text-red-600 px-4 py-3 rounded-md mb-6 flex items-start text-xs">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 mr-2"></i>
                    <span>Email atau password yang Anda masukkan salah. Silahkan coba lagi.</span>
                </div>
                @endif

                <button type="submit" class="w-full bg-[#1b5e4b] hover:bg-[#144d3d] text-white font-bold py-3 rounded-md shadow-lg transition duration-300">
                    Login
                </button>
            </form>

            <div class="mt-12 text-center">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest">© KLINIK GIGI WINARDI</p>
            </div>
        </div>
    </div>

</body>
</html>
