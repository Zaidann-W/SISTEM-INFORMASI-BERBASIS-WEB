@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <a href="{{ url('/informasi') }}" class="text-xs font-semibold text-stone-500 hover:text-stone-900 inline-flex items-center gap-1.5 transition">
            &larr; Kembali ke Semua Informasi
        </a>
        <button onclick="window.print()" class="text-xs font-medium text-stone-600 hover:text-stone-900 border border-stone-300 px-3 py-1.5 rounded bg-white hover:bg-stone-50 transition inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Pengumuman
        </button>
    </div>

    <article class="bg-white border border-stone-200 rounded-lg overflow-hidden shadow-sm">
        @if($informasi->gambar)
            <div class="w-full h-72 sm:h-96 bg-stone-100 overflow-hidden">
                <img src="{{ asset('storage/' . $informasi->gambar) }}" alt="{{ $informasi->judul }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-8 sm:p-12">
            <header class="mb-8 border-b border-stone-100 pb-8">
                <div class="flex items-center flex-wrap gap-2 text-xs text-stone-500 mb-4">
                    @if($informasi->is_pinned)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                            PENTING
                        </span>
                    @endif
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium 
                        @if(strtolower($informasi->kategori) === 'akademik') bg-sky-50 text-sky-800 border border-sky-200
                        @elseif(strtolower($informasi->kategori) === 'ekstrakurikuler') bg-emerald-50 text-emerald-800 border border-emerald-200
                        @else bg-amber-50 text-amber-800 border border-amber-200 @endif">
                        {{ $informasi->kategori }}
                    </span>
                    <span>&bull;</span>
                    <time datetime="{{ $informasi->created_at->toISOString() }}">{{ $informasi->created_at->format('d F Y') }}</time>
                    <span>&bull;</span>
                    <span>{{ $informasi->views }} kali dibaca</span>
                    <span>&bull;</span>
                    <span>Penulis: {{ $informasi->user->name ?? 'Admin Sekolah' }}</span>
                </div>

                <h1 class="font-editorial text-2xl sm:text-4xl font-bold text-stone-900 leading-tight">
                    {{ $informasi->judul }}
                </h1>
            </header>

            <div class="text-stone-800 text-base sm:text-lg leading-relaxed whitespace-pre-line font-normal space-y-4">
                {{ $informasi->isi }}
            </div>

            <!-- Box Lampiran Dokumen -->
            @if($informasi->lampiran)
                <div class="mt-10 p-5 rounded-lg bg-stone-50 border border-stone-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded bg-stone-200 flex items-center justify-center text-stone-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs uppercase tracking-wider text-stone-400 font-bold">Lampiran Resmi</div>
                            <div class="text-sm font-semibold text-stone-900">{{ $informasi->lampiran_nama ?? 'Dokumen Surat Edaran' }}</div>
                        </div>
                    </div>
                    <div>
                        <a href="{{ asset('storage/' . $informasi->lampiran) }}" download target="_blank"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded bg-stone-900 text-white text-xs font-semibold hover:bg-stone-800 transition">
                            Unduh Dokumen &darr;
                        </a>
                    </div>
                </div>
            @endif

            <footer class="mt-12 pt-6 border-t border-stone-100 flex items-center justify-between text-xs text-stone-500">
                <span>Diterbitkan resmi melalui Portal Informasi & Pengumuman Sekolah</span>
                <a href="{{ url('/informasi') }}" class="font-medium text-stone-900 hover:underline">
                    Daftar Pengumuman Lainnya &rarr;
                </a>
            </footer>
        </div>
    </article>

    <!-- Pengumuman Terkait -->
    @if(isset($terkait) && $terkait->isNotEmpty())
        <div class="bg-white border border-stone-200 rounded-lg p-6">
            <h2 class="font-editorial text-lg font-bold text-stone-900 mb-4">Pengumuman Terkait Kategori {{ $informasi->kategori }}</h2>
            <div class="divide-y border-t border-stone-100 text-sm">
                @foreach($terkait as $t)
                    <div class="py-3 flex items-center justify-between">
                        <a href="{{ url('/informasi/' . $t->id) }}" class="font-medium text-stone-800 hover:text-stone-900 hover:underline">
                            {{ $t->judul }}
                        </a>
                        <span class="text-xs text-stone-400 whitespace-nowrap ml-4">{{ $t->created_at->format('d M Y') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
