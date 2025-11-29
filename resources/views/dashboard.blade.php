<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment System Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-md p-6">
            <a href="{{ route('dashboard') }}" class="text-xl font-bold mb-6">Dashboard</a>
            <nav class="space-y-2">
                <a href="{{ route('posts.index') }}" class="block px-3 py-2 rounded hover:bg-gray-200">Posts</a>
                {{-- <a href="{{ route('visitors.index') }}" class="block px-3 py-2 rounded hover:bg-gray-200">Visitors</a>
                <a href="{{ route('officers.index') }}" class="block px-3 py-2 rounded hover:bg-gray-200">Officers</a> --}}

            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            @yield('content')
        </div>
    </div>
</body>

</html>
