@extends('layouts.app')

@section('content')
<div class="mb-8 space-y-6">
    <div class="border-b border-stone-200 pb-5">
        <h1 class="font-editorial text-3xl sm:text-4xl font-bold tracking-tight text-stone-900">Papan Pengumuman Sekolah</h1>
        <p class="mt-1.5 text-sm text-stone-600">Pusat publikasi informasi akademik resmi, kegiatan kesiswaan, dan jadwal kejuruan.</p>
    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white border border-stone-200 rounded-lg p-4 sm:p-5 shadow-sm space-y-4">
        <form method="GET" action="{{ url('/informasi') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <input type="text" name="cari" value="{{ request('cari') }}"
                    placeholder="Cari kata kunci judul atau isi pengumuman..."
                    class="w-full pl-9 pr-4 py-2 text-sm rounded border border-stone-300 focus:outline-none focus:border-stone-800 transition">
                <svg class="w-4 h-4 text-stone-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <input type="hidden" name="kategori" value="{{ request('kategori', 'Semua') }}">

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-stone-900 text-white text-xs font-semibold rounded hover:bg-stone-800 transition">
                    Cari
                </button>
                @if(request('cari') || (request('kategori') && request('kategori') !== 'Semua'))
                    <a href="{{ url('/informasi') }}" class="px-3 py-2 border border-stone-300 text-stone-600 text-xs font-medium rounded hover:bg-stone-100 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Kategori Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pt-1 text-xs">
            <span class="text-stone-400 font-semibold uppercase tracking-wider mr-1">Kategori:</span>
            <a href="{{ url('/informasi?' . http_build_query(array_merge(request()->except('page'), ['kategori' => 'Semua']))) }}"
                class="px-3 py-1.5 rounded-full font-medium transition {{ !request('kategori') || request('kategori') === 'Semua' ? 'bg-stone-900 text-white' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
                Semua
            </a>
            @foreach($kategoriList as $kat)
                <a href="{{ url('/informasi?' . http_build_query(array_merge(request()->except('page'), ['kategori' => $kat]))) }}"
                    class="px-3 py-1.5 rounded-full font-medium transition {{ request('kategori') === $kat ? 'bg-stone-900 text-white' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
                    {{ $kat }}
                </a>
            @endforeach
        </div>
    </div>
</div>

@if($informasis->isEmpty())
    <div class="text-center py-16 bg-white border border-stone-200 rounded-lg">
        <p class="font-editorial text-xl font-bold text-stone-700">Tidak ada pengumuman ditemukan</p>
        <p class="text-stone-500 text-xs mt-1">Coba gunakan kata kunci pencarian atau kategori lain.</p>
        @if(request('cari') || request('kategori'))
            <a href="{{ url('/informasi') }}" class="inline-block mt-4 text-xs font-semibold text-stone-900 underline">
                Kembali ke semua pengumuman
            </a>
        @endif
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($informasis as $info)
            <article class="bg-white border {{ $info->is_pinned ? 'border-amber-300 ring-1 ring-amber-200 shadow-sm' : 'border-stone-200' }} rounded-lg overflow-hidden flex flex-col justify-between hover:border-stone-400 transition">
                @if($info->gambar)
                    <div class="h-44 w-full bg-stone-100 overflow-hidden relative">
                        <img src="{{ asset('storage/' . $info->gambar) }}" alt="{{ $info->judul }}" class="w-full h-full object-cover">
                        @if($info->is_pinned)
                            <span class="absolute top-3 left-3 inline-flex items-center px-2.5 py-1 rounded bg-amber-600 text-white text-[11px] font-bold shadow-sm">
                                PENTING
                            </span>
                        @endif
                    </div>
                @endif

                <div class="p-6 flex-grow">
                    <div class="flex items-center justify-between text-xs text-stone-500 mb-3 flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            @if(!$info->gambar && $info->is_pinned)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                    PENTING
                                </span>
                            @endif
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium 
                                @if(strtolower($info->kategori) === 'akademik') bg-sky-50 text-sky-800 border border-sky-200
                                @elseif(strtolower($info->kategori) === 'ekstrakurikuler') bg-emerald-50 text-emerald-800 border border-emerald-200
                                @else bg-amber-50 text-amber-800 border border-amber-200 @endif">
                                {{ $info->kategori }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-[11px]">
                            <span title="Dilihat">{{ $info->views }}x dibaca</span>
                            <span>&bull;</span>
                            <time datetime="{{ $info->created_at->toISOString() }}">{{ $info->created_at->format('d M Y') }}</time>
                        </div>
                    </div>

                    <h2 class="font-editorial text-xl font-bold text-stone-900 mb-2 leading-snug">
                        <a href="{{ url('/informasi/' . $info->id) }}" class="hover:text-stone-600 transition">
                            {{ $info->judul }}
                        </a>
                    </h2>

                    <p class="text-sm text-stone-600 leading-relaxed mb-4">
                        {{ \Illuminate\Support\Str::limit($info->isi, 120) }}
                    </p>

                    @if($info->lampiran)
                        <div class="mb-2">
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-stone-600 bg-stone-100 px-2.5 py-1 rounded">
                                <svg class="w-3 h-3 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                Lampiran berkas tersedia
                            </span>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-stone-50/50 border-t border-stone-100 flex items-center justify-between">
                    <span class="text-xs text-stone-400">Oleh {{ $info->user->name ?? 'Admin Sekolah' }}</span>
                    <a href="{{ url('/informasi/' . $info->id) }}" class="text-xs font-semibold text-stone-900 hover:text-stone-600 inline-flex items-center gap-1 group">
                        Baca Selengkapnya
                        <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $informasis->links() }}
    </div>
@endif
@endsection
