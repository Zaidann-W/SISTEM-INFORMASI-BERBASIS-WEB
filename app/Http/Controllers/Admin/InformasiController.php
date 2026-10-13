<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InformasiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Informasi::query();

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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $informasis = $query->orderByDesc('is_pinned')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.informasi.index', compact('informasis'));
    }

    public function create(): View
    {
        return view('admin.informasi.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:50'],
            'isi' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'lampiran' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx', 'max:5120'],
            'is_pinned' => ['nullable', 'boolean'],
            'status' => ['required', 'in:published,draft'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_pinned'] = $request->boolean('is_pinned');

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('informasi/gambar', 'public');
        }

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $validated['lampiran_nama'] = $file->getClientOriginalName();
            $validated['lampiran'] = $file->store('informasi/lampiran', 'public');
        }

        Informasi::create($validated);

        return redirect()->route('admin.informasi.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit(Informasi $informasi): View
    {
        return view('admin.informasi.edit', compact('informasi'));
    }

    public function update(Request $request, Informasi $informasi): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:50'],
            'isi' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'lampiran' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx', 'max:5120'],
            'is_pinned' => ['nullable', 'boolean'],
            'status' => ['required', 'in:published,draft'],
            'hapus_gambar' => ['nullable', 'boolean'],
            'hapus_lampiran' => ['nullable', 'boolean'],
        ]);

        $validated['is_pinned'] = $request->boolean('is_pinned');

        // Handle gambar
        if ($request->boolean('hapus_gambar') && $informasi->gambar) {
            Storage::disk('public')->delete($informasi->gambar);
            $validated['gambar'] = null;
        } elseif ($request->hasFile('gambar')) {
            if ($informasi->gambar) {
                Storage::disk('public')->delete($informasi->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('informasi/gambar', 'public');
        }

        // Handle lampiran
        if ($request->boolean('hapus_lampiran') && $informasi->lampiran) {
            Storage::disk('public')->delete($informasi->lampiran);
            $validated['lampiran'] = null;
            $validated['lampiran_nama'] = null;
        } elseif ($request->hasFile('lampiran')) {
            if ($informasi->lampiran) {
                Storage::disk('public')->delete($informasi->lampiran);
            }
            $file = $request->file('lampiran');
            $validated['lampiran_nama'] = $file->getClientOriginalName();
            $validated['lampiran'] = $file->store('informasi/lampiran', 'public');
        }

        $informasi->update($validated);

        return redirect()->route('admin.informasi.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Informasi $informasi): RedirectResponse
    {
        if ($informasi->gambar) {
            Storage::disk('public')->delete($informasi->gambar);
        }

        if ($informasi->lampiran) {
            Storage::disk('public')->delete($informasi->lampiran);
        }

        $informasi->delete();

        return redirect()->back()->with('success', 'Pengumuman dan berkas terkait berhasil dihapus.');
    }
}
