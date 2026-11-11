<?php

namespace Modules\PPDB\Http\Controllers;

use App\Models\Curriculum;
use App\Models\Fasilitas;
use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class PPDBController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $curriculum = Curriculum::where('is_active', true)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();
        $jumlahGuru = User::where('role', 'Guru')->where('status', 'Aktif')->count();
        $jumlahFasilitas = Fasilitas::where('is_active', '0')->count();
        $jumlahKegiatan = Kegiatan::where('is_active', '0')->count();
        $jumlahSiswa = User::whereIn('role', ['Guest', 'Murid'])
            ->where('status', 'Aktif')
            ->count();

        return view('ppdb::index', compact(
            'curriculum',
            'jumlahGuru',
            'jumlahFasilitas',
            'jumlahKegiatan',
            'jumlahSiswa'
        ));
    }
}
