<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Portal Informasi Sekolah' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Source+Serif+4:ital,opsz,wght@0,8..60,600;0,8..60,700;1,8..60,400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-editorial {
            font-family: 'Source Serif 4', Georgia, serif;
        }
    </style>
</head>
<body class="bg-stone-50 text-stone-900 min-h-screen flex flex-col antialiased">
    <!-- Navbar -->
    <header class="border-b border-stone-200 bg-white/90 backdrop-blur sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <a href="{{ auth()->check() && auth()->user()->role === 'admin' ? route('admin.dashboard') : url('/informasi') }}" class="group flex items-baseline space-x-2">
                    <span class="font-editorial text-xl font-bold tracking-tight text-stone-900 group-hover:text-stone-700 transition">Portal Sekolah</span>
                    <span class="text-xs uppercase tracking-widest text-stone-400 font-semibold">Warta & Informasi</span>
                </a>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <nav class="hidden sm:flex items-center space-x-4 text-sm font-medium">
                            <a href="{{ route('admin.dashboard') }}" class="text-stone-600 hover:text-stone-900 transition">Dashboard</a>
                            <a href="{{ route('admin.informasi.index') }}" class="text-stone-600 hover:text-stone-900 transition">Kelola Informasi</a>
                        </nav>
                    @endif
                @endauth
            </div>

            <div class="flex items-center space-x-3 sm:space-x-4">
                @auth
                    <a href="{{ route('profile.edit') }}" class="text-right hidden sm:block group">
                        <div class="text-sm font-semibold text-stone-800 group-hover:text-stone-600 transition">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-stone-500 uppercase tracking-wider flex items-center gap-1 justify-end">
                            <span>{{ auth()->user()->role }}</span>
                            <span class="text-stone-400 group-hover:text-stone-600">&bull; Akun</span>
                        </div>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="sm:hidden text-xs font-medium px-2.5 py-1.5 rounded border border-stone-300 text-stone-700 bg-white hover:bg-stone-100 transition">
                        Akun
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs sm:text-sm font-medium px-3.5 py-1.5 rounded border border-stone-300 text-stone-700 bg-white hover:bg-stone-100 hover:border-stone-400 transition">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-stone-700 hover:text-stone-900">Masuk</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-md bg-stone-900 text-white text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-stone-400 hover:text-white">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-md bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-stone-200 bg-white py-6 mt-12 text-center text-xs text-stone-500">
        <p>&copy; {{ date('Y') }} Portal Informasi & Pengumuman Sekolah. Semua hak cipta dilindungi.</p>
    </footer>
</body>
</html>
