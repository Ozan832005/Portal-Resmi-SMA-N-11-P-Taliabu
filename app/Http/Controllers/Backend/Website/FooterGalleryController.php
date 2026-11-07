<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\FooterGalleryRequest;
use App\Models\FooterGallery;
use App\Support\ImageStorage;
use Illuminate\Support\Facades\DB;
use Session;

class FooterGalleryController extends Controller
{
    /**
     * Galeri foto footer + form unggah pada satu halaman.
     */
    public function index()
    {
        $gallery = FooterGallery::orderBy('urutan')->orderBy('id')->get();

        return view('backend.website.content.footerGallery.index', compact('gallery'));
    }

    public function create()
    {
        return redirect()->route('backend-footer-gallery.index');
    }

    public function store(FooterGalleryRequest $request)
    {
        try {
            DB::beginTransaction();

            $galeri = new FooterGallery;
            $galeri->image = ImageStorage::store($request->file('image'), 'footer');
            $galeri->caption = $request->caption;
            $galeri->urutan = $request->urutan ?? 0;
            $galeri->is_active = $request->is_active;
            $galeri->save();

            DB::commit();
            Session::flash('success', 'Foto footer Berhasil ditambah !');
        } catch (\Throwable $e) {
            DB::rollback();

            return back()->withInput()->with('error', 'Foto footer gagal disimpan: '.$e->getMessage());
        }

        return redirect()->route('backend-footer-gallery.index');
    }

    public function show($id)
    {
        return redirect()->route('backend-footer-gallery.index');
    }

    public function edit($id)
    {
        $galeri = FooterGallery::findOrFail($id);
        $semua = FooterGallery::orderBy('urutan')->orderBy('id')->get();

        return view('backend.website.content.footerGallery.edit', [
            'galeri' => $galeri,
            'semua' => $semua,
        ]);
    }

    public function update(FooterGalleryRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $galeri = FooterGallery::findOrFail($id);
            $gambarBaru = ImageStorage::store($request->file('image'), 'footer');
            $gambarLama = $galeri->image;

            $galeri->image = $gambarBaru ?? $gambarLama;
            $galeri->caption = $request->caption;
            $galeri->urutan = $request->urutan ?? 0;
            $galeri->is_active = $request->is_active;
            $galeri->save();

            if ($gambarBaru) {
                ImageStorage::delete($gambarLama, 'footer');
            }

            DB::commit();
            Session::flash('success', 'Foto footer Berhasil diupdate !');
        } catch (\Throwable $e) {
            DB::rollback();

            return back()->withInput()->with('error', 'Foto footer gagal diupdate: '.$e->getMessage());
        }

        return redirect()->route('backend-footer-gallery.index');
    }

    public function destroy($id)
    {
        $galeri = FooterGallery::findOrFail($id);

        ImageStorage::delete($galeri->image, 'footer');
        $galeri->delete();

        Session::flash('success', 'Foto footer Berhasil dihapus !');

        return redirect()->route('backend-footer-gallery.index');
    }
}
