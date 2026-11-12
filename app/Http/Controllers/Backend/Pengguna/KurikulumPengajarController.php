<?php

namespace App\Http\Controllers\Backend\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\Curriculum;
use Illuminate\View\View;

class KurikulumPengajarController extends Controller
{
    public function index(): View
    {
        $curricula = Curriculum::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('backend.pengguna.pengajar.kurikulum', compact('curricula'));
    }
}
