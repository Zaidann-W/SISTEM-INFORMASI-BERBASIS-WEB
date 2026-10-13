<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiswaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Informasi::where('status', 'published');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                    ->orWhere('isi', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        $informasis = $query->orderByDesc('is_pinned')
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $kategoriList = ['Akademik', 'Pengumuman', 'Ekstrakurikuler'];

        return view('informasi.index', compact('informasis', 'kategoriList'));
    }

    public function show($id): View
    {
        $informasi = Informasi::where('status', 'published')->findOrFail($id);

        $informasi->increment('views');

        $terkait = Informasi::where('status', 'published')
            ->where('id', '!=', $informasi->id)
            ->where('kategori', $informasi->kategori)
            ->latest()
            ->take(3)
            ->get();

        return view('informasi.show', compact('informasi', 'terkait'));
    }
}
