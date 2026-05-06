<nav class="bg-white p-4 flex justify-end items-center w-full">
    {{-- <div class="relative w-96">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
            <i class="fa-solid fa-magnifying-glass text-sm"></i>
        </span>
        <input type="text" placeholder="Cari data..." class="w-full pl-10 pr-4 py-2 bg-gray-100 border-none rounded-md focus:ring-1 focus:ring-teal-500 text-sm">
    </div> --}}

    <div class="flex items-center space-x-4">
        <div class="relative">
            <i class="fa-regular fa-bell text-xl text-gray-500"></i>
            <span class="absolute -top-1 -right-1 bg-green-600 text-white text-[10px] rounded-full h-4 w-4 flex items-center justify-center border-2 border-white">3</span>
        </div>
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/user-profile-avatar.jpg') }}" class="w-8 h-8 rounded-xl object-cover" alt="Profile">
        </div>
    </div>
</nav>
