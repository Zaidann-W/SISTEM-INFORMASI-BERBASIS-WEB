<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $totalInformasi = Informasi::count();
        $totalPublished = Informasi::where('status', 'published')->count();
        $totalDraft = Informasi::where('status', 'draft')->count();
        $totalViews = Informasi::sum('views');
        $totalUser = User::count();

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

        return view('admin.dashboard', compact(
            'totalInformasi',
            'totalPublished',
            'totalDraft',
            'totalViews',
            'totalUser',
            'informasis'
        ));
    }
}
