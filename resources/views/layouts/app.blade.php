<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tempatin - Sistem Booking Fasilitas</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        background: '#F7FAFA',
                        /* putih sejuk kehijauan */
                        primary: '#1F6F78',
                        /* teal dalam */
                        success: '#6FA98A',
                        /* sage green */
                        pending: '#D9A24B',
                        /* kuning keemasan lembut */
                        slate: '#26333A',
                        /* teks utama */
                    },
                    fontFamily: {
                        manrope: ['Manrope', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-background text-slate font-inter antialiased min-h-screen flex flex-col">

<nav class="sticky top-0 z-50 bg-white border-b border-gray-200 py-4 mb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 flex justify-between items-center">
        <a href="{{ route('home') ?? '#' }}" class="font-manrope text-2xl font-bold text-primary tracking-tight">
            Tempatin
        </a>

        <div class="space-x-4 sm:space-x-6 flex items-center font-medium text-sm">
            <a href="{{ route('home') ?? '#' }}" class="text-gray-500 hover:text-primary transition-colors">Pesan Fasilitas</a>
            <a href="{{ route('booking.status') ?? '#' }}" class="text-gray-500 hover:text-primary transition-colors">Cek Status</a>

            @auth
                <a href="{{ route('admin.dashboard') ?? '#' }}" class="text-slate hover:text-primary transition-colors hidden sm:block">Admin Dashboard</a>
                <form action="{{ route('logout') ?? '#' }}" method="POST" class="inline sm:border-l border-gray-300 sm:pl-6 ml-2">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors">Logout</button>
                </form>
            @else
                <a href="{{ route('login') ?? '#' }}" class="bg-primary text-white px-4 py-2 sm:px-5 sm:py-2.5 rounded-lg hover:bg-opacity-90 transition-colors">Admin Login</a>
            @endauth
        </div>
    </div>
</nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 pb-16 w-full flex-grow relative">

        @if (session('success'))
            <div
                class="bg-success/10 border border-success/30 text-success px-5 py-4 rounded-lg mb-8 font-medium text-sm flex items-center">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-lg mb-8 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </main>

</body>

</html>
