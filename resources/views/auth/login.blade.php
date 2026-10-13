<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Portal Informasi Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Source+Serif+4:ital,opsz,wght@0,8..60,600;0,8..60,700;1,8..60,400&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-editorial { font-family: 'Source Serif 4', Georgia, serif; }
    </style>
</head>
<body class="bg-stone-50 text-stone-900 min-h-screen flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <span class="text-xs uppercase tracking-widest text-stone-500 font-semibold">Sistem Informasi Pengumuman</span>
            <h1 class="font-editorial text-3xl font-bold tracking-tight text-stone-900 mt-1">Portal Sekolah</h1>
            <p class="text-xs text-stone-600 mt-2">Silakan masuk menggunakan kredensial akun terdaftar Anda.</p>
        </div>

        <div class="bg-white border border-stone-200 rounded-lg p-8 shadow-sm">
            @if($errors->any())
                <div class="mb-5 p-3 rounded bg-rose-50 border border-rose-200 text-xs text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-3.5 py-2.5 rounded border border-stone-300 text-stone-900 text-sm focus:outline-none focus:border-stone-800 transition"
                        placeholder="nama@sekolah.test">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-3.5 py-2.5 rounded border border-stone-300 text-stone-900 text-sm focus:outline-none focus:border-stone-800 transition"
                        placeholder="••••••••">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 rounded bg-stone-900 text-white text-xs font-semibold hover:bg-stone-800 transition">
                        Masuk ke Portal
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-stone-100 text-xs text-stone-500 space-y-1.5 bg-stone-50/70 p-3.5 rounded border border-stone-200/60">
                <p class="font-semibold text-stone-700">Kredensial Pengujian:</p>
                <p>&bull; Admin: <code class="text-stone-800 font-mono">admin@sekolah.test</code> / <code class="text-stone-800 font-mono">password</code></p>
                <p>&bull; Siswa: <code class="text-stone-800 font-mono">siswa@sekolah.test</code> / <code class="text-stone-800 font-mono">password</code></p>
            </div>
        </div>

        <div class="mt-6 text-center text-xs text-stone-400">
            &copy; {{ date('Y') }} Portal Informasi Sekolah
        </div>
    </div>
</body>
</html>
