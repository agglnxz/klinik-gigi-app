<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Klinik Winardi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body class="bg-gray-50 flex">

    @include('layouts.sidebar')

    <div class="flex-1 overflow-hidden flex flex-col">
        @include('layouts.navbar')

        <main class="p-8 bg-teal-50">
            @yield('content')
        </main>
    </div>
<script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>
