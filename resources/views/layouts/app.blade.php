<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment System Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-md p-6">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold mb-10">Dashboard</a>
            <nav class="space-y-2">
                <a href="{{ route('posts.index') }}" class="block px-3 py-2 rounded hover:bg-gray-200">Posts</a>
                <a href="{{ route('visitors.index') }}" class="block px-3 py-2 rounded hover:bg-gray-200">Visitors</a>
                <a href="{{ route('officers.index') }}" class="block px-3 py-2 rounded hover:bg-gray-200">Officers</a>
                <a href="{{ route('workdays.index') }}" class="block px-3 py-2 rounded hover:bg-gray-200">Workdays</a>


            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6 bg-gray-100">

            <!-- Flash Messages (Auto-hide) -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="bg-green-200 text-green-800 p-3 rounded mb-4 transition duration-500">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="bg-red-200 text-red-800 p-3 rounded mb-4 transition duration-500">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content') <!-- Page-specific content goes here -->

        </div>
    </div>
</body>

</html>
