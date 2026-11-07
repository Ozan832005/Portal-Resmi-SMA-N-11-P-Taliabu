<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Footer;
use App\Models\Jurusan;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Program Studi
    public function programStudi($slug)
    {
        $jurusan = Jurusan::with('dataJurusan')->where('slug', $slug)->firstOrFail();

        // Menu
        $jurusanM = Jurusan::where('is_active','0')->get();
        $kegiatanM = Kegiatan::where('is_active','0')->get();

        // Footer
        $footer = Footer::first();

        return view('frontend.program.jurusan.show', compact('jurusan','jurusanM','kegiatanM','footer'));
    }

    // Kegiatan
    public function kegiatan($slug)
    {
        $kegiatan = Kegiatan::where('slug', $slug)->firstOrFail();

        // Menu
        $jurusanM = Jurusan::where('is_active','0')->get();
        $kegiatanM = Kegiatan::where('is_active','0')->get();

        // Footer
        $footer = Footer::first();

        return view('frontend.program.kegiatan.show', compact('kegiatan','jurusanM','kegiatanM','footer'));
    }
}
