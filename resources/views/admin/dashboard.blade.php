@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-stone-200 pb-5">
        <div>
            <h1 class="font-editorial text-3xl font-bold tracking-tight text-stone-900">Dashboard Administrasi</h1>
            <p class="mt-1 text-sm text-stone-600">Ringkasan aktivitas publikasi dan statistik portal sekolah.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.informasi.create') }}" class="inline-flex items-center px-4 py-2 rounded border border-stone-900 bg-stone-900 text-white text-sm font-medium hover:bg-stone-800 transition">
                + Tambah Informasi Baru
            </a>
        </div>
    </div>

    <!-- 4 Kartu Statistik Ringkas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-stone-200 rounded-lg p-5">
            <span class="text-xs uppercase tracking-wider font-semibold text-stone-500">Total Pengumuman</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="font-editorial text-3xl font-bold text-stone-900">{{ $totalInformasi }}</span>
                <span class="text-xs text-stone-400">Semua artikel</span>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-lg p-5">
            <span class="text-xs uppercase tracking-wider font-semibold text-stone-500">Status Terbit / Draf</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="font-editorial text-3xl font-bold text-stone-900">{{ $totalPublished }}</span>
                <span class="text-xs text-stone-400">{{ $totalDraft }} draf tersimpan</span>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-lg p-5">
            <span class="text-xs uppercase tracking-wider font-semibold text-stone-500">Total Pembaca (Views)</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="font-editorial text-3xl font-bold text-stone-900">{{ number_format($totalViews) }}</span>
                <span class="text-xs text-stone-400">Total tayangan</span>
            </div>
        </div>

        <div class="bg-white border border-stone-200 rounded-lg p-5">
            <span class="text-xs uppercase tracking-wider font-semibold text-stone-500">Total Pengguna</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="font-editorial text-3xl font-bold text-stone-900">{{ $totalUser }}</span>
                <span class="text-xs text-stone-400">Admin & Siswa</span>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian Tabel -->
    <div class="bg-white border border-stone-200 rounded-lg overflow-hidden shadow-sm">
        <div class="p-5 border-b border-stone-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-editorial text-xl font-bold text-stone-900">Kelola Informasi Sekolah</h2>
                <p class="text-xs text-stone-500 mt-0.5">{{ $informasis->total() }} informasi ditemukan</p>
            </div>

            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari judul..."
                    class="px-3 py-1.5 text-xs rounded border border-stone-300 focus:outline-none focus:border-stone-800">
                <select name="kategori" class="px-2.5 py-1.5 text-xs rounded border border-stone-300 bg-white">
                    <option value="">Semua Kategori</option>
                    <option value="Akademik" {{ request('kategori') === 'Akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="Pengumuman" {{ request('kategori') === 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                    <option value="Ekstrakurikuler" {{ request('kategori') === 'Ekstrakurikuler' ? 'selected' : '' }}>Ekstrakurikuler</option>
                </select>
                <select name="status" class="px-2.5 py-1.5 text-xs rounded border border-stone-300 bg-white">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Terbit</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draf</option>
                </select>
                <button type="submit" class="px-3 py-1.5 bg-stone-900 text-white text-xs font-medium rounded hover:bg-stone-800">
                    Filter
                </button>
                @if(request('cari') || request('kategori') || request('status'))
                    <a href="{{ route('admin.dashboard') }}" class="px-2.5 py-1.5 border border-stone-300 text-stone-600 text-xs rounded hover:bg-stone-100">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-stone-700">
                <thead class="bg-stone-50 border-b border-stone-200 text-xs uppercase tracking-wider text-stone-500">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 font-semibold">Judul</th>
                        <th scope="col" class="px-6 py-3.5 font-semibold">Kategori</th>
                        <th scope="col" class="px-6 py-3.5 font-semibold">Status</th>
                        <th scope="col" class="px-6 py-3.5 font-semibold">Pembaca</th>
                        <th scope="col" class="px-6 py-3.5 font-semibold">Tanggal</th>
                        <th scope="col" class="px-6 py-3.5 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($informasis as $info)
                        <tr class="hover:bg-stone-50/75 transition-colors">
                            <td class="px-6 py-4 font-medium text-stone-900 max-w-sm">
                                <div class="flex items-center gap-2">
                                    @if($info->is_pinned)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300" title="Disematkan di atas">PIN</span>
                                    @endif
                                    <a href="{{ url('/informasi/' . $info->id) }}" target="_blank" class="hover:underline line-clamp-1">
                                        {{ $info->judul }}
                                    </a>
                                </div>
                                @if($info->lampiran)
                                    <span class="text-[11px] text-stone-400 mt-0.5 inline-block">Lampiran: {{ $info->lampiran_nama }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                    @if(strtolower($info->kategori) === 'akademik') bg-sky-50 text-sky-800 border border-sky-200
                                    @elseif(strtolower($info->kategori) === 'ekstrakurikuler') bg-emerald-50 text-emerald-800 border border-emerald-200
                                    @else bg-amber-50 text-amber-800 border border-amber-200 @endif">
                                    {{ $info->kategori }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold {{ $info->status === 'published' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-stone-100 text-stone-600 border border-stone-300' }}">
                                    {{ $info->status === 'published' ? 'Terbit' : 'Draf' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-stone-500">
                                {{ $info->views }}x
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-stone-500">
                                {{ $info->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs space-x-2">
                                <a href="{{ route('admin.informasi.edit', $info) }}" class="font-medium text-stone-700 hover:text-stone-900 border border-stone-300 px-2.5 py-1 rounded bg-white hover:bg-stone-100 transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.informasi.destroy', $info) }}" method="POST" class="inline" onsubmit="return confirm('Hapus informasi ini dan berkas terkait?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-rose-600 hover:text-rose-800 border border-rose-200 px-2.5 py-1 rounded bg-rose-50 hover:bg-rose-100 transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-stone-400">
                                Belum ada informasi pengumuman yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($informasis->hasPages())
            <div class="px-6 py-4 border-t border-stone-200">
                {{ $informasis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
