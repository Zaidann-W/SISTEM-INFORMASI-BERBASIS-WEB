@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b border-stone-200 pb-4">
        <h1 class="font-editorial text-3xl font-bold tracking-tight text-stone-900">Pengaturan Akun</h1>
        <p class="mt-1 text-sm text-stone-600">Kelola identitas profil dan keamanan kata sandi Anda.</p>
    </div>

    <!-- Informasi Akun -->
    <div class="bg-white border border-stone-200 rounded-lg p-6 sm:p-8">
        <h2 class="font-editorial text-xl font-bold text-stone-900 mb-4">Informasi Profil</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-xs uppercase tracking-wider text-stone-500 font-semibold">Nama Lengkap</span>
                <p class="font-medium text-stone-900 mt-1">{{ $user->name }}</p>
            </div>
            <div>
                <span class="text-xs uppercase tracking-wider text-stone-500 font-semibold">Alamat Email</span>
                <p class="font-medium text-stone-900 mt-1">{{ $user->email }}</p>
            </div>
            <div>
                <span class="text-xs uppercase tracking-wider text-stone-500 font-semibold">Peran Akses</span>
                <p class="font-medium text-stone-900 mt-1 capitalize">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $user->role === 'admin' ? 'bg-stone-900 text-white' : 'bg-stone-100 text-stone-800' }}">
                        {{ $user->role === 'admin' ? 'Administrator' : 'Siswa' }}
                    </span>
                </p>
            </div>
            <div>
                <span class="text-xs uppercase tracking-wider text-stone-500 font-semibold">Terdaftar Sejak</span>
                <p class="font-medium text-stone-900 mt-1">{{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Form Ganti Password -->
    <div class="bg-white border border-stone-200 rounded-lg p-6 sm:p-8">
        <div class="border-b border-stone-100 pb-4 mb-6">
            <h2 class="font-editorial text-xl font-bold text-stone-900">Perbarui Kata Sandi</h2>
            <p class="text-xs text-stone-500 mt-1">Pastikan kata sandi baru Anda memiliki minimal 6 karakter kombinasi yang aman.</p>
        </div>

        <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" id="current_password" required
                    class="w-full px-3.5 py-2.5 rounded border border-stone-300 text-stone-900 text-sm focus:outline-none focus:border-stone-800 transition @error('current_password') border-rose-500 @enderror">
                @error('current_password')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Kata Sandi Baru</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-3.5 py-2.5 rounded border border-stone-300 text-stone-900 text-sm focus:outline-none focus:border-stone-800 transition @error('password') border-rose-500 @enderror">
                @error('password')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-3.5 py-2.5 rounded border border-stone-300 text-stone-900 text-sm focus:outline-none focus:border-stone-800 transition">
            </div>

            <div class="pt-3 border-t border-stone-100 flex justify-end">
                <button type="submit" class="px-5 py-2.5 rounded bg-stone-900 text-white text-xs font-semibold hover:bg-stone-800 transition">
                    Simpan Kata Sandi Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
