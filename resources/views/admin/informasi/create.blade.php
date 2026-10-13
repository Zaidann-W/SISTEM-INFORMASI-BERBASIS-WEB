@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.informasi.index') }}" class="text-xs font-semibold text-stone-500 hover:text-stone-900 inline-flex items-center gap-1.5 transition">
            &larr; Kembali ke Daftar Informasi
        </a>
    </div>

    <div class="bg-white border border-stone-200 rounded-lg p-8 shadow-sm">
        <div class="border-b border-stone-100 pb-5 mb-6">
            <h1 class="font-editorial text-2xl font-bold text-stone-900">Tambah Informasi Baru</h1>
            <p class="text-xs text-stone-500 mt-1">Publikasikan pengumuman sekolah lengkap dengan gambar banner dan berkas lampiran.</p>
        </div>

        <form action="{{ route('admin.informasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="judul" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Judul Pengumuman</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required maxlength="255"
                    class="w-full px-3.5 py-2.5 rounded border border-stone-300 text-stone-900 text-sm focus:outline-none focus:border-stone-800 transition @error('judul') border-rose-500 @enderror"
                    placeholder="Contoh: Jadwal Ujian Tengah Semester Genap 2026/2027">
                @error('judul')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="kategori" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Kategori</label>
                    <select name="kategori" id="kategori" required
                        class="w-full px-3.5 py-2.5 rounded border border-stone-300 text-stone-900 text-sm focus:outline-none focus:border-stone-800 transition bg-white @error('kategori') border-rose-500 @enderror">
                        <option value="" disabled selected>Pilih Kategori</option>
                        <option value="Akademik" {{ old('kategori') === 'Akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="Pengumuman" {{ old('kategori') === 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="Ekstrakurikuler" {{ old('kategori') === 'Ekstrakurikuler' ? 'selected' : '' }}>Ekstrakurikuler</option>
                    </select>
                    @error('kategori')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Status Publikasi</label>
                    <select name="status" id="status" required
                        class="w-full px-3.5 py-2.5 rounded border border-stone-300 text-stone-900 text-sm focus:outline-none focus:border-stone-800 transition bg-white">
                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Terbitkan Langsung</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Simpan Sebagai Draf</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="isi" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Isi Pengumuman</label>
                <textarea name="isi" id="isi" rows="8" required
                    class="w-full px-3.5 py-2.5 rounded border border-stone-300 text-stone-900 text-sm focus:outline-none focus:border-stone-800 transition @error('isi') border-rose-500 @enderror"
                    placeholder="Tuliskan isi pengumuman lengkap secara jelas...">{{ old('isi') }}</textarea>
                @error('isi')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Gambar Banner -->
            <div class="p-4 rounded-lg bg-stone-50 border border-stone-200 space-y-2">
                <label for="gambar" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider">Gambar Sampul / Banner (Opsional)</label>
                <input type="file" name="gambar" id="gambar" accept="image/*"
                    class="block w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-stone-900 file:text-white hover:file:bg-stone-800 cursor-pointer">
                <p class="text-[11px] text-stone-400">Format: JPG, PNG, atau WebP. Maksimal 2MB.</p>
                @error('gambar')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Lampiran PDF -->
            <div class="p-4 rounded-lg bg-stone-50 border border-stone-200 space-y-2">
                <label for="lampiran" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider">Lampiran Dokumen Resmi (Opsional)</label>
                <input type="file" name="lampiran" id="lampiran" accept=".pdf,.doc,.docx,.xls,.xlsx"
                    class="block w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-stone-900 file:text-white hover:file:bg-stone-800 cursor-pointer">
                <p class="text-[11px] text-stone-400">Format: PDF, Word, atau Excel surat edaran/jadwal. Maksimal 5MB.</p>
                @error('lampiran')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Opsi Pin / Penting -->
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_pinned" id="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}
                    class="rounded border-stone-300 text-stone-900 focus:ring-stone-900 h-4 w-4">
                <label for="is_pinned" class="text-xs font-semibold text-stone-700 cursor-pointer">
                    Sematkan pengumuman ini di posisi paling atas (Pengumuman Penting)
                </label>
            </div>

            <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.informasi.index') }}" class="px-4 py-2 text-xs font-medium text-stone-600 hover:text-stone-900 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded bg-stone-900 text-white text-xs font-semibold hover:bg-stone-800 transition">
                    Simpan Informasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
