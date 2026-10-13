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
            <h1 class="font-editorial text-2xl font-bold text-stone-900">Ubah Informasi Pengumuman</h1>
            <p class="text-xs text-stone-500 mt-1">Perbarui judul, isi, status publikasi, serta berkas media pengumuman.</p>
        </div>

        <form action="{{ route('admin.informasi.update', $informasi) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="judul" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Judul Pengumuman</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', $informasi->judul) }}" required maxlength="255"
                    class="w-full px-3.5 py-2.5 rounded border border-stone-300 text-stone-900 text-sm focus:outline-none focus:border-stone-800 transition @error('judul') border-rose-500 @enderror">
                @error('judul')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="kategori" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Kategori</label>
                    <select name="kategori" id="kategori" required
                        class="w-full px-3.5 py-2.5 rounded border border-stone-300 text-stone-900 text-sm focus:outline-none focus:border-stone-800 transition bg-white @error('kategori') border-rose-500 @enderror">
                        <option value="Akademik" {{ old('kategori', $informasi->kategori) === 'Akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="Pengumuman" {{ old('kategori', $informasi->kategori) === 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="Ekstrakurikuler" {{ old('kategori', $informasi->kategori) === 'Ekstrakurikuler' ? 'selected' : '' }}>Ekstrakurikuler</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Status Publikasi</label>
                    <select name="status" id="status" required
                        class="w-full px-3.5 py-2.5 rounded border border-stone-300 text-stone-900 text-sm focus:outline-none focus:border-stone-800 transition bg-white">
                        <option value="published" {{ old('status', $informasi->status) === 'published' ? 'selected' : '' }}>Terbitkan Langsung</option>
                        <option value="draft" {{ old('status', $informasi->status) === 'draft' ? 'selected' : '' }}>Simpan Sebagai Draf</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="isi" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Isi Pengumuman</label>
                <textarea name="isi" id="isi" rows="8" required
                    class="w-full px-3.5 py-2.5 rounded border border-stone-300 text-stone-900 text-sm focus:outline-none focus:border-stone-800 transition @error('isi') border-rose-500 @enderror">{{ old('isi', $informasi->isi) }}</textarea>
                @error('isi')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gambar Banner & Preview -->
            <div class="p-4 rounded-lg bg-stone-50 border border-stone-200 space-y-3">
                <label for="gambar" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider">Gambar Sampul / Banner</label>
                @if($informasi->gambar)
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('storage/' . $informasi->gambar) }}" alt="Preview" class="w-24 h-16 object-cover rounded border border-stone-300">
                        <label class="flex items-center gap-1.5 text-xs text-rose-600 cursor-pointer">
                            <input type="checkbox" name="hapus_gambar" value="1" class="rounded border-stone-300 text-rose-600">
                            Hapus gambar saat ini
                        </label>
                    </div>
                @endif
                <input type="file" name="gambar" id="gambar" accept="image/*"
                    class="block w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-stone-900 file:text-white hover:file:bg-stone-800 cursor-pointer">
                <p class="text-[11px] text-stone-400">Pilih file baru untuk mengganti gambar sampul (JPG, PNG, WebP max 2MB).</p>
            </div>

            <!-- Lampiran Berkas & Preview -->
            <div class="p-4 rounded-lg bg-stone-50 border border-stone-200 space-y-3">
                <label for="lampiran" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider">Lampiran Dokumen Resmi</label>
                @if($informasi->lampiran)
                    <div class="flex items-center justify-between p-2.5 bg-white rounded border border-stone-200 text-xs">
                        <span class="font-medium text-stone-800">{{ $informasi->lampiran_nama ?? 'Dokumen Terlampir' }}</span>
                        <label class="flex items-center gap-1 text-rose-600 cursor-pointer">
                            <input type="checkbox" name="hapus_lampiran" value="1" class="rounded border-stone-300 text-rose-600">
                            Hapus lampiran
                        </label>
                    </div>
                @endif
                <input type="file" name="lampiran" id="lampiran" accept=".pdf,.doc,.docx,.xls,.xlsx"
                    class="block w-full text-xs text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-stone-900 file:text-white hover:file:bg-stone-800 cursor-pointer">
                <p class="text-[11px] text-stone-400">Pilih file baru untuk mengganti lampiran (PDF, Word, Excel max 5MB).</p>
            </div>

            <!-- Opsi Pin / Penting -->
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_pinned" id="is_pinned" value="1" {{ old('is_pinned', $informasi->is_pinned) ? 'checked' : '' }}
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
